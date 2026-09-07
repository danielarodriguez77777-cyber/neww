<?php
header('Content-Type: application/json');
http_response_code(200);

require_once __DIR__ . '/includes/telegram_actions.php';

$configPath = __DIR__ . '/config.php';
$config = file_exists($configPath) ? require $configPath : [];
$botToken = $config['bot_token'] ?? '';

if (empty($botToken)) {
    echo json_encode(['ok' => false, 'error' => 'Bot token missing']);
    exit;
}

$raw = file_get_contents('php://input');
if ($raw === '') {
    echo json_encode(['ok' => true, 'status' => 'empty']);
    exit;
}

$update = json_decode($raw, true);
if (!is_array($update)) {
    echo json_encode(['ok' => false, 'error' => 'Invalid JSON']);
    exit;
}

if (!isset($update['callback_query'])) {
    echo json_encode(['ok' => true, 'status' => 'ignored']);
    exit;
}

$callback = $update['callback_query'];
$callbackData = (string)($callback['data'] ?? '');
$action = $callbackData;
$transactionId = '';

if ($callbackData !== '') {
    $parts = explode(':', $callbackData, 2);
    if (isset($parts[0])) {
        $action = $parts[0];
    }
    if (isset($parts[1])) {
        $transactionId = $parts[1];
    }
}

if ($transactionId !== '' && $action !== '') {
    storeB34f9Action($transactionId, $action, [
        'callback_data' => $callbackData,
        'message_id' => $callback['message']['message_id'] ?? null,
        'chat_id' => $callback['message']['chat']['id'] ?? null,
    ]);
}

$telegramUrl = "https://api.telegram.org/bot{$botToken}/answerCallbackQuery";
$answer = curl_init($telegramUrl);
curl_setopt_array($answer, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode(['callback_query_id' => $callback['id']]),
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_TIMEOUT => 10,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
]);
curl_exec($answer);
curl_close($answer);

$message = $callback['message'] ?? [];
if (!empty($message['chat']['id']) && !empty($message['message_id']) && !empty($message['text'])) {
    $from = $callback['from'] ?? [];
    $username = $from['username'] ?? trim(($from['first_name'] ?? '') . ' ' . ($from['last_name'] ?? ''));
    $operator = $username !== '' ? $username : 'Operador';

    $newText = $message['text']
        . "\n\n————————————\n"
        . "✅ Acción: <b>" . htmlspecialchars(ucfirst(str_replace('_', ' ', $action)), ENT_QUOTES | ENT_HTML5, 'UTF-8') . "</b>\n"
        . "👤 Operador: <b>" . htmlspecialchars($operator, ENT_QUOTES | ENT_HTML5, 'UTF-8') . "</b>";

    $edit = curl_init("https://api.telegram.org/bot{$botToken}/editMessageText");
    curl_setopt_array($edit, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'chat_id' => $message['chat']['id'],
            'message_id' => $message['message_id'],
            'text' => $newText,
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode(['inline_keyboard' => []]),
        ]),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ]);
    curl_exec($edit);
    curl_close($edit);
}

echo json_encode([
    'ok' => true,
    'action' => $action,
    'transaction_id' => $transactionId,
]);
