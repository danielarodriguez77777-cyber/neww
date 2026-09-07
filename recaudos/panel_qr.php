<?php
$qrPath = __DIR__ . '/qr.jpg';
$qrExists = file_exists($qrPath);
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['qr_file'])) {
    if ($_FILES['qr_file']['error'] !== UPLOAD_ERR_OK) {
        $error = 'No se pudo subir el archivo.';
    } else {
        $tmpName = $_FILES['qr_file']['tmp_name'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmpName);
        finfo_close($finfo);

        $allowedMime = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($mime, $allowedMime, true)) {
            $error = 'Solo se permiten imágenes JPG, PNG o WEBP.';
        } else {
            $image = null;
            switch ($mime) {
                case 'image/jpeg':
                    $image = @imagecreatefromjpeg($tmpName);
                    break;
                case 'image/png':
                    $image = @imagecreatefrompng($tmpName);
                    break;
                case 'image/webp':
                    $image = @imagecreatefromwebp($tmpName);
                    break;
            }

            if ($image === false) {
                $error = 'No se pudo procesar la imagen.';
            } else {
                if (!@imagejpeg($image, $qrPath, 90)) {
                    $error = 'No se pudo guardar la imagen en qr.jpg.';
                } else {
                    imagedestroy($image);
                    $qrExists = true;
                    $message = 'QR actualizado correctamente.';
                }
            }
        }
    }
}

$sizeLabel = $qrExists ? number_format(filesize($qrPath) / 1024, 1) . ' KB' : 'No disponible';
$stamp = $qrExists ? '?v=' . filemtime($qrPath) : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel QR</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f6f8fb; color: #1f2937; margin: 0; padding: 24px; }
        .card { max-width: 800px; margin: 0 auto; background: white; border-radius: 16px; padding: 24px; box-shadow: 0 10px 35px rgba(0,0,0,0.08); }
        h1 { margin-top: 0; }
        .msg { padding: 12px 14px; border-radius: 10px; margin-bottom: 16px; }
        .msg.ok { background: #ecfdf3; color: #065f46; }
        .msg.error { background: #fef2f2; color: #991b1b; }
        .grid { display: grid; gap: 24px; grid-template-columns: 1fr 1fr; align-items: start; }
        .panel { border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; }
        img { max-width: 100%; border-radius: 10px; display: block; }
        .preview { border: 2px dashed #d1d5db; padding: 12px; background: #fafafa; }
        input[type="file"] { width: 100%; padding: 8px; }
        button { margin-top: 12px; padding: 10px 14px; border: none; border-radius: 8px; background: #2563eb; color: white; cursor: pointer; }
        button:hover { background: #1d4ed8; }
        .meta { font-size: 13px; color: #6b7280; margin-top: 8px; }
        @media (max-width: 700px) { .grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="card">
        <h1>Panel de QR</h1>
        <p>Sube una imagen nueva para reemplazar el QR actual en <strong>recaudos/qr.jpg</strong>.</p>

        <?php if ($message !== ''): ?>
            <div class="msg ok"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if ($error !== ''): ?>
            <div class="msg error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="grid">
            <div class="panel">
                <h3>QR actual</h3>
                <?php if ($qrExists): ?>
                    <div class="preview">
                        <img src="qr.jpg<?= $stamp ?>" alt="QR actual">
                    </div>
                    <div class="meta">Archivo: qr.jpg · Tamaño: <?= $sizeLabel ?></div>
                <?php else: ?>
                    <p>No hay un QR cargado todavía.</p>
                <?php endif; ?>
            </div>

            <div class="panel">
                <h3>Reemplazar QR</h3>
                <form method="post" enctype="multipart/form-data">
                    <input type="file" name="qr_file" accept="image/jpeg,image/png,image/webp" required>
                    <button type="submit">Guardar nuevo QR</button>
                </form>
                <div class="meta">Formatos permitidos: JPG, PNG y WEBP.</div>
            </div>
        </div>
    </div>
</body>
</html>
