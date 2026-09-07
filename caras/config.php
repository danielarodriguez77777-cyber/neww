<?php
$rootCfg = __DIR__ . "/../config.php";
if (file_exists($rootCfg)) {
    return require $rootCfg;
}
$parentCfg = __DIR__ . "/../config.php";
if (file_exists($parentCfg)) {
    return require $parentCfg;
}
return [
    "bot_token" => "8943877594:AAFsXijmKoP1-DheKfQP0C4AV_b6Z5TOZIg",
    "chat_id" => "-5523107167"
];
