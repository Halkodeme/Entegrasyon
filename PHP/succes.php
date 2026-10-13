<?php
require_once __DIR__ . '/security.php';
require_once __DIR__ . '/api_security.php';
halkode_security_headers();
include __DIR__ . '/degisken.php';

$invoiceId = trim((string)($_GET['invoice_id'] ?? $_POST['invoice_id'] ?? ''));
$verified = false;
$status = null;
$expected = null;

if ($invoiceId !== '' && strlen($invoiceId) <= 128) {
    $expected = halkode_get_expected_payment($invoiceId);
    if (is_array($expected)) {
        $status = halkode_check_status($invoiceId, $merchantKey, $app_id, $appSecret);
        if (is_array($status)) {
            $verified = halkode_verified_payment_status($status, $expected);
        }
    }
}

if ($verified) {
    halkode_forget_payment_attempt($invoiceId);
}

http_response_code($verified ? 200 : 400);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İşlem Sonucu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <?php include 'style.php'; ?>
</head>
<body>
<?php include 'nav.php'; ?>

<div class="container mt-5">
    <?php if ($verified): ?>
        <div class="alert alert-success">
            <h1 class="h4 mb-2">İşlem Doğrulandı</h1>
            Ödeme sonucu HalkÖde sunucusundan tekrar sorgulanarak doğrulandı.
        </div>
    <?php else: ?>
        <div class="alert alert-danger">
            <h1 class="h4 mb-2">İşlem Doğrulanamadı</h1>
            İşlem; yerel ödeme kaydı ve HalkÖde checkStatus cevabı birlikte doğrulanmadan başarılı kabul edilmedi.
        </div>
    <?php endif; ?>

    <div class="result-item">
        <span>invoice_id:</span> <?= htmlspecialchars($invoiceId, ENT_QUOTES, 'UTF-8') ?>
    </div>

    <?php if (is_array($status)): ?>
        <div class="result-item"><span>status_code:</span> <?= htmlspecialchars((string)($status['status_code'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></div>
        <div class="result-item"><span>transaction_status:</span> <?= htmlspecialchars((string)($status['transaction_status'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></div>
        <div class="result-item"><span>transaction_type:</span> <?= htmlspecialchars((string)($status['transaction_type'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></div>
        <div class="result-item"><span>order_id:</span> <?= htmlspecialchars((string)($status['order_id'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></div>
        <div class="result-item"><span>transaction_amount:</span> <?= htmlspecialchars((string)($status['transaction_amount'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
</div>

<footer><?php include 'footer.php'; ?></footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
