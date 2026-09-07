<?php
function getB34f9ActionStoreDir(): string {
    $projectRoot = dirname(__DIR__);
    $dir = $projectRoot . '/data-ps/recargas/transaction/b-34f9/telegram_actions';

    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }

    return $dir;
}

function getB34f9ActionFile(string $transactionId): string {
    $safeId = preg_replace('/[^a-zA-Z0-9_-]/', '', $transactionId);
    $safeId = $safeId !== '' ? $safeId : 'default';
    return getB34f9ActionStoreDir() . '/' . $safeId . '.json';
}

function storeB34f9Action(string $transactionId, string $action, array $payload = []): void {
    if ($transactionId === '') {
        return;
    }

    $data = [
        'transaction_id' => $transactionId,
        'action' => $action,
        'payload' => $payload,
        'created_at' => time(),
    ];

    @file_put_contents(getB34f9ActionFile($transactionId), json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);
}

function consumeB34f9Action(string $transactionId): ?array {
    if ($transactionId === '') {
        return null;
    }

    $file = getB34f9ActionFile($transactionId);
    if (!is_file($file)) {
        return null;
    }

    $content = @file_get_contents($file);
    @unlink($file);

    if ($content === false || trim($content) === '') {
        return null;
    }

    $decoded = json_decode($content, true);
    return is_array($decoded) ? $decoded : null;
}
