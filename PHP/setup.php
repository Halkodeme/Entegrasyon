<?php
require_once __DIR__ . '/security.php';

halkode_security_headers();

if (!halkode_is_loopback_request()) {
    http_response_code(403);
    exit('Güvenlik nedeniyle ilk HalkÖde yapılandırması yalnızca localhost üzerinden yapılabilir.');
}

if (empty($_SESSION['halkode_setup_csrf'])) {
    try {
        $_SESSION['halkode_setup_csrf'] = bin2hex(random_bytes(32));
    } catch (Throwable $e) {
        http_response_code(500);
        exit('Güvenli kurulum anahtarı üretilemedi.');
    }
}

$return = basename((string)($_GET['return'] ?? $_POST['return'] ?? 'index.php'));
if ($return === '' || $return === 'setup.php' || !preg_match('/^[A-Za-z0-9._-]+\.php$/', $return)) {
    $return = 'index.php';
}

$error = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $csrf = (string)($_POST['csrf'] ?? '');
    if ($csrf === '' || !hash_equals((string)$_SESSION['halkode_setup_csrf'], $csrf)) {
        http_response_code(403);
        exit('Kurulum isteği doğrulanamadı.');
    }

    $merchantKey = trim((string)($_POST['merchant_key'] ?? ''));
    $appId = trim((string)($_POST['app_id'] ?? ''));
    $appSecret = trim((string)($_POST['app_secret'] ?? ''));

    if ($merchantKey === '' || $appId === '' || $appSecret === '') {
        $error = 'Merchant Key, App ID ve App Secret alanlarının üçü de zorunludur.';
    } elseif (strlen($merchantKey) > 512 || strlen($appId) > 512 || strlen($appSecret) > 512) {
        $error = 'Yapılandırma değerlerinden biri beklenenden uzun.';
    } else {
        $config = "<?php\nreturn " . var_export([
            'merchant_key' => $merchantKey,
            'app_id' => $appId,
            'app_secret' => $appSecret,
        ], true) . ";\n";

        $target = __DIR__ . DIRECTORY_SEPARATOR . 'halkode-config.php';
        $written = @file_put_contents($target, $config, LOCK_EX);

        if ($written === false) {
            $error = 'halkode-config.php oluşturulamadı. PHP klasörünün yazma iznini kontrol edin.';
        } else {
            @chmod($target, 0600);
            unset($_SESSION['halkode_setup_csrf']);
            header('Location: ' . $return, true, 303);
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>HalkÖde Yapılandırma</title>
    <style>
        body{font-family:Arial,sans-serif;background:#f4f6f8;margin:0;padding:40px}
        .box{max-width:620px;margin:auto;background:#fff;padding:28px;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,.08)}
        h1{margin-top:0;font-size:24px}
        label{display:block;margin:16px 0 6px;font-weight:600}
        input{width:100%;box-sizing:border-box;padding:11px;border:1px solid #ccd2d8;border-radius:8px}
        button{margin-top:20px;padding:12px 18px;border:0;border-radius:8px;background:#111827;color:#fff;cursor:pointer}
        .err{background:#fee2e2;color:#991b1b;padding:10px;border-radius:8px;margin-bottom:14px}
        .note{color:#4b5563;font-size:14px;line-height:1.5}
    </style>
</head>
<body>
<div class="box">
    <h1>HalkÖde yapılandırması</h1>
    <p class="note">Bu ekran yalnızca localhost üzerinden açılır. Bilgiler kod dosyalarına gömülmeden ayrı bir <strong>halkode-config.php</strong> dosyasına kaydedilir.</p>
    <?php if ($error !== ''): ?>
        <div class="err"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <form method="post" autocomplete="off">
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars((string)$_SESSION['halkode_setup_csrf'], ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="return" value="<?php echo htmlspecialchars($return, ENT_QUOTES, 'UTF-8'); ?>">
        <label for="merchant_key">Merchant Key</label>
        <input id="merchant_key" name="merchant_key" type="password" required autocomplete="new-password">
        <label for="app_id">App ID</label>
        <input id="app_id" name="app_id" type="text" required autocomplete="off">
        <label for="app_secret">App Secret</label>
        <input id="app_secret" name="app_secret" type="password" required autocomplete="new-password">
        <button type="submit">Kaydet ve devam et</button>
    </form>
</div>
</body>
</html>
