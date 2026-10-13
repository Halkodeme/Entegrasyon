<?php
require_once __DIR__ . '/degisken.php';
 require_once __DIR__ . '/security.php';  halkode_require_sensitive_action();
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Link ile Ödeme Entegrasyonu</title>

    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <?php include 'style.php'; ?>

    <style>
        body {
            background: #f5f7fb;
        }

        .page-title {
            font-weight: 700;
            color: #212529;
            margin-bottom: 5px;
        }

        .page-subtitle {
            color: #6c757d;
            margin-bottom: 25px;
        }

        .payment-card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            background: #fff;
            margin-bottom: 25px;
        }

        .payment-card-header {
            padding: 18px 22px;
            border-bottom: 1px solid #eceff3;
            background: #ffffff;
        }

        .payment-card-header h5 {
            margin: 0;
            font-weight: 700;
            font-size: 18px;
        }

        .payment-card-body {
            padding: 25px;
        }

        .form-control {
            height: 46px;
            border-radius: 8px;
            border: 1px solid #dce1e7;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #6c757d;
        }

        label {
            font-size: 14px;
            font-weight: 600;
            color: #343a40;
        }

        .readonly-input {
            background: #f7f8fa !important;
            color: #6c757d;
        }

        .payment-button {
            min-width: 180px;
            height: 46px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            padding: 0 25px;
            background: #212529;
            color: white;
            transition: all .2s ease;
        }

        .payment-button:hover {
            background: #000;
            transform: translateY(-1px);
        }

        .result-table {
            margin-bottom: 0;
        }

        .result-table thead th {
            border-top: none;
            background: #f7f8fa;
            color: #495057;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .result-table td {
            vertical-align: middle;
            font-size: 14px;
        }

        .parameter-name {
            font-weight: 600;
            width: 240px;
        }

        .payment-link {
            display: inline-block;
            padding: 8px 14px;
            background: #212529;
            color: #fff !important;
            border-radius: 6px;
            font-size: 13px;
            text-decoration: none !important;
        }

        .payment-link:hover {
            background: #000;
        }

        .status-box {
            border-radius: 10px;
            padding: 15px 18px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .status-success {
            background: #eaf8ef;
            border: 1px solid #bce8c9;
            color: #217a3c;
        }

        .status-error {
            background: #fff0f0;
            border: 1px solid #f2c2c2;
            color: #a72a2a;
        }

        .amount-wrapper {
            position: relative;
        }

        .amount-wrapper input {
            padding-right: 50px;
        }

        .currency-label {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: 600;
            color: #6c757d;
        }

        .section-icon {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            background: #f0f1f3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
        }

        @media(max-width: 768px) {
            .payment-card-body {
                padding: 18px;
            }

            .parameter-name {
                width: auto;
            }

            .payment-button {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<?php include 'nav.php'; ?>

<div class="container mt-4 mb-5">

    <h2 class="page-title">Link ile Ödeme</h2>

    <p class="page-subtitle">
        Ödeme linki oluşturmak için müşteri ve işlem bilgilerini giriniz.
    </p>

    <?php

    $invoice_id = halkode_random_invoice_id('LINK');

    $responseData = null;
    $requestCompleted = false;
    $merchantKey = null;
    $currency_code = "TRY";
    $name = "";
    $surname = "";
    $total = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $apiUrl = "https://testapp.halkode.com.tr/ccpayment/purchase/link";

        

        $invoice_id = $_POST['invoice_id'];
        $currency_code = "TRY";

        $name = trim($_POST['name']);
        $surname = trim($_POST['surname']);
        $total = trim($_POST['total']);

        $invoiceData = [
            "invoice_id" => $invoice_id,
            "invoice_description" => "Testdescription",
            "total" => (float)$total,
            "return_url" => "https://google.com.tr",
            "cancel_url" => "https://github.com.tr",
            "items" => [
                [
                    "name" => "Item1",
                    "price" => (float)$total,
                    "quantity" => 1,
                    "description" => "Test"
                ]
            ]
        ];

        $requestData = [
            "merchant_key" => $merchantKey,
            "currency_code" => $currency_code,
            "invoice" => json_encode(
                $invoiceData,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ),
            "name" => $name,
            "surname" => $surname
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            http_build_query($requestData)
        );

        $response = curl_exec($ch);

        if (curl_errno($ch)) {

            error_log("HalkOde purchase link network error: " . curl_error($ch));

            $responseData = [
                "status" => false,
                "error" => "HalkÖde servisine bağlantı kurulamadı."
            ];

        } else {

            $responseData = json_decode($response, true);

            if (!is_array($responseData)) {
                $responseData = [
                    "status" => false,
                    "error" => "API cevabı doğrulanamadı."
                ];
            }
        }

        curl_close($ch);

        $requestCompleted = true;
    }

    ?>


    
    <div class="payment-card">

        <div class="payment-card-header d-flex align-items-center">

            <div class="section-icon">
                ₺
            </div>

            <div>
                <h5>Ödeme Bilgileri</h5>
            </div>

        </div>

        <div class="payment-card-body">

            <form method="post">

                <div class="form-row">

                    <div class="form-group col-md-6">

                        <label for="invoice_id">
                            Fatura Numarası
                        </label>

                        <input
                            type="text"
                            class="form-control readonly-input"
                            id="invoice_id"
                            name="invoice_id"
                            value="<?php echo htmlspecialchars($invoice_id); ?>"
                            readonly>

                    </div>


                    <div class="form-group col-md-6">

                        <label for="total">
                            Tutar
                        </label>

                        <div class="amount-wrapper">

                            <input
                                type="number"
                                step="0.01"
                                min="0.01"
                                id="total"
                                name="total"
                                class="form-control"
                                placeholder="0.00"
                                value="<?php echo htmlspecialchars($total); ?>"
                                required>

                            <span class="currency-label">
                                TL
                            </span>

                        </div>

                    </div>


                    <div class="form-group col-md-6">

                        <label for="name">
                            Kart Sahibi Adı
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control"
                            placeholder="İsim"
                            value="<?php echo htmlspecialchars($name); ?>"
                            required>

                    </div>


                    <div class="form-group col-md-6">

                        <label for="surname">
                            Kart Sahibi Soyadı
                        </label>

                        <input
                            type="text"
                            id="surname"
                            name="surname"
                            class="form-control"
                            placeholder="soyisim"
                            value="<?php echo htmlspecialchars($surname); ?>"
                            required>

                    </div>

                </div>


                <div class="text-right mt-3">

                    <button
                        type="submit"
                        name="process_payment"
                        class="payment-button">

                        Ödeme Linki Oluştur

                    </button>

                </div>

            </form>

        </div>

    </div>


    <?php if ($requestCompleted && is_array($responseData)) { ?>


        <?php

        $isSuccess = false;

        if (
            (isset($responseData['status']) &&
                (
                    $responseData['status'] === true ||
                    $responseData['status'] === 1 ||
                    $responseData['status'] === "1" ||
                    strtolower((string)$responseData['status']) === "success"
                ))
            ||
            isset($responseData['payment_link'])
            ||
            isset($responseData['link'])
            ||
            isset($responseData['url'])
        ) {
            $isSuccess = true;
        }

        ?>


        
        <?php if ($isSuccess) { ?>

            <div class="status-box status-success">
                <strong>✓ İşlem Başarılı</strong><br>
                Ödeme linki başarıyla oluşturuldu.
            </div>

        <?php } else { ?>

            <div class="status-box status-error">
                <strong>İşlem Sonucu</strong><br>
                API isteğini aşağıdaki cevap üzerinden kontrol edebilirsiniz.
            </div>

        <?php } ?>


        
        <div class="payment-card">

            <div class="payment-card-header">
                <h5>Gönderilen Parametreler</h5>
            </div>

            <div class="payment-card-body p-0">

                <div class="table-responsive">

                    <table class="table result-table">

                        <thead>
                        <tr>
                            <th>Parametre</th>
                            <th>Değer</th>
                        </tr>
                        </thead>

                        <tbody>

                        <tr>
                            <td class="parameter-name">
                                Merchant Key
                            </td>

                            <td>
                                <?php

                                if (!empty($merchantKey)) {

                                    $maskedMerchantKey =
                                        substr($merchantKey, 0, 6)
                                        . str_repeat('*', max(strlen($merchantKey) - 10, 4))
                                        . substr($merchantKey, -4);

                                    echo htmlspecialchars($maskedMerchantKey);

                                } else {

                                    echo '-';

                                }

                                ?>
                            </td>
                        </tr>


                        <tr>
                            <td class="parameter-name">
                                Fatura ID
                            </td>

                            <td>
                                <?php echo htmlspecialchars($invoice_id); ?>
                            </td>
                        </tr>


                        <tr>
                            <td class="parameter-name">
                                Tutar
                            </td>

                            <td>
                                <strong>
                                    <?php
                                    echo number_format((float)$total, 2, ',', '.');
                                    ?>
                                    TL
                                </strong>
                            </td>
                        </tr>


                        <tr>
                            <td class="parameter-name">
                                Para Birimi
                            </td>

                            <td>
                                <?php echo htmlspecialchars($currency_code); ?>
                            </td>
                        </tr>


                        <tr>
                            <td class="parameter-name">
                                Ad
                            </td>

                            <td>
                                <?php echo htmlspecialchars($name); ?>
                            </td>
                        </tr>


                        <tr>
                            <td class="parameter-name">
                                Soyad
                            </td>

                            <td>
                                <?php echo htmlspecialchars($surname); ?>
                            </td>
                        </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        
        <div class="payment-card">

            <div class="payment-card-header">
                <h5>API Cevabı</h5>
            </div>

            <div class="payment-card-body p-0">

                <div class="table-responsive">

                    <table class="table result-table">

                        <thead>
                        <tr>
                            <th>Parametre</th>
                            <th>Değer</th>
                        </tr>
                        </thead>

                        <tbody>

                        <?php

                        foreach ($responseData as $key => $value) {

                            
                            if (
                                $key === 'status' ||
                                $key === 'status_code' ||
                                $key === 'success_message'
                            ) {
                                continue;
                            }

                            ?>

                            <tr>

                                <td class="parameter-name">

                                    <?php
                                    echo htmlspecialchars(
                                        ucwords(str_replace('_', ' ', $key))
                                    );
                                    ?>

                                </td>

                                <td>

                                    <?php

                                    if (is_array($value)) {

                                        echo '<pre class="mb-0">';
                                        echo htmlspecialchars(
                                            json_encode(
                                                $value,
                                                JSON_PRETTY_PRINT |
                                                JSON_UNESCAPED_UNICODE |
                                                JSON_UNESCAPED_SLASHES
                                            )
                                        );
                                        echo '</pre>';

                                    }
                                    elseif (
                                        is_string($value) &&
                                        filter_var($value, FILTER_VALIDATE_URL)
                                    ) {

                                        ?>

                                        <a
                                            href="<?php echo htmlspecialchars($value); ?>"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="payment-link">

                                            Ödeme Sayfasını Aç ↗

                                        </a>

                                        <?php

                                    }
                                    else {

                                        echo htmlspecialchars(
                                            (string)$value
                                        );

                                    }

                                    ?>

                                </td>

                            </tr>

                            <?php
                        }

                        ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    <?php } ?>

</div>


<?php include 'footer.php'; ?>

</body>

</html>