<?php
require_once __DIR__ . '/degisken.php';
 require_once __DIR__ . '/security.php';  halkode_require_sensitive_action();
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Ödeme Tamamlama</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <?php include 'style.php'; require_once __DIR__ . '/api_security.php'; ?>
</head>

<body>
<?php include 'nav.php'; ?>

    <div class="container">
        <h2 class="mt-5">Ödeme Tamamlama</h2>

        
        <form method="post">
<label for="invoice_id">Fatura ID:</label>
            <input type="text" id="invoice_id" name="invoice_id" value="" required><br><br>

            <label for="order_id">İşlem Numarası:</label>
            <input type="text" id="order_id" name="order_id" value="" required><br><br>

            <button type="submit" name="process_payment" class="btns">Ödemeyi Test Et</button>
        </form>
    </div>

    <?php
    if (isset($_POST['process_payment'])) {
        
        $baseUrl = "https://testapp.halkode.com.tr/ccpayment/api/complete";
        
        $order_id = $_POST['order_id']; 
        $invoice_id = $_POST['invoice_id']; 
        $status = "complete";

        
        $tokenResponse = getToken($app_id, $appSecret);
        $decodedTokenResponse = json_decode($tokenResponse, true);

        
        if ($decodedTokenResponse === null || $decodedTokenResponse['status_code'] != 100) {
            echo "<p><strong>Hata:</strong> Token alınamadı. Lütfen bilgilerinizi kontrol ediniz.</p>";
            return;
        }
        $token = $decodedTokenResponse['data']['token'];

        
        $hash_key = halkode_complete_hash($merchantKey, $invoice_id, $order_id, $status, $appSecret);
        if ($hash_key === '') {
            echo "<p><strong>Hata:</strong> Güvenlik imzası üretilemedi.</p>";
            return;
        }

        $data = array(
            "invoice_id" => $invoice_id,
            "merchant_key" => $merchantKey,
            "order_id" => $order_id,
            "status" => $status,
            "hash_key" => $hash_key,
        );

        
        echo "<h3>Gönderilen Veriler</h3>";
        echo "<pre>" . htmlspecialchars(http_build_query(halkode_safe_display_payload($data))) . "</pre>";

        
        $ch = curl_init($baseUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Accept: application/json",
            "Authorization: Bearer $token"
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        $response = curl_exec($ch);

        if ($response === false) {
            error_log("HalkOde complete network error: " . curl_error($ch));
            echo '<p><strong>Hata:</strong> HalkÖde servisine bağlantı kurulamadı.</p>';
            curl_close($ch);
            return;
        }
        curl_close($ch);

        
        echo "<h3>API Yanıtı</h3><pre>" . htmlspecialchars($response) . "</pre>";

        
        $responseData = json_decode($response, true);
        if (!is_array($responseData)) {
            parse_str($response, $responseData);
        }
        if (isset($responseData['status_code']) && $responseData['status_code'] == 100) {
            echo "<h3>İşlem Tamamlandı</h3><p>Ödeme işleminiz başarıyla tamamlanmıştır.</p>";
        } else {
            echo "<h3>Hata</h3><p>İşlem sırasında bir hata oluştu. Lütfen tekrar deneyin.</p>";
        }
    }

    function getToken($app_id, $app_secret)
    {
        $baseUrl = "https://testapp.halkode.com.tr/ccpayment/api/token";  
        $data = array('app_id' => $app_id, 'app_secret' => $app_secret);
        $ch = curl_init($baseUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        $response = curl_exec($ch);

        
        if (curl_errno($ch)) {
            error_log('HalkOde token network error: ' . curl_error($ch)); echo 'Token servisine bağlantı kurulamadı.';
            curl_close($ch);
            return null;
        }
        curl_close($ch);
        return $response;
    }
    ?>

    <?php include 'footer.php'; ?>

</body>

</html>