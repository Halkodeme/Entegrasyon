<?php
require_once __DIR__ . '/degisken.php';
 require_once __DIR__ . '/security.php';  halkode_require_sensitive_action();
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $ccNo = preg_replace('/\D+/', '', (string)($data['cc_no'] ?? ''));
    $ccNo = substr($ccNo, 0, 6);
    $total = (float)($data['total'] ?? 0);

    if (strlen($ccNo) !== 6 || $total <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Geçersiz BIN veya tutar.']);
        return;
    }

    $baseUrl = "https://testapp.halkode.com.tr/ccpayment/api/getpos";
    
    $currencyCode = "TRY";

    
    $tokenResponse = getToken($app_id, $appSecret);
    $decodedTokenResponse = json_decode($tokenResponse, true);

    if ($decodedTokenResponse['status_code'] == 100) {
        $token = $decodedTokenResponse['data']['token'];
    } else {
        echo json_encode(['error' => 'Token alınamadı.']);
        return;
    }

    $data = array(
        "merchant_key" => $merchantKey,
        "credit_card" => $ccNo,
        "amount" => $total,
        "currency_code" => $currencyCode,
    );

    $ch = curl_init($baseUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        "Authorization: Bearer $token"
    ));
    $jsonData = json_encode($data);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

    
    curl_setopt($ch, CURLOPT_FAILONERROR, true);

    $response = curl_exec($ch);

    
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        error_log('HalkOde getpos network error: ' . $error_msg); echo json_encode(['error' => 'Taksit servisine ulaşılamadı.']);
        return;
    }

    curl_close($ch);

    $response_data = json_decode($response, true);

    
if (isset($response_data['data'])) {
        $installments = [];
        foreach ($response_data['data'] as $item) {
            $installments[] = [
                'installment_number' => $item['installments_number'],
                'amount' => $item['amount_to_be_paid'],
                'currency' => $item['currency_code']
            ];
        }
        echo json_encode(['installments' => $installments]);
    } else {
        echo json_encode(['error' => 'Taksit bilgisi alınamadı.']);
    }
}

function getToken($app_id, $app_secret)
{
    $baseUrl = "https://testapp.halkode.com.tr/ccpayment/api/token";
    $data = array('app_id' => $app_id, 'app_secret' => $app_secret);
    $jsonData = json_encode($data);
    $ch = curl_init($baseUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    
    curl_setopt($ch, CURLOPT_FAILONERROR, true);

    $response = curl_exec($ch);

    
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        error_log('HalkOde token network error: ' . $error_msg); return json_encode(['error' => 'Token servisine ulaşılamadı.']);
    }

    curl_close($ch);
    return $response;
}
?>