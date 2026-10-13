<?php
require_once dirname(__DIR__, 2) . '/security.php';
require_once dirname(__DIR__, 2) . '/api_security.php';
include dirname(__DIR__, 2) . '/degisken.php';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    exit;
}

halkode_require_sensitive_action();

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

$token = halkode_get_token($app_id, $appSecret);

if ($token === null) {
    http_response_code(502);
    echo json_encode(['status' => 'error', 'message' => 'Token alınamadı.'], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode(['status' => 'ok', 'message' => 'Token güvenli şekilde alındı.'], JSON_UNESCAPED_UNICODE);
