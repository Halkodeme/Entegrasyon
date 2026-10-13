<?php
require_once __DIR__ . '/degisken.php';
 require_once __DIR__ . '/security.php';  halkode_require_sensitive_action();
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>2D Ödeme İşleme Sorgulama</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <?php include 'style.php'; ?>
</head>

<body></body>
<?php include 'nav.php'; ?>

    <div class="container">
        <h2 class="mt-5">Tarih Görme Entegrasyonu</h2>

        
        <form method="post">
            <label for="invoiceId">Fatura ID:</label>
            <input type="text" id="invoiceId" name="invoice_id" value="" required><br><br>
<label for="credit_card">Kredi Kartı Numarası:</label>
            <input type="text" id="credit_card" name="credit_card" value="" required><br><br>

            <label for="amount">Miktar:</label>
            <input type="number" id="amount" name="amount" value="100.00" step="" required><br><br>

            <label for="currencyCode">KOD:</label>
            <input type="text" id="currencyCode" name="currency_code" value="" required><br><br>

            <button type="submit" name="process_payment" class="btns">Ödemeyi Test Et</button>
        </form>
    </div>

    <?php
    if (isset($_POST['process_payment'])) {
        
        $baseUrl = "https://testapp.halkode.com.tr/ccpayment/api/getTransactions";
        
        $invoice_id = $_POST['invoice_id'];
        $credit_card = $_POST['credit_card'];
        $amount = $_POST['amount'];
        $currency_code = $_POST['currency_code'];
        $date = date('Y-m-d');  

        
        $tokenResponse = getToken($app_id, $appSecret);
        $decodedTokenResponse = json_decode($tokenResponse, true);

        if ($decodedTokenResponse['status_code'] == 100) {
            $token = $decodedTokenResponse['data']['token'];
        } else {
            echo "<p><strong>Hata:</strong> Token alınamadı. Lütfen bilgilerinizi kontrol ediniz.</p>";
            return;
        }

        
        $data = array(
            "merchant_key" => $merchant_key,
            "date" => $date,
            "invoiceid" => $invoice_id,
            "credit_card" => $credit_card,
            "amount" => $amount,
            "currency_code" => $currency_code,
        );

        
        $displayData = halkode_safe_display_payload($data);
        if (!empty($displayData['credit_card'])) {
            $digits = preg_replace('/\D+/', '', (string)$displayData['credit_card']);
            $displayData['credit_card'] = strlen($digits) >= 10
                ? substr($digits, 0, 6) . str_repeat('*', max(strlen($digits) - 10, 0)) . substr($digits, -4)
                : '******';
        }
        echo "<h3>Gönderilen Veriler</h3>";
        echo "<pre>" . htmlspecialchars(json_encode($displayData, JSON_PRETTY_PRINT)) . "</pre>";

        
        $ch = curl_init($baseUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            "Authorization: Bearer $token"
        ));
        $jsonData = json_encode($data);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        $response = curl_exec($ch);
        curl_close($ch);

        
        echo "<h3>Yanıt</h3><pre>" . htmlspecialchars($response) . "</pre>";

        
        echo "<h3>Güncel Tarih</h3><p>$date</p>";  
    }

    function generateHashKey($total, $installment, $currency_code, $merchant_key, $invoice_id, $app_secret)
    {
        $data = $total . '|' . $installment . '|' . $currency_code . '|' . $merchant_key . '|' . $invoice_id;
        $iv = bin2hex(random_bytes(8));
        $password = sha1($app_secret);
        $salt = bin2hex(random_bytes(2));
        $saltWithPassword = hash('sha256', $password . $salt);
        $encrypted = openssl_encrypt("$data", 'aes-256-cbc', "$saltWithPassword", 0, $iv);
        $msg_encrypted_bundle = "$iv:$salt:$encrypted";
        $msg_encrypted_bundle = str_replace('/', '__', $msg_encrypted_bundle);
        return $msg_encrypted_bundle;
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

    <?php include 'footer.php'; ?>

</body>

</html>