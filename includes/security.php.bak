<?php
declare(strict_types=1);

function jelpi_site_url(): string
{
    $configured = getenv('JELPI_SITE_URL') ?: 'https://ir.conjuntjelpitir.com';
    return rtrim($configured, '/');
}

function jelpi_canonical_url(string $path): string
{
    return jelpi_site_url() . '/' . ltrim($path, '/');
}

function jelpi_start_secure_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_secure', $isHttps ? '1' : '0');
    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.gc_maxlifetime', '1800');

    session_name('jelpi_session');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();

    if (empty($_SESSION['created_at'])) {
        $_SESSION['created_at'] = time();
    } elseif ((time() - (int) $_SESSION['created_at']) > 1800) {
        session_regenerate_id(true);
        $_SESSION['created_at'] = time();
    }
}

function jelpi_apply_security_headers(array $options = []): void
{
    $noindex = (bool) ($options['noindex'] ?? false);
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; img-src 'self' data: https:; font-src 'self' data: https://cdnjs.cloudflare.com; connect-src 'self' https://jel-api.lat https://cdn.jsdelivr.net; frame-ancestors 'none'; base-uri 'self'; form-action 'self'");

    if ($isHttps) {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }

    if ($noindex) {
        header('X-Robots-Tag: noindex, nofollow, noarchive');
    }
}

function jelpi_bootstrap(array $options = []): void
{
    jelpi_start_secure_session();
    jelpi_apply_security_headers($options);
}

function jelpi_csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return (string) $_SESSION['csrf_token'];
}

function jelpi_validate_csrf(?string $token): bool
{
    $stored = $_SESSION['csrf_token'] ?? '';
    return is_string($token) && $stored !== '' && hash_equals((string) $stored, $token);
}

function jelpi_csrf_field(): string
{
    $token = htmlspecialchars(jelpi_csrf_token(), ENT_QUOTES, 'UTF-8');
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

function jelpi_client_ip(): string
{
    $candidates = [
        $_SERVER['HTTP_CF_CONNECTING_IP'] ?? null,
        $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
        $_SERVER['REMOTE_ADDR'] ?? null,
    ];

    foreach ($candidates as $candidate) {
        if (!is_string($candidate) || $candidate === '') {
            continue;
        }

        $ip = trim(explode(',', $candidate)[0]);
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }
    }

    return '0.0.0.0';
}

function jelpi_enforce_rate_limit(string $scope, int $maxRequests, int $windowSeconds): void
{
    $storageDir = sys_get_temp_dir() . '/jelpi_rate_limit';
    if (!is_dir($storageDir) && !mkdir($storageDir, 0755, true) && !is_dir($storageDir)) {
        error_log('RATE_LIMIT: no se pudo crear directorio de almacenamiento');
        return;
    }

    $key = hash('sha256', $scope);
    $filePath = $storageDir . '/' . $key . '.json';
    $now = time();

    $fp = fopen($filePath, 'c+');
    if ($fp === false) {
        error_log('RATE_LIMIT: no se pudo abrir archivo de control');
        return;
    }

    if (!flock($fp, LOCK_EX)) {
        fclose($fp);
        error_log('RATE_LIMIT: no se pudo bloquear archivo de control');
        return;
    }

    $raw = stream_get_contents($fp);
    $data = json_decode($raw ?: '', true);

    if (!is_array($data) || !isset($data['count'], $data['reset_at']) || $now >= (int) $data['reset_at']) {
        $data = [
            'count' => 0,
            'reset_at' => $now + $windowSeconds,
        ];
    }

    $data['count'] = (int) $data['count'] + 1;

    $remaining = max(0, $maxRequests - (int) $data['count']);
    $retryAfter = max(1, (int) $data['reset_at'] - $now);

    header('X-RateLimit-Limit: ' . $maxRequests);
    header('X-RateLimit-Remaining: ' . $remaining);
    header('X-RateLimit-Reset: ' . (int) $data['reset_at']);

    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($data, JSON_UNESCAPED_UNICODE));
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);

    if ((int) $data['count'] > $maxRequests) {
        header('Retry-After: ' . $retryAfter);
        http_response_code(429);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => 'Demasiadas solicitudes. Intenta más tarde.'], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
