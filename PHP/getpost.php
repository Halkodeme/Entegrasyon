<?php
require_once __DIR__ . '/degisken.php';
 require_once __DIR__ . '/security.php';  halkode_require_sensitive_action();
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kart Taksit Gösterme Entegrasyonu</title>

    <link
        rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <?php include 'style.php'; ?>

    <style>
        html,
        body {
            height: 100%;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #f5f6f8;
        }

        .main-content {
            flex: 1 0 auto;
            width: 100%;
        }

        footer {
            flex-shrink: 0;
        }

        .payment-container {
            max-width: 950px;
            margin: 40px auto;
        }

        .payment-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 30px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }

        .payment-card h2 {
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 25px;
        }

        .form-control {
            height: 48px;
            border-radius: 8px;
        }

        .btn-payment {
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }

        .api-success {
            padding: 15px 20px;
            background: #e8f7ee;
            border-left: 4px solid #28a745;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .api-error {
            padding: 15px 20px;
            background: #fdecec;
            border-left: 4px solid #dc3545;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .installment-table {
            background: #ffffff;
            margin-bottom: 0;
        }

        .installment-table th {
            white-space: nowrap;
            vertical-align: middle !important;
        }

        .installment-table td {
            vertical-align: middle !important;
        }

        .installment-number {
            font-weight: 700;
        }

        .card-program {
            font-weight: 600;
        }

        .card-scheme {
            text-transform: uppercase;
            font-weight: 600;
        }

        .card-number-info {
            margin-top: 12px;
            color: #6c757d;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .payment-container {
                margin-top: 20px;
            }

            .payment-card {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<?php include 'nav.php'; ?>

<div class="main-content">

    <div class="container payment-container">

        <div class="payment-card">

            <h2>
                Kart Taksit Gösterme Entegrasyonu
            </h2>

            <form method="post">

                <div class="form-group">

                    <label for="credit_card">
                        Kredi Kartı Numarası
                    </label>

                    <input
                        type="text"
                        id="credit_card"
                        name="credit_card"
                        placeholder="0000 0000 0000 0000"
                        maxlength="19"
                        class="form-control"
                        value="<?= htmlspecialchars($_POST['credit_card'] ?? '') ?>"
                        required
                        autocomplete="off"
                        inputmode="numeric">

                    <div class="card-number-info">
                        Kart numarasını girerek kullanılabilir taksit seçeneklerini görüntüleyebilirsiniz.
                    </div>

                </div>

                <button
                    type="submit"
                    name="process_payment"
                    class="btn btn-primary btn-payment">

                    Taksitleri Getir

                </button>

            </form>

        </div>


<?php

if (isset($_POST['process_payment'])) {

    

    $baseUrl =
        "https://testapp.halkode.com.tr/ccpayment/api/getpos";


    


    

    $credit_card =
        preg_replace(
            '/\D/',
            '',
            $_POST['credit_card'] ?? ''
        );


    

    $amount = "100";


    $currencyCode = "TRY";


    

    if (
        strlen($credit_card) < 6 ||
        strlen($credit_card) > 19
    ) {

        echo '
        <div class="payment-card">

            <div class="api-error">

                <strong>
                    Geçersiz kart numarası.
                </strong>

                <br>

                Lütfen geçerli bir kart numarası giriniz.

            </div>

        </div>
        ';

    } else {

        

        $tokenResponse =
            getToken(
                $app_id,
                $appSecret
            );


        $decodedTokenResponse =
            json_decode(
                $tokenResponse,
                true
            );


        if (
            !is_array($decodedTokenResponse) ||
            !isset($decodedTokenResponse['status_code']) ||
            $decodedTokenResponse['status_code'] != 100 ||
            empty($decodedTokenResponse['data']['token'])
        ) {

            echo '
            <div class="payment-card">

                <div class="api-error">

                    <strong>
                        Token alınamadı.
                    </strong>

                    <br>

                    Uygulama bilgilerinizi kontrol ediniz.

                </div>

            </div>
            ';

        } else {

            $token =
                $decodedTokenResponse['data']['token'];


            

            $data = array(

                "merchant_key" =>
                    $merchantKey,

                "credit_card" =>
                    substr($credit_card, 0, 6),

                "amount" =>
                    $amount,

                "currency_code" =>
                    $currencyCode

            );


            

            $ch =
                curl_init(
                    $baseUrl
                );


            curl_setopt_array(
                $ch,
                array(

                    CURLOPT_HTTPHEADER =>
                        array(

                            'Content-Type: application/json',

                            "Authorization: Bearer $token"

                        ),

                    CURLOPT_POST =>
                        true,

                    CURLOPT_POSTFIELDS =>
                        json_encode($data),

                    CURLOPT_RETURNTRANSFER =>
                        true,

                    CURLOPT_CONNECTTIMEOUT =>
                        5,

                    CURLOPT_TIMEOUT =>
                        15,

                    CURLOPT_SSL_VERIFYPEER =>
                        true,

                    CURLOPT_SSL_VERIFYHOST =>
                        2,

                    CURLOPT_FOLLOWLOCATION =>
                        false

                )
            );


            $response =
                curl_exec(
                    $ch
                );


            $curlError =
                curl_error(
                    $ch
                );


            curl_close(
                $ch
            );


            

            if ($response === false) {

                echo '
                <div class="payment-card">

                    <div class="api-error">

                        <strong>
                            API bağlantı hatası:
                        </strong>

                        <br>

                        Bağlantı ayrıntıları güvenlik nedeniyle gizlendi.

                    </div>

                </div>
                ';

            } else {

                

                $response_data =
                    json_decode(
                        $response,
                        true
                    );


                

                if (!is_array($response_data)) {

                    echo '
                    <div class="payment-card">

                        <div class="api-error">

                            API geçerli bir JSON yanıtı döndürmedi.

                        </div>

                    </div>
                    ';

                }

                

                elseif (
                    isset($response_data['status_code']) &&
                    $response_data['status_code'] == 100
                ) {

                    ?>

                    <div class="payment-card">

                        <div class="api-success">

                            <strong>
                                Kart bilgileri başarıyla sorgulandı.
                            </strong>

                            <?php if (!empty($response_data['status_description'])): ?>

                                <br>

                                <?= htmlspecialchars(
                                    $response_data['status_description']
                                ) ?>

                            <?php endif; ?>

                        </div>


                        <h4 class="mb-3">
                            Taksit Seçenekleri
                        </h4>


                        <?php

                        if (
                            isset($response_data['data']) &&
                            is_array($response_data['data']) &&
                            count($response_data['data']) > 0
                        ) {

                            ?>

                            <div class="table-responsive">

                                <table
                                    class="table table-bordered table-hover installment-table">

                                    <thead class="thead-light">

                                        <tr>

                                            <th>
                                                Taksit
                                            </th>

                                            <th>
                                                Kart Programı
                                            </th>

                                            <th>
                                                Kart Tipi
                                            </th>

                                            <th>
                                                Kart Şeması
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                    <?php

                                    foreach (
                                        $response_data['data']
                                        as $installment
                                    ) {

                                        

                                        $installmentNumber =
                                            isset(
                                                $installment[
                                                    'installments_number'
                                                ]
                                            )
                                                ? (int)$installment[
                                                    'installments_number'
                                                ]
                                                : 0;


                                        

                                        if ($installmentNumber === 1) {

                                            $installmentText =
                                                'Tek Çekim';

                                        } elseif ($installmentNumber > 1) {

                                            $installmentText =
                                                $installmentNumber .
                                                ' Taksit';

                                        } else {

                                            $installmentText =
                                                '-';

                                        }


                                        

                                        $cardProgram =
                                            $installment[
                                                'card_program'
                                            ]
                                            ?? '-';


                                        

                                        $cardType =
                                            $installment[
                                                'card_type'
                                            ]
                                            ?? '-';


                                        

                                        $cardScheme =
                                            strtoupper(
                                                $installment[
                                                    'card_scheme'
                                                ]
                                                ?? '-'
                                            );

                                        ?>

                                        <tr>

                                            <td class="installment-number">

                                                <?= htmlspecialchars(
                                                    $installmentText
                                                ) ?>

                                            </td>


                                            <td class="card-program">

                                                <?= htmlspecialchars(
                                                    $cardProgram
                                                ) ?>

                                            </td>


                                            <td>

                                                <?= htmlspecialchars(
                                                    $cardType
                                                ) ?>

                                            </td>


                                            <td class="card-scheme">

                                                <?= htmlspecialchars(
                                                    $cardScheme
                                                ) ?>

                                            </td>

                                        </tr>

                                    <?php } ?>

                                    </tbody>

                                </table>

                            </div>


                            <?php

                        } else {

                            ?>

                            <div class="alert alert-warning mb-0">

                                Bu kart için taksit seçeneği bulunamadı.

                            </div>

                            <?php

                        }

                        ?>

                    </div>

                    <?php

                }

                

                else {

                    $statusCode =
                        $response_data[
                            'status_code'
                        ]
                        ?? '-';


                    $description =
                        $response_data[
                            'status_description'
                        ]
                        ?? 'Bilinmeyen API hatası';


                    echo '
                    <div class="payment-card">

                        <div class="api-error">

                            <strong>
                                API Hatası
                            </strong>

                            <br>

                            Kod:
                            ' .
                            htmlspecialchars(
                                $statusCode
                            ) .
                            '

                            <br>

                            Açıklama:
                            ' .
                            htmlspecialchars(
                                $description
                            ) .
                            '

                        </div>

                    </div>
                    ';

                }

            }

        }

    }

}




function getToken(
    $app_id,
    $app_secret
) {

    $baseUrl =
        "https://testapp.halkode.com.tr/ccpayment/api/token";


    $data = array(

        'app_id' =>
            $app_id,

        'app_secret' =>
            $app_secret

    );


    $ch =
        curl_init(
            $baseUrl
        );


    curl_setopt_array(
        $ch,
        array(

            CURLOPT_POST =>
                true,

            CURLOPT_POSTFIELDS =>
                json_encode($data),

            CURLOPT_RETURNTRANSFER =>
                true,

            CURLOPT_HTTPHEADER =>
                array(

                    'Content-Type: application/json'

                ),

            CURLOPT_CONNECTTIMEOUT =>
                5,

            CURLOPT_TIMEOUT =>
                15

        )
    );


    $response =
        curl_exec(
            $ch
        );


    curl_close(
        $ch
    );


    return $response;

}

?>

    </div>

</div>


<?php include 'footer.php'; ?>


<script>
















const creditCardInput =
    document.getElementById(
        'credit_card'
    );


if (creditCardInput) {

    creditCardInput.addEventListener(
        'input',
        function () {

            let value =
                this.value
                    .replace(/\D/g, '')
                    .substring(0, 19);


            let groups =
                value.match(/.{1,4}/g);


            this.value =
                groups
                    ? groups.join(' ')
                    : '';

        }
    );

}

</script>

</body>

</html>
