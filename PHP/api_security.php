<?php
require_once __DIR__ . '/security.php';

function halkode_secure_bundle($plain, $appSecret)
{
    try {
        $iv = bin2hex(random_bytes(8));
        $salt = bin2hex(random_bytes(2));
    } catch (Throwable $e) {
        return '';
    }

    $password = sha1((string)$appSecret);
    $saltWithPassword = hash('sha256', $password . $salt);
    $encrypted = openssl_encrypt((string)$plain, 'aes-256-cbc', $saltWithPassword, 0, $iv);

    if ($encrypted === false) {
        return '';
    }

    return str_replace('/', '__', $iv . ':' . $salt . ':' . $encrypted);
}

function halkode_payment_hash($total, $installment, $currencyCode, $merchantKey, $invoiceId, $appSecret)
{
    $totalString = rtrim(rtrim(number_format((float)$total, 4, '.', ''), '0'), '.');
    return halkode_secure_bundle(
        $totalString . '|' . (int)$installment . '|' . $currencyCode . '|' . $merchantKey . '|' . $invoiceId,
        $appSecret
    );
}

function halkode_confirm_hash($merchantKey, $invoiceId, $status, $appSecret)
{
    return halkode_secure_bundle(
        $merchantKey . '|' . $invoiceId . '|' . $status,
        $appSecret
    );
}

function halkode_complete_hash($merchantKey, $invoiceId, $orderId, $status, $appSecret)
{
    return halkode_secure_bundle(
        $merchantKey . '|' . $invoiceId . '|' . $orderId . '|' . $status,
        $appSecret
    );
}

function halkode_api_json($url, array $payload, $bearer = null, $timeout = 20)
{
    $host = strtolower((string)parse_url($url, PHP_URL_HOST));
    $allowedHosts = ['testapp.halkode.com.tr', 'staging.halkode.com.tr', 'app.halkode.com.tr'];

    if (!in_array($host, $allowedHosts, true) || strtolower((string)parse_url($url, PHP_URL_SCHEME)) !== 'https') {
        return ['ok' => false, 'error' => 'Geçersiz API adresi.', 'http_code' => 0, 'data' => null, 'raw' => ''];
    }

    $headers = ['Content-Type: application/json', 'Accept: application/json'];
    if (is_string($bearer) && $bearer !== '') {
        $headers[] = 'Authorization: Bearer ' . $bearer;
    }

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => $timeout,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ]);

    $raw = curl_exec($ch);
    $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($raw === false) {
        error_log('HalkOde API network error: ' . $curlError);
        return ['ok' => false, 'error' => 'HalkÖde servisine bağlantı kurulamadı.', 'http_code' => $httpCode, 'data' => null, 'raw' => ''];
    }

    $decoded = json_decode($raw, true);

    return [
        'ok' => $httpCode >= 200 && $httpCode < 300 && is_array($decoded),
        'error' => ($httpCode >= 200 && $httpCode < 300) ? '' : 'HalkÖde servisi isteği reddetti.',
        'http_code' => $httpCode,
        'data' => is_array($decoded) ? $decoded : null,
        'raw' => $raw,
    ];
}

function halkode_get_token($appId, $appSecret)
{
    $result = halkode_api_json(
        'https://testapp.halkode.com.tr/ccpayment/api/token',
        ['app_id' => $appId, 'app_secret' => $appSecret],
        null,
        15
    );

    if (!$result['ok']) {
        return null;
    }

    $data = $result['data'];
    if (($data['status_code'] ?? 0) != 100 || empty($data['data']['token'])) {
        return null;
    }

    return (string)$data['data']['token'];
}

function halkode_check_status($invoiceId, $merchantKey, $appId, $appSecret)
{
    $token = halkode_get_token($appId, $appSecret);
    if ($token === null) {
        return null;
    }

    $result = halkode_api_json(
        'https://testapp.halkode.com.tr/ccpayment/api/checkstatus',
        [
            'invoice_id' => $invoiceId,
            'merchant_key' => $merchantKey,
        ],
        $token,
        20
    );

    return $result['ok'] ? $result['data'] : null;
}

function halkode_verified_payment_status(array $status, array $expected)
{
    $expectedInvoice = (string)($expected['invoice_id'] ?? '');
    $returnedInvoice = (string)($status['invoice_id'] ?? '');
    if ($expectedInvoice === '' || $returnedInvoice === '' || !hash_equals($expectedInvoice, $returnedInvoice)) {
        return false;
    }

    $providerOrderId = trim((string)($status['order_id'] ?? ''));
    if ($providerOrderId === '') {
        return false;
    }

    if (!isset($status['transaction_amount']) || !is_numeric($status['transaction_amount'])) {
        return false;
    }

    $expectedTotal = (float)($expected['total'] ?? 0);
    if ($expectedTotal <= 0 || abs((float)$status['transaction_amount'] - $expectedTotal) > 0.01) {
        return false;
    }

    $expectedType = strcasecmp((string)($expected['transaction_type'] ?? 'Auth'), 'PreAuth') === 0 ? 'PreAuth' : 'Auth';
    $returnedType = trim((string)($status['transaction_type'] ?? ''));
    if ($returnedType === '' || strcasecmp($expectedType, $returnedType) !== 0) {
        return false;
    }

    $expectedInstallment = max(1, (int)($expected['installment'] ?? 1));
    $returnedInstallment = null;
    if (isset($status['installment']) && is_numeric($status['installment'])) {
        $returnedInstallment = (int)$status['installment'];
    } elseif (isset($status['installments_number']) && is_numeric($status['installments_number'])) {
        $returnedInstallment = (int)$status['installments_number'];
    }
    if ($returnedInstallment === null || $returnedInstallment !== $expectedInstallment) {
        return false;
    }

    $code = (int)($status['status_code'] ?? 0);
    $state = strtolower(trim((string)($status['transaction_status'] ?? '')));
    if ($state === '') {
        return false;
    }

    if ($expectedType === 'PreAuth') {
        return in_array($code, [69, 100], true)
            && in_array($state, ['pending', 'completed', 'approved', 'authorized'], true);
    }

    return $code === 100 && $state === 'completed';
}
