<?php
require_once __DIR__ . '/degisken.php';
 require_once __DIR__ . '/security.php'; halkode_require_sensitive_action();
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taksit Sayısı</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <?php include 'style.php'; ?>
</head>

<body>

<?php include 'nav.php'; ?>

    <div class="container mt-4">
        <h2 class="">Üye iş Yeri Desteklenen Taksit Sayısı</h2>

        
        <form method="post">

            <div class="form-group">
                <label for="invoice_id" class="">Fatura ID:</label>
                <input type="text" id="invoice_id" name="invoice_id" class="form-control" value="" required>
            </div>

            <button type="submit" name="process_payment" class="btns">Ödemeyi Test Et</button>
        </form>
    </div>

    <?php
    if (isset($_POST['process_payment'])) {
        
   
        $baseUrl = "https://testapp.halkode.com.tr/ccpayment/api/installments";
        
        $invoice_id = $_POST['invoice_id'];

        
        $tokenResponse = getToken($app_id, $appSecret);
        $decodedTokenResponse = json_decode($tokenResponse, true);

        if ($decodedTokenResponse['status_code'] == 100) {
            $token = $decodedTokenResponse['data']['token'];
        } else {
            echo "<div class='alert alert-danger'><strong>Hata:</strong> Token alınamadı. Lütfen bilgilerinizi kontrol ediniz.</div>";
            return;
        }

        
        $data = array(
            "invoice_id" => $invoice_id,
            "merchant_key" => $merchantKey
        );

        
        echo "<h3>Gönderilen Veriler</h3>";
        echo "<pre>" . htmlspecialchars(json_encode(halkode_safe_display_payload($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . "</pre>";

        
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

        
        echo "<h3>Yanıt</h3><pre>" . htmlspecialchars($response) . "</pre>";
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
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    ?>

    </div>

    <?php include 'footer.php'; ?>

</body>

</html>