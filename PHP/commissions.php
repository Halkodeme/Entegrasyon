<?php
require_once __DIR__ . '/degisken.php';
 require_once __DIR__ . '/security.php'; halkode_require_sensitive_action();
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Komisyon Tablosu</title>

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
            justify-content: space-between;
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

        .readonly-input {
            background: #f7f8fa !important;
            color: #6c757d;
        }

        .action-button {
            height: 46px;
            padding: 0 24px;
            border-radius: 8px;
            border: none;
            background: #212529;
            color: #ffffff;
            font-weight: 600;
            transition: all .2s ease;
        }

        .action-button:hover {
            background: #000000;
            transform: translateY(-1px);
        }

        .status-box {
            padding: 14px 18px;
            border-radius: 10px;
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

        .commission-card {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #edf0f3;
            overflow: hidden;
            height: 100%;
            box-shadow: 0 3px 14px rgba(0, 0, 0, 0.04);
        }

        .commission-card-header {
            padding: 16px 18px;
            background: #fafbfc;
            border-bottom: 1px solid #edf0f3;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .commission-card-title {
            font-weight: 700;
            font-size: 17px;
            margin: 0;
            color: #212529;
        }

        .commission-badge {
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 50px;
            background: #eceff3;
            color: #495057;
            font-weight: 600;
        }

        .commission-table {
            margin-bottom: 0;
        }

        .commission-table thead th {
            border-top: none;
            border-bottom: 1px solid #e9ecef;
            background: #ffffff;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: #6c757d;
            white-space: nowrap;
        }

        .commission-table td {
            font-size: 14px;
            vertical-align: middle;
        }

        .rate-badge {
            display: inline-block;
            min-width: 70px;
            text-align: center;
            padding: 5px 9px;
            border-radius: 6px;
            background: #eef2f6;
            font-weight: 700;
            color: #343a40;
        }

        .installment-badge {
            display: inline-block;
            min-width: 38px;
            text-align: center;
            padding: 4px 8px;
            border-radius: 6px;
            background: #f7f8fa;
            border: 1px solid #e2e6ea;
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
            .action-button {
                width: 100%;
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
        <h2 class="page-title">Mevcut Komisyonlar</h2>

        <p class="page-subtitle">
            Kart programlarına göre tanımlı taksit ve komisyon oranlarını görüntüleyin.
        </p>
    </div>

    <div class="panel-card">

        <div class="panel-header">
            <h5>Komisyon Sorgulama</h5>
        </div>

        <div class="panel-body">

            <form method="post">

                <div class="form-row align-items-end">

                    <div class="form-group col-md-8 mb-md-0">

                        <label for="currency_code">
                            Para Birimi
                        </label>

                        <input
                            type="text"
                            class="form-control readonly-input"
                            id="currency_code"
                            name="currency_code"
                            value="TRY"
                            readonly>

                    </div>

                    <div class="form-group col-md-4 mb-0">

                        <button
                            type="submit"
                            name="process_payment"
                            class="action-button w-100">

                            Mevcut Komisyonu Göster

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <?php

    if (isset($_POST['process_payment'])) {

        $baseUrl = "https://testapp.halkode.com.tr/ccpayment/api/commissions";

        

        $currency_code = "TRY";

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
                "currency_code" => $currency_code
            ];

            $jsonData = json_encode(
                $data,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );

            $ch = curl_init($baseUrl);

            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                "Authorization: Bearer $token"
            ]);

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
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
                    <?php error_log('HalkOde commissions network error: ' . $curlError); echo 'Bağlantı ayrıntıları güvenlik nedeniyle gizlendi.'; ?>
                </div>

                <?php

            } else {

                $decodedResponse = json_decode($response, true);

                if (
                    $decodedResponse &&
                    isset($decodedResponse['data']) &&
                    is_array($decodedResponse['data'])
                ) {

                    $groupedData = [];

                    foreach ($decodedResponse['data'] as $items) {

                        if (!is_array($items)) {
                            continue;
                        }

                        foreach ($items as $item) {

                            if (!is_array($item)) {
                                continue;
                            }

                            $cardProgram = $item['card_program'] ?? 'Diğer';

                            $groupedData[$cardProgram][] = $item;
                        }
                    }

                    ?>

                    <div class="status-box status-success">
                        <strong>Komisyon bilgileri başarıyla alındı.</strong><br>
                        <?php echo count($groupedData); ?> kart programı bulundu.
                    </div>


                    <div class="row">

                        <?php foreach ($groupedData as $cardProgram => $items) { ?>

                            <div class="col-lg-6 mb-4">

                                <div class="commission-card">

                                    <div class="commission-card-header">

                                        <h5 class="commission-card-title">
                                            <?php echo htmlspecialchars($cardProgram); ?>
                                        </h5>

                                        <span class="commission-badge">
                                            <?php echo count($items); ?> seçenek
                                        </span>

                                    </div>

                                    <div class="table-responsive">

                                        <table class="table commission-table">

                                            <thead>
                                            <tr>
                                                <th>Başlık</th>
                                                <th>Para Birimi</th>
                                                <th>Taksit</th>
                                                <th>Komisyon</th>
                                            </tr>
                                            </thead>

                                            <tbody>

                                            <?php foreach ($items as $item) { ?>

                                                <tr>

                                                    <td>
                                                        <?php
                                                        echo htmlspecialchars(
                                                            $item['title'] ?? '-'
                                                        );
                                                        ?>
                                                    </td>

                                                    <td>
                                                        <?php
                                                        echo htmlspecialchars(
                                                            $item['currency_code'] ?? '-'
                                                        );
                                                        ?>
                                                    </td>

                                                    <td>

                                                        <span class="installment-badge">

                                                            <?php
                                                            $installment = $item['installment'] ?? '-';

                                                            echo htmlspecialchars(
                                                                (string)$installment
                                                            );
                                                            ?>

                                                        </span>

                                                    </td>

                                                    <td>

                                                        <span class="rate-badge">

                                                            <?php

                                                            $commission =
                                                                $item['merchant_commission_percentage']
                                                                ?? null;

                                                            if (
                                                                $commission !== null &&
                                                                is_numeric($commission)
                                                            ) {

                                                                echo '%' .
                                                                    number_format(
                                                                        (float)$commission,
                                                                        2,
                                                                        ',',
                                                                        '.'
                                                                    );

                                                            } else {

                                                                echo '-';

                                                            }

                                                            ?>

                                                        </span>

                                                    </td>

                                                </tr>

                                            <?php } ?>

                                            </tbody>

                                        </table>

                                    </div>

                                </div>

                            </div>

                        <?php } ?>

                    </div>


                    <details class="debug-box">

                        <summary>
                            Teknik API Detaylarını Göster
                        </summary>

                        <div class="debug-content">

                            <p class="mb-2">
                                <strong>Gönderilen Veri</strong>
                            </p>

                            <pre><?php
                                echo htmlspecialchars(
                                    json_encode(
                                        $data,
                                        JSON_PRETTY_PRINT |
                                        JSON_UNESCAPED_UNICODE |
                                        JSON_UNESCAPED_SLASHES
                                    )
                                );
                            ?></pre>

                            <p class="mt-4 mb-2">
                                <strong>API Yanıtı</strong>
                            </p>

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

                } else {

                    ?>

                    <div class="status-box status-error">
                        <strong>Komisyon bilgisi alınamadı.</strong><br>
                        API yanıtında geçerli bir veri bulunamadı.
                    </div>

                    <details class="debug-box">

                        <summary>
                            Ham API Yanıtını Göster
                        </summary>

                        <div class="debug-content">
                            <pre><?php echo htmlspecialchars($response); ?></pre>
                        </div>

                    </details>

                    <?php
                }
            }
        }
    }


    function getToken($app_id, $app_secret)
    {
        $baseUrl = "https://testapp.halkode.com.tr/ccpayment/api/token";

        $data = [
            'app_id' => $app_id,
            'app_secret' => $app_secret
        ];

        $jsonData = json_encode(
            $data,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );

        $ch = curl_init($baseUrl);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

    ?>

</div>

<?php include 'footer.php'; ?>

</body>

</html>