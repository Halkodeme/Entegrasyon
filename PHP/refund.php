<?php
require_once __DIR__ . '/degisken.php';
 require_once __DIR__ . '/security.php'; halkode_require_sensitive_action();
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>İade Entegrasyonu</title>

    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <?php include 'style.php'; ?>

    <style>
        body {
            background: #f5f7fb;
        }

        .page-header {
            margin-top: 30px;
            margin-bottom: 25px;
        }

        .page-title {
            font-size: 30px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 5px;
        }

        .page-subtitle {
            color: #6c757d;
            margin-bottom: 0;
        }

        .panel-card {
            background: #ffffff;
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            margin-bottom: 25px;
            overflow: hidden;
        }

        .panel-header {
            padding: 18px 22px;
            border-bottom: 1px solid #edf0f3;
            display: flex;
            align-items: center;
        }

        .panel-header-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: #fff1f1;
            color: #b42318;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-right: 10px;
        }

        .panel-header h5 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
        }

        .panel-body {
            padding: 24px;
        }

        label {
            font-size: 14px;
            font-weight: 600;
            color: #343a40;
        }

        .form-control {
            height: 46px;
            border-radius: 8px;
            border: 1px solid #dce1e7;
        }

        .form-control:focus {
            border-color: #6c757d;
            box-shadow: none;
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
            color: #6c757d;
            font-weight: 600;
        }

        .refund-button {
            height: 46px;
            min-width: 190px;
            padding: 0 24px;
            border: none;
            border-radius: 8px;
            background: #b42318;
            color: #ffffff;
            font-weight: 600;
            transition: all .2s ease;
        }

        .refund-button:hover {
            background: #8f1c13;
            transform: translateY(-1px);
        }

        .info-box {
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 20px;
            background: #fff8e6;
            border: 1px solid #f6d98b;
            color: #7a5a00;
            font-size: 14px;
        }

        .status-box {
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .status-success {
            background: #eaf8ef;
            border: 1px solid #bce8c9;
            color: #217a3c;
        }

        .status-error {
            background: #fff1f1;
            border: 1px solid #efc4c4;
            color: #a12a2a;
        }

        .result-table {
            margin-bottom: 0;
        }

        .result-table thead th {
            border-top: none;
            background: #f7f8fa;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: .4px;
            font-size: 12px;
            white-space: nowrap;
        }

        .result-table td {
            vertical-align: middle;
            font-size: 14px;
        }

        .parameter-name {
            width: 230px;
            font-weight: 600;
        }

        .status-code {
            display: inline-block;
            min-width: 55px;
            text-align: center;
            padding: 5px 9px;
            border-radius: 6px;
            background: #eef2f6;
            font-weight: 700;
        }

        details.debug-box {
            margin-top: 20px;
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            overflow: hidden;
        }

        details.debug-box summary {
            cursor: pointer;
            padding: 14px 18px;
            font-weight: 600;
            background: #f8f9fa;
        }

        .debug-content {
            padding: 15px;
        }

        .debug-content pre {
            margin: 0;
            background: #1f2328;
            color: #e6edf3;
            border-radius: 8px;
            padding: 15px;
            font-size: 12px;
            white-space: pre-wrap;
            word-break: break-word;
        }

        @media(max-width: 768px) {
            .refund-button {
                width: 100%;
            }

            .parameter-name {
                width: auto;
            }

            .page-title {
                font-size: 25px;
            }
        }
    </style>

</head>

<body>

<?php include 'nav.php'; ?>

<div class="container pb-5">

    <div class="page-header">

        <h2 class="page-title">
            İade İşlemi
        </h2>

        <p class="page-subtitle">
            Daha önce gerçekleştirilen bir ödeme için fatura numarası üzerinden iade işlemi başlatın.
        </p>

    </div>


    <div class="panel-card">

        <div class="panel-header">

            <div class="panel-header-icon">
                ↩
            </div>

            <h5>İade Bilgileri</h5>

        </div>

        <div class="panel-body">

            <div class="info-box">
                <strong>Dikkat:</strong>
                Girdiğiniz tutar ilgili işlem üzerinden iade edilmeye çalışılacaktır.
                Fatura numarası ve tutarı kontrol ederek devam edin.
            </div>

            <form method="post">

                <div class="form-row">

                    <div class="form-group col-md-6">

                        <label for="invoiceId">
                            Fatura ID
                        </label>

                        <input
                            type="text"
                            id="invoiceId"
                            name="invoice_id"
                            class="form-control"
                            placeholder="Örn. 20261005-1234"
                            value="<?php
                                echo isset($_POST['invoice_id'])
                                    ? htmlspecialchars($_POST['invoice_id'])
                                    : '';
                            ?>"
                            required>

                    </div>


                    <div class="form-group col-md-6">

                        <label for="amount">
                            İade Tutarı
                        </label>

                        <div class="amount-wrapper">

                            <input
                                type="number"
                                step="0.01"
                                min="0.01"
                                id="amount"
                                name="amount"
                                class="form-control"
                                placeholder="0.00"
                                value="<?php
                                    echo isset($_POST['amount'])
                                        ? htmlspecialchars($_POST['amount'])
                                        : '';
                                ?>"
                                required>

                            <span class="currency-label">
                                TL
                            </span>

                        </div>

                    </div>

                </div>


                <div class="text-right mt-3">

                    <button
                        type="submit"
                        name="process_payment"
                        class="refund-button">

                        İade İşlemini Başlat

                    </button>

                </div>

            </form>

        </div>

    </div>


<?php

if (isset($_POST['process_payment'])) {

    $baseUrl = "https://testapp.halkode.com.tr/ccpayment/api/refund";

    

    $invoice_id = trim($_POST['invoice_id']);
    $amount = trim($_POST['amount']);

    
    $tokenResponse = getToken($app_id, $appSecret);

    $decodedTokenResponse = json_decode($tokenResponse, true);

    if (
        !$decodedTokenResponse ||
        !isset($decodedTokenResponse['status_code']) ||
        $decodedTokenResponse['status_code'] != 100
    ) {

        ?>

        <div class="status-box status-error">

            <strong>Token alınamadı.</strong><br>

            App ID ve App Secret bilgilerini kontrol ediniz.

        </div>

        <?php

    } else {

        $token = $decodedTokenResponse['data']['token'];

        
        $data = [
            "invoice_id" => $invoice_id,
            "merchant_key" => $merchantKey,
            "amount" => $amount
        ];


        
        $ch = curl_init($baseUrl);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "Authorization: Bearer $token"
        ]);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            json_encode(
                $data,
                JSON_UNESCAPED_UNICODE |
                JSON_UNESCAPED_SLASHES
            )
        );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

        $response = curl_exec($ch);

        $curlError = curl_error($ch);

        curl_close($ch);


        if ($curlError) {

            ?>

            <div class="status-box status-error">

                <strong>Bağlantı hatası oluştu.</strong><br>

                <?php error_log('HalkOde refund network error: ' . $curlError); echo 'Bağlantı ayrıntıları güvenlik nedeniyle gizlendi.'; ?>

            </div>

            <?php

        } else {

            $decodedResponse = json_decode($response, true);

            $statusCode =
                $decodedResponse['status_code']
                ?? null;

            $isSuccess =
                $statusCode == 100;


            if ($isSuccess) {

                ?>

                <div class="status-box status-success">

                    <strong>İade işlemi başarılı.</strong><br>

                    İşlem banka tarafından başarıyla işlendi.

                </div>

                <?php

            } else {

                ?>

                <div class="status-box status-error">

                    <strong>İade işlemi tamamlanamadı.</strong><br>

                    <?php
                    echo htmlspecialchars(
                        $decodedResponse['status_description']
                        ?? 'API tarafından işlem reddedildi.'
                    );
                    ?>

                </div>

                <?php

            }


            
            ?>

            <div class="panel-card">

                <div class="panel-header">
                    <h5>Gönderilen Veriler</h5>
                </div>

                <div class="panel-body p-0">

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
                                    Fatura ID
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars($invoice_id);
                                    ?>
                                </td>

                            </tr>


                            <tr>

                                <td class="parameter-name">
                                    İade Tutarı
                                </td>

                                <td>

                                    <strong>

                                        <?php
                                        echo number_format(
                                            (float)$amount,
                                            2,
                                            ',',
                                            '.'
                                        );
                                        ?>

                                        TL

                                    </strong>

                                </td>

                            </tr>


                            <tr>

                                <td class="parameter-name">
                                    Merchant Key
                                </td>

                                <td>

                                    <?php

                                    if (!empty($merchantKey)) {

                                        $maskedMerchantKey =
                                            substr($merchantKey, 0, 6)
                                            . str_repeat(
                                                '*',
                                                max(strlen($merchantKey) - 10, 4)
                                            )
                                            . substr($merchantKey, -4);

                                        echo htmlspecialchars(
                                            $maskedMerchantKey
                                        );

                                    } else {

                                        echo '-';

                                    }

                                    ?>

                                </td>

                            </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            <?php

            
            ?>

            <div class="panel-card">

                <div class="panel-header">
                    <h5>İade Sonucu</h5>
                </div>

                <div class="panel-body p-0">

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
                                    Durum Kodu
                                </td>

                                <td>

                                    <span class="status-code">

                                        <?php
                                        echo htmlspecialchars(
                                            $decodedResponse['status_code']
                                            ?? '-'
                                        );
                                        ?>

                                    </span>

                                </td>

                            </tr>


                            <tr>

                                <td class="parameter-name">
                                    Durum Açıklaması
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $decodedResponse['status_description']
                                        ?? '-'
                                    );
                                    ?>
                                </td>

                            </tr>


                            <tr>

                                <td class="parameter-name">
                                    Sipariş Numarası
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $decodedResponse['order_no']
                                        ?? '-'
                                    );
                                    ?>
                                </td>

                            </tr>


                            <tr>

                                <td class="parameter-name">
                                    Fatura ID
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $decodedResponse['invoice_id']
                                        ?? '-'
                                    );
                                    ?>
                                </td>

                            </tr>


                            <tr>

                                <td class="parameter-name">
                                    Referans No
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $decodedResponse['ref_no']
                                        ?? '-'
                                    );
                                    ?>
                                </td>

                            </tr>


                            <tr>

                                <td class="parameter-name">
                                    Referans Numarası
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $decodedResponse['ref_number']
                                        ?? '-'
                                    );
                                    ?>
                                </td>

                            </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            <details class="debug-box">

                <summary>
                    Teknik API Yanıtını Göster
                </summary>

                <div class="debug-content">

                    <pre><?php

                        echo htmlspecialchars(
                            json_encode(
                                $decodedResponse,
                                JSON_PRETTY_PRINT |
                                JSON_UNESCAPED_UNICODE |
                                JSON_UNESCAPED_SLASHES
                            )
                        );

                    ?></pre>

                </div>

            </details>

            <?php
        }
    }
}



function getToken($app_id, $app_secret)
{
    $baseUrl =
        "https://testapp.halkode.com.tr/ccpayment/api/token";

    $data = [
        'app_id' => $app_id,
        'app_secret' => $app_secret
    ];

    $jsonData = json_encode(
        $data,
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES
    );

    $ch = curl_init($baseUrl);

    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt(
        $ch,
        CURLOPT_POSTFIELDS,
        $jsonData
    );

    curl_setopt(
        $ch,
        CURLOPT_RETURNTRANSFER,
        true
    );

    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        [
            'Content-Type: application/json'
        ]
    );

    $response = curl_exec($ch);

    curl_close($ch);

    return $response;
}

?>

</div>

<?php include 'footer.php'; ?>

</body>

</html>