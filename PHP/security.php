<?php
function halkode_is_https_request()
{
    $https = strtolower((string)($_SERVER['HTTPS'] ?? ''));
    return $https !== '' && $https !== 'off' && $https !== '0';
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    if (!headers_sent()) {
        session_set_cookie_params([
            'httponly' => true,
            'secure' => halkode_is_https_request(),
            'samesite' => 'Lax',
            'path' => '/',
        ]);
    }
    session_start();
}

function halkode_security_headers()
{
    if (!headers_sent()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
        header("Content-Security-Policy: default-src 'self' https: data:; script-src 'self' 'unsafe-inline' https:; style-src 'self' 'unsafe-inline' https:; img-src 'self' data: https:; font-src 'self' data: https:; connect-src 'self' https:; form-action 'self' https:; frame-ancestors 'self'; base-uri 'self'; object-src 'none'");
    }
}

function halkode_is_loopback_request()
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    return in_array($ip, ['127.0.0.1', '::1'], true);
}

function halkode_require_remote_access()
{
    if (halkode_is_loopback_request()) {
        return;
    }

    $expected = getenv('HALKODE_DEMO_ACCESS_KEY');
    if (!is_string($expected) || $expected === '') {
        http_response_code(403);
        exit('Remote test işlemleri kapalı. HALKODE_DEMO_ACCESS_KEY tanımlayın veya localhost kullanın.');
    }

    $user = $_SERVER['PHP_AUTH_USER'] ?? '';
    $pass = $_SERVER['PHP_AUTH_PW'] ?? '';

    if ($user !== 'halkode' || !hash_equals($expected, (string)$pass)) {
        header('WWW-Authenticate: Basic realm="HalkOde Test Integration"');
        http_response_code(401);
        exit('Yetkisiz erişim.');
    }
}

function halkode_require_sensitive_action()
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        return;
    }

    if (halkode_is_loopback_request()) {
        return;
    }

    halkode_require_remote_access();

    $host = strtolower(preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST'] ?? ''));
    $origin = trim((string)($_SERVER['HTTP_ORIGIN'] ?? ''));
    $referer = trim((string)($_SERVER['HTTP_REFERER'] ?? ''));
    $source = $origin !== '' ? $origin : $referer;

    if ($source === '') {
        http_response_code(403);
        exit('İstek kaynağı doğrulanamadı.');
    }

    $sourceHost = strtolower((string)parse_url($source, PHP_URL_HOST));
    if ($host === '' || $sourceHost === '' || !hash_equals($host, $sourceHost)) {
        http_response_code(403);
        exit('İstek kaynağı doğrulanamadı.');
    }
}

function halkode_random_invoice_id($prefix = 'TEST')
{
    try {
        return $prefix . '-' . date('YmdHis') . '-' . bin2hex(random_bytes(8));
    } catch (Throwable $e) {
        http_response_code(500);
        exit('Güvenli işlem numarası üretilemedi.');
    }
}

function halkode_mask_secret($value)
{
    $value = (string)$value;
    $length = strlen($value);
    if ($length <= 8) {
        return str_repeat('*', max($length, 4));
    }
    return substr($value, 0, 4) . str_repeat('*', max($length - 8, 4)) . substr($value, -4);
}

function halkode_safe_display_payload(array $payload)
{
    $sensitiveKeys = [
        'merchant_key', 'app_secret', 'app_id', 'hash_key', 'token',
        'authorization', 'cvv', 'cc_no', 'credit_card'
    ];

    $walk = function ($value, $key = '') use (&$walk, $sensitiveKeys) {
        if (is_array($value)) {
            $out = [];
            foreach ($value as $childKey => $childValue) {
                $out[$childKey] = $walk($childValue, (string)$childKey);
            }
            return $out;
        }

        if (in_array(strtolower($key), $sensitiveKeys, true)) {
            if (in_array(strtolower($key), ['cc_no', 'credit_card'], true)) {
                $digits = preg_replace('/\D+/', '', (string)$value);
                if (strlen($digits) >= 10) {
                    return substr($digits, 0, 6) . str_repeat('*', strlen($digits) - 10) . substr($digits, -4);
                }
            }
            return halkode_mask_secret((string)$value);
        }

        return $value;
    };

    return $walk($payload);
}

function halkode_remember_payment_attempt($invoiceId, $total, $transactionType, $installment)
{
    $invoiceId = trim((string)$invoiceId);
    if ($invoiceId === '' || strlen($invoiceId) > 128 || $total <= 0) {
        return false;
    }

    if (!isset($_SESSION['halkode_expected_payments']) || !is_array($_SESSION['halkode_expected_payments'])) {
        $_SESSION['halkode_expected_payments'] = [];
    }

    $now = time();
    foreach ($_SESSION['halkode_expected_payments'] as $key => $item) {
        if (!is_array($item) || ($now - (int)($item['created_at'] ?? 0)) > 1800) {
            unset($_SESSION['halkode_expected_payments'][$key]);
        }
    }

    $_SESSION['halkode_expected_payments'][$invoiceId] = [
        'invoice_id' => $invoiceId,
        'total' => (float)$total,
        'transaction_type' => strcasecmp((string)$transactionType, 'PreAuth') === 0 ? 'PreAuth' : 'Auth',
        'installment' => max(1, (int)$installment),
        'created_at' => $now,
    ];

    return true;
}

function halkode_get_expected_payment($invoiceId)
{
    $invoiceId = trim((string)$invoiceId);
    $item = $_SESSION['halkode_expected_payments'][$invoiceId] ?? null;
    if (!is_array($item)) {
        return null;
    }

    if ((time() - (int)($item['created_at'] ?? 0)) > 1800) {
        unset($_SESSION['halkode_expected_payments'][$invoiceId]);
        return null;
    }

    return $item;
}

function halkode_forget_payment_attempt($invoiceId)
{
    unset($_SESSION['halkode_expected_payments'][(string)$invoiceId]);
}

function halkode_public_url($path)
{
    $configured = trim((string)(getenv('HALKODE_PUBLIC_BASE_URL') ?: ''));
    if ($configured !== '') {
        $parts = parse_url($configured);
        $scheme = strtolower((string)($parts['scheme'] ?? ''));
        $host = strtolower((string)($parts['host'] ?? ''));
        if ($host !== '' && ($scheme === 'https' || ($scheme === 'http' && in_array($host, ['localhost', '127.0.0.1', '::1'], true)))) {
            return rtrim($configured, '/') . '/' . ltrim((string)$path, '/');
        }
        return '';
    }

    $host = strtolower(preg_replace('/:\d+$/', '', (string)($_SERVER['HTTP_HOST'] ?? '')));
    if (!in_array($host, ['localhost', '127.0.0.1', '::1'], true)) {
        return '';
    }

    $scheme = halkode_is_https_request() ? 'https' : 'http';
    $httpHost = (string)($_SERVER['HTTP_HOST'] ?? 'localhost');
    $dir = rtrim(str_replace('\\', '/', dirname((string)($_SERVER['SCRIPT_NAME'] ?? '/'))), '/');
    return $scheme . '://' . $httpHost . ($dir !== '' ? $dir : '') . '/' . ltrim((string)$path, '/');
}

halkode_security_headers();
halkode_require_remote_access();
