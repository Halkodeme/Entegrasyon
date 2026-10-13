<?php
$configFile = __DIR__ . DIRECTORY_SEPARATOR . 'halkode-config.php';

if (!is_file($configFile)) {
    http_response_code(500);
    exit('HalkÖde yapılandırması eksik. halkode-config.php dosyasını kontrol edin.');
}

$config = require $configFile;

if (!is_array($config)) {
    http_response_code(500);
    exit('HalkÖde yapılandırması geçersiz.');
}

$merchantKey = trim((string)($config['merchant_key'] ?? ''));
$app_id = trim((string)($config['app_id'] ?? ''));
$appSecret = trim((string)($config['app_secret'] ?? ''));
$merchant_key = $merchantKey;

if ($merchantKey === '' || $app_id === '' || $appSecret === '') {
    http_response_code(500);
    exit('HalkÖde yapılandırması eksik. halkode-config.php içine Merchant Key, App ID ve App Secret bilgilerini girin.');
}
