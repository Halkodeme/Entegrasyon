<?php
require_once __DIR__ . '/degisken.php';
 require_once __DIR__ . '/security.php'; halkode_require_sensitive_action();
?>
<?php

function getToken($app_id, $app_secret) {
    $baseUrl = "https://testapp.halkode.com.tr/ccpayment/api/token";
    $data = array('app_id' => $app_id, 'app_secret' => $app_secret);
    $jsonData = json_encode($data);
    $ch = curl_init($baseUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}


function generateConfrimPaymentHashKey($merchant_key, $invoice_id, $status, $app_secret)
{
    $data = $merchant_key . '|' . $invoice_id . '|' . $status;

    
    $iv = bin2hex(random_bytes(8)); 
    $password = sha1($app_secret);

    
    $salt = bin2hex(random_bytes(2));
    $saltWithPassword = hash('sha256', $password . $salt);

    
    
    $encrypted = openssl_encrypt("$data", 'aes-256-cbc', "$saltWithPassword", 0, $iv);

    
    $msg_encrypted_bundle = "$iv:$salt:$encrypted";

    
    $msg_encrypted_bundle = str_replace('/', '__', $msg_encrypted_bundle);

    
    return $msg_encrypted_bundle;
}

if (isset($_POST['process_payment'])) {
    
    $baseUrl = "https://testapp.halkode.com.tr/ccpayment/api/confirmPayment";
   

    
    $tokenResponse = getToken($app_id, $appSecret);
    $decodedTokenResponse = json_decode($tokenResponse, true);

    if ($decodedTokenResponse['status_code'] == 100) {
        $token = $decodedTokenResponse['data']['token'];
    } else {
        echo "<p><strong>Hata:</strong> Token alınamadı. Lütfen bilgilerinizi kontrol ediniz.</p>";
        return;
    }

    
    $invoice_id = trim((string)($_POST['invoice_id'] ?? ''));
    $total = (float)($_POST['total'] ?? 0);
    $status = "1";  

    
    $hash_key = generateConfrimPaymentHashKey($merchantKey, $invoice_id, $status, $appSecret);

    
    $data = array(
        "merchant_key" => $merchantKey,
        "invoice_id" => $invoice_id,
        "total" => $total,
        "status" => $status,
        "hash_key" => $hash_key 
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
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    $response = curl_exec($ch);
    curl_close($ch);

    
    $responseData = json_decode($response, true);
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Doğrulama</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <?php include 'style.php'; ?>
</head>
<body>
<?php include 'nav.php'; ?>

<div class="container mt-5">
    <h2>Ödeme Tamamlama İşlemi</h2>

    
    <form method="post">
        <div class="form-group">
            <label for="invoice_id" class="mt-2">Fatura ID:</label>
            <input type="text" class="form-control" id="invoice_id" name="invoice_id" required>
        </div>
    
        <div class="form-group">
            <label for="total">Tutar:</label>
            <input type="text" class="form-control" id="total" name="total" required>
        </div>
        <button type="submit" name="process_payment" class="btn btn-primary">Ödemeyi Gönder</button>
    </form>

    
    <?php if (isset($responseData)) { ?>
        <div class="result mt-4">
            <h3>Yanıt</h3>
            <pre><?php echo htmlspecialchars(json_encode($responseData, JSON_PRETTY_PRINT)); ?></pre>
        </div>
    <?php } ?>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
