<?php
require_once __DIR__ . '/degisken.php';
 require_once __DIR__ . '/security.php'; halkode_require_sensitive_action();
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>2D Komisyonlu Ödeme İşlemi</title>

    <link
        rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <?php include 'style.php'; ?>

    <style>
        html,
        body {
            min-height: 100%;
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

        .payment-page {
            max-width: 1350px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .payment-title {
            margin-bottom: 25px;
            font-weight: 600;
        }

        .payment-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(420px, 0.8fr);
            gap: 35px;
            align-items: start;
        }

        .form-card,
        .card-preview-wrapper,
        .response-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, .08);
        }

        .form-card {
            padding: 28px;
        }

        .form-card label {
            font-weight: 500;
        }

        .form-control {
            height: 46px;
            border-radius: 8px;
        }

        select.form-control {
            height: 46px;
        }

        .btn-payment {
            width: 100%;
            border: 0;
            padding: 13px 20px;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 10px;
            cursor: pointer;
        }

        .right-panel {
            position: sticky;
            top: 25px;
        }

        .card-preview-wrapper {
            padding: 30px;
        }

        .preview-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 25px;
        }

        .credit-card-preview {
            width: 100%;
            max-width: 430px;
            aspect-ratio: 1.586 / 1;
            margin: 0 auto;
            border-radius: 22px;
            padding: 28px;
            color: #fff;

            background:
                radial-gradient(
                    circle at top right,
                    rgba(255,255,255,.24),
                    transparent 35%
                ),
                linear-gradient(
                    135deg,
                    #111827,
                    #26344a 55%,
                    #0b1220
                );

            box-shadow: 0 20px 45px rgba(0,0,0,.23);

            position: relative;
            overflow: hidden;
        }

        .credit-card-preview::before {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255,255,255,.05);
            right: -80px;
            bottom: -100px;
        }

        .credit-card-preview::after {
            content: "";
            position: absolute;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.08);
            right: 35px;
            top: -80px;
        }

        .card-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .chip {
            width: 53px;
            height: 39px;
            border-radius: 8px;

            background:
                linear-gradient(
                    135deg,
                    #e5c86b,
                    #fff0a8,
                    #b69236
                );

            position: relative;
        }

        .chip::before,
        .chip::after {
            content: "";
            position: absolute;
            border: 1px solid rgba(80,60,10,.35);
        }

        .chip::before {
            left: 17px;
            top: 0;
            bottom: 0;
        }

        .chip::after {
            left: 0;
            right: 0;
            top: 18px;
        }

        .card-brand {
            font-size: 21px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .card-number-preview {
            margin-top: 45px;
            font-size: 25px;
            letter-spacing: 3px;
            font-weight: 500;
            position: relative;
            z-index: 2;
            white-space: nowrap;
        }

        .card-bottom {
            position: absolute;
            left: 28px;
            right: 28px;
            bottom: 25px;

            display: flex;
            justify-content: space-between;
            align-items: flex-end;

            gap: 20px;
            z-index: 2;
        }

        .card-label {
            display: block;
            font-size: 9px;
            opacity: .7;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }

        .card-holder-preview {
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            max-width: 230px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .card-expiry-preview {
            font-size: 15px;
            white-space: nowrap;
        }

        .payment-summary {
            margin-top: 25px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;

            padding: 9px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .summary-row:last-child {
            border-bottom: 0;
        }

        .summary-label {
            color: #6c757d;
        }

        .summary-value {
            font-weight: 600;
            text-align: right;
        }

        .commission-value {
            color: #dc3545;
        }

        .response-card {
            padding: 25px;
            margin-top: 30px;
        }

        .api-success {
            background: #e8f7ee;
            border-left: 4px solid #28a745;
            border-radius: 6px;
            padding: 15px 20px;
        }

        .api-error {
            background: #fdecec;
            border-left: 4px solid #dc3545;
            border-radius: 6px;
            padding: 15px 20px;
        }

        @media (max-width: 992px) {
            .payment-layout {
                grid-template-columns: 1fr;
            }

            .right-panel {
                position: static;
            }
        }

        @media (max-width: 576px) {
            .payment-page {
                padding: 0 12px;
            }

            .form-card,
            .card-preview-wrapper {
                padding: 18px;
            }

            .credit-card-preview {
                padding: 20px;
            }

            .card-number-preview {
                font-size: 19px;
                letter-spacing: 2px;
                margin-top: 35px;
            }

            .card-bottom {
                left: 20px;
                right: 20px;
                bottom: 18px;
            }
        }
    </style>
</head>

<body>

<?php include 'nav.php'; ?>

<?php



$invoice_id = $_POST['invoice_id'] ?? halkode_random_invoice_id('2D');

$formHolder =
    $_POST['cc_holder_name']
    ?? '';

$formCard =
    $_POST['cc_no']
    ?? '';

$formMonth =
    $_POST['expiry_month']
    ?? '';

$formYear =
    $_POST['expiry_year']
    ?? '';

$formTotal =
    $_POST['total']
    ?? '';

$formTransaction =
    $_POST['transaction_type']
    ?? 'Auth';

$formDescription =
    $_POST['invoice_description']
    ?? '';

?>

<div class="main-content">

    <div class="container-fluid payment-page">

        <h2 class="payment-title">
            2D Komisyonlu Ödeme İşlemi
        </h2>

        <div class="payment-layout">

            

            <div class="form-card">

                <form method="post">

                    <div class="form-group">

                        <label for="invoice_id">
                            Fatura Numarası
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="invoice_id"
                            name="invoice_id"
                            value="<?= htmlspecialchars($invoice_id) ?>"
                            readonly>

                    </div>

                    <div class="form-group">

                        <label for="cc_holder_name">
                            Kart Üzerindeki İsim / Soyisim
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="cc_holder_name"
                            name="cc_holder_name"
                            placeholder="AD SOYAD"
                            maxlength="50"
                            value="<?= htmlspecialchars($formHolder) ?>"
                            required>

                    </div>

                    <div class="form-group">

                        <label for="cc_no">
                            Kart Numarası
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="cc_no"
                            name="cc_no"
                            placeholder="0000 0000 0000 0000"
                            maxlength="19"
                            inputmode="numeric"
                            autocomplete="off"
                            value="<?= htmlspecialchars($formCard) ?>"
                            required>

                    </div>

                    <div class="form-row">

                        <div class="form-group col-md-4">

                            <label for="expiry_month">
                                Son Kullanım Ayı
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="expiry_month"
                                name="expiry_month"
                                placeholder="AA"
                                maxlength="2"
                                inputmode="numeric"
                                value="<?= htmlspecialchars($formMonth) ?>"
                                required>

                        </div>

                        <div class="form-group col-md-4">

                            <label for="expiry_year">
                                Son Kullanım Yılı
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="expiry_year"
                                name="expiry_year"
                                placeholder="YY"
                                maxlength="2"
                                inputmode="numeric"
                                value="<?= htmlspecialchars($formYear) ?>"
                                required>

                        </div>

                        <div class="form-group col-md-4">

                            <label for="cvv">
                                CVV
                            </label>

                            <input
                                type="password"
                                class="form-control"
                                id="cvv"
                                name="cvv"
                                placeholder="***"
                                maxlength="3"
                                inputmode="numeric"
                                required>

                        </div>

                    </div>

                    <div class="form-row">

                        <div class="form-group col-md-6">

                            <label for="total">
                                Tutar
                            </label>

                            <input
                                type="number"
                                class="form-control"
                                id="total"
                                name="total"
                                min="1"
                                step="0.01"
                                placeholder="100"
                                value="<?= htmlspecialchars($formTotal) ?>"
                                required>

                        </div>

                        <div class="form-group col-md-6">

                            <label for="installments_number">
                                Taksit Sayısı
                            </label>

                            <select
                                class="form-control"
                                id="installments_number"
                                name="installments_number"
                                required>

                                <option value="1">
                                    Tek Çekim
                                </option>

                            </select>

                        </div>

                    </div>

                    <div class="form-row">

                        <div class="form-group col-md-6">

                            <label for="transaction_type">
                                İşlem Tipi
                            </label>

                            <select
                                class="form-control"
                                id="transaction_type"
                                name="transaction_type"
                                required>

                                <option
                                    value="Auth"
                                    <?= $formTransaction === 'Auth'
                                        ? 'selected'
                                        : '' ?>>

                                    Auth

                                </option>

                                <option
                                    value="PreAuth"
                                    <?= $formTransaction === 'PreAuth'
                                        ? 'selected'
                                        : '' ?>>

                                    PreAuth

                                </option>

                            </select>

                        </div>

                        <div class="form-group col-md-6">

                            <label for="invoice_description">
                                Fatura Açıklaması
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="invoice_description"
                                name="invoice_description"
                                placeholder="Ürün açıklaması"
                                value="<?= htmlspecialchars($formDescription) ?>"
                                required>

                        </div>

                    </div>

                    
                    <input
                        type="hidden"
                        id="selected_total"
                        value="<?= htmlspecialchars($formTotal) ?>">

                    <button
                        type="submit"
                        name="process_payment"
                        class="btn btn-primary btn-payment">

                        Ödemeyi Gönder

                    </button>

                </form>

            </div>


            

            <div class="right-panel">

                <div class="card-preview-wrapper">

                    <div class="preview-title">
                        Kart Önizleme
                    </div>

                    <div class="credit-card-preview">

                        <div class="card-top">

                            <div class="chip"></div>

                            <div
                                class="card-brand"
                                id="cardBrand">

                                CARD

                            </div>

                        </div>

                        <div
                            class="card-number-preview"
                            id="cardNumberPreview">

                            0000 0000 0000 0000

                        </div>

                        <div class="card-bottom">

                            <div>

                                <span class="card-label">
                                    Kart Sahibi
                                </span>

                                <div
                                    class="card-holder-preview"
                                    id="cardHolderPreview">

                                    AD SOYAD

                                </div>

                            </div>

                            <div>

                                <span class="card-label">
                                    Son Kullanım
                                </span>

                                <div
                                    class="card-expiry-preview"
                                    id="cardExpiryPreview">

                                    AA/YY

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="payment-summary">

                        <div class="summary-row">

                            <span class="summary-label">
                                Ana Tutar
                            </span>

                            <span
                                class="summary-value"
                                id="summaryAmount">

                                0,00 TL

                            </span>

                        </div>

                        <div class="summary-row">

                            <span class="summary-label">
                                Taksit
                            </span>

                            <span
                                class="summary-value"
                                id="summaryInstallment">

                                Tek Çekim

                            </span>

                        </div>

                        <div class="summary-row">

                            <span class="summary-label">
                                Komisyonlu Toplam
                            </span>

                            <span
                                class="summary-value"
                                id="summaryPayable">

                                0,00 TL

                            </span>

                        </div>

                        <div class="summary-row">

                            <span class="summary-label">
                                Komisyon
                            </span>

                            <span
                                class="summary-value commission-value"
                                id="summaryCommission">

                                0,00 TL

                            </span>

                        </div>

                        <div class="summary-row">

                            <span class="summary-label">
                                İşlem Tipi
                            </span>

                            <span
                                class="summary-value"
                                id="summaryTransaction">

                                <?= htmlspecialchars($formTransaction) ?>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


<?php



if (isset($_POST['process_payment'])) {

    

    $baseUrl =
        "https://testapp.halkode.com.tr/ccpayment/api/paySmart2D";


    

    $ccNo =
        preg_replace(
            '/\D/',
            '',
            $_POST['cc_no']
            ?? ''
        );


    

    $total =
        isset($_POST['total'])
            ? (float)$_POST['total']
            : 0;


    $installments_number =
        isset($_POST['installments_number'])
            ? (int)$_POST['installments_number']
            : 1;


    $currencyCode =
        "TRY";


    $postedInvoiceId =
        $_POST['invoice_id']
        ?? $invoice_id;


    $transactionType =
        $_POST['transaction_type']
        ?? 'Auth';


    $invoiceDescription =
        $_POST['invoice_description']
        ?? '';


    

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
        !is_array($decodedTokenResponse)
        ||
        ($decodedTokenResponse['status_code'] ?? 0) != 100
        ||
        empty($decodedTokenResponse['data']['token'])
    ) {

        echo '
        <div class="response-card">

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

            "cc_holder_name" =>
                $_POST['cc_holder_name']
                ?? '',

            "cc_no" =>
                $ccNo,

            "expiry_month" =>
                $_POST['expiry_month']
                ?? '',

            "expiry_year" =>
                $_POST['expiry_year']
                ?? '',

            "cvv" =>
                $_POST['cvv']
                ?? '',

            "currency_code" =>
                $currencyCode,

            "installments_number" =>
                $installments_number,

            "invoice_id" =>
                $postedInvoiceId,

            "invoice_description" =>
                $invoiceDescription,

            
            "total" =>
                $total,

            "merchant_key" =>
                $merchantKey,

            "transaction_type" =>
                $transactionType,

            
            "items" =>
                array(

                    array(

                        "name" =>
                            "item",

                        "price" =>
                            $total,

                        "quantity" =>
                            1,

                        "description" =>
                            $invoiceDescription

                    )

                ),

            "name" =>
                $_POST['cc_holder_name']
                ?? '',

            "surname" =>
                "Dao",

            "payment_status" =>
                1,

            

            "commission_by" =>
                "merchant",

            "is_commission_from_user" =>
                "1",

            

            "hash_key" =>
                generateHashKey(

                    $total,

                    $installments_number,

                    $currencyCode,

                    $merchantKey,

                    $postedInvoiceId,

                    $appSecret

                )

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
                    json_encode(
                        $data,
                        JSON_UNESCAPED_UNICODE
                    ),

                CURLOPT_RETURNTRANSFER =>
                    true,

                CURLOPT_CONNECTTIMEOUT =>
                    5,

                CURLOPT_TIMEOUT =>
                    30,

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


        $httpCode =
            curl_getinfo(
                $ch,
                CURLINFO_HTTP_CODE
            );


        curl_close(
            $ch
        );


        

        if ($response === false) {

            echo '

            <div class="response-card">

                <div class="api-error">

                    <strong>
                        API bağlantı hatası.
                    </strong>

                    <br>Bağlantı ayrıntıları güvenlik nedeniyle gizlendi.

                </div>

            </div>

            ';

        } else {

            

            $decodedResponse =
                json_decode(
                    $response,
                    true
                );


            if (is_array($decodedResponse)) {

                $statusCode =
                    $decodedResponse[
                        'status_code'
                    ]
                    ?? '-';


                $statusDescription =
                    $decodedResponse[
                        'status_description'
                    ]
                    ?? '';


                $isSuccess =
                    $statusCode == 100;


                ?>

                <div class="response-card">

                    <div
                        class="<?= $isSuccess
                            ? 'api-success'
                            : 'api-error' ?>">

                        <strong>

                            <?= $isSuccess
                                ? 'İşlem başarılı.'
                                : 'İşlem başarısız.' ?>

                        </strong>

                        <br>

                        Durum Kodu:

                        <strong>
                            <?= htmlspecialchars($statusCode) ?>
                        </strong>


                        <?php if ($statusDescription !== ''): ?>

                            <br>

                            Açıklama:

                            <?= htmlspecialchars(
                                $statusDescription
                            ) ?>

                        <?php endif; ?>

                    </div>


                    <?php if (!$isSuccess): ?>

                        <div class="alert alert-secondary mt-3 mb-0">

                            <strong>
                                Tutar Kontrolü
                            </strong>

                            <br><br>

                            Invoice Total:

                            <strong>
                                <?= htmlspecialchars((string)$total) ?>
                            </strong>

                            <br>

                            Item Price:

                            <strong>
                                <?= htmlspecialchars((string)$total) ?>
                            </strong>

                            <br>

                            Quantity:

                            <strong>
                                1
                            </strong>

                            <br>

                            Installment:

                            <strong>
                                <?= htmlspecialchars(
                                    (string)$installments_number
                                ) ?>
                            </strong>

                        </div>

                    <?php endif; ?>

                </div>

                <?php

            } else {

                echo '

                <div class="response-card">

                    <div class="api-error">

                        <strong>
                            Geçersiz API yanıtı.
                        </strong>

                        <br>

                        HTTP Kod:

                        ' .
                        htmlspecialchars(
                            (string)$httpCode
                        ) .
                        '

                    </div>

                </div>

                ';

            }

        }

    }

}




function generateHashKey(
    $total,
    $installment,
    $currency_code,
    $merchant_key,
    $invoice_id,
    $app_secret
) {

    

    $totalString =
        rtrim(
            rtrim(
                number_format(
                    (float)$total,
                    4,
                    '.',
                    ''
                ),
                '0'
            ),
            '.'
        );


    $data =
        $totalString .
        '|' .
        $installment .
        '|' .
        $currency_code .
        '|' .
        $merchant_key .
        '|' .
        $invoice_id;


    $iv = bin2hex(random_bytes(8));


    $password =
        sha1(
            $app_secret
        );


    $salt = bin2hex(random_bytes(2));


    $saltWithPassword =
        hash(
            'sha256',
            $password .
            $salt
        );


    $encrypted =
        openssl_encrypt(
            $data,
            'aes-256-cbc',
            $saltWithPassword,
            0,
            $iv
        );


    $msg_encrypted_bundle =
        $iv .
        ':' .
        $salt .
        ':' .
        $encrypted;


    $msg_encrypted_bundle =
        str_replace(
            '/',
            '__',
            $msg_encrypted_bundle
        );


    return $msg_encrypted_bundle;

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

const ccNoInput =
    document.getElementById('cc_no');

const holderInput =
    document.getElementById('cc_holder_name');

const expiryMonthInput =
    document.getElementById('expiry_month');

const expiryYearInput =
    document.getElementById('expiry_year');

const cvvInput =
    document.getElementById('cvv');

const totalInput =
    document.getElementById('total');

const installmentSelect =
    document.getElementById('installments_number');

const transactionTypeSelect =
    document.getElementById('transaction_type');

const selectedTotalInput =
    document.getElementById('selected_total');








ccNoInput.addEventListener(
    'input',
    function () {

        const digits =
            this.value
                .replace(/\D/g, '')
                .substring(0, 16);


        const groups =
            digits.match(
                /.{1,4}/g
            );


        this.value =
            groups
                ? groups.join(' ')
                : '';


        updateCardPreview();

        updateInstallments();

    }
);








holderInput.addEventListener(
    'input',
    updateCardPreview
);








expiryMonthInput.addEventListener(
    'input',
    function () {

        this.value =
            this.value
                .replace(/\D/g, '')
                .substring(0, 2);


        updateCardPreview();

    }
);








expiryYearInput.addEventListener(
    'input',
    function () {

        this.value =
            this.value
                .replace(/\D/g, '')
                .substring(0, 2);


        updateCardPreview();

    }
);








cvvInput.addEventListener(
    'input',
    function () {

        this.value =
            this.value
                .replace(/\D/g, '')
                .substring(0, 3);

    }
);








totalInput.addEventListener(
    'input',
    function () {

        selectedTotalInput.value =
            this.value;


        updateSummaryAmount();

        updateInstallments();

    }
);








installmentSelect.addEventListener(
    'change',
    function () {

        updateSelectedInstallment();

    }
);








transactionTypeSelect.addEventListener(
    'change',
    function () {

        document.getElementById(
            'summaryTransaction'
        ).textContent =
            this.value;

    }
);








function updateCardPreview() {

    const digits =
        ccNoInput.value
            .replace(/\D/g, '');


    document.getElementById(
        'cardNumberPreview'
    ).textContent =
        ccNoInput.value.trim()
            ? ccNoInput.value
            : '0000 0000 0000 0000';


    document.getElementById(
        'cardHolderPreview'
    ).textContent =
        holderInput.value.trim()
            ? holderInput.value.toUpperCase()
            : 'AD SOYAD';


    const month =
        expiryMonthInput.value
        || 'AA';


    const year =
        expiryYearInput.value
        || 'YY';


    document.getElementById(
        'cardExpiryPreview'
    ).textContent =
        month +
        '/' +
        year;


    let brand =
        'CARD';


    if (/^4/.test(digits)) {

        brand =
            'VISA';

    } else if (
        /^5[1-5]/.test(digits)
        ||
        /^2(2[2-9]|[3-6][0-9]|7[01]|720)/.test(digits)
    ) {

        brand =
            'MASTERCARD';

    } else if (
        /^3[47]/.test(digits)
    ) {

        brand =
            'AMEX';

    }


    document.getElementById(
        'cardBrand'
    ).textContent =
        brand;

}








function formatMoney(amount) {

    if (isNaN(amount)) {
        return '0,00 TL';
    }


    return amount.toLocaleString(
        'tr-TR',
        {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }
    ) +
    ' TL';

}








function updateSummaryAmount() {

    const amount =
        parseFloat(
            totalInput.value
        );


    document.getElementById(
        'summaryAmount'
    ).textContent =
        formatMoney(amount);


    if (
        !selectedTotalInput.value
        ||
        isNaN(
            parseFloat(
                selectedTotalInput.value
            )
        )
    ) {

        selectedTotalInput.value =
            !isNaN(amount)
                ? amount
                : '';


        updatePayableSummary(
            amount
        );

    }

}








function updatePayableSummary(amount) {

    document.getElementById(
        'summaryPayable'
    ).textContent =
        formatMoney(amount);


    const original =
        parseFloat(
            totalInput.value
        );


    let commission =
        0;


    if (
        !isNaN(amount)
        &&
        !isNaN(original)
        &&
        amount > original
    ) {

        commission =
            amount - original;

    }


    document.getElementById(
        'summaryCommission'
    ).textContent =
        formatMoney(
            commission
        );

}








function updateSelectedInstallment() {

    if (
        installmentSelect.selectedIndex < 0
    ) {

        return;

    }


    const option =
        installmentSelect.options[
            installmentSelect.selectedIndex
        ];


    document.getElementById(
        'summaryInstallment'
    ).textContent =
        option.text;


    const payableTotal =
        parseFloat(
            option.dataset.total
        );


    if (!isNaN(payableTotal)) {

        




        selectedTotalInput.value =
            payableTotal;


        updatePayableSummary(
            payableTotal
        );

    } else {

        const original =
            parseFloat(
                totalInput.value
            );


        selectedTotalInput.value =
            original;


        updatePayableSummary(
            original
        );

    }

}








let installmentRequestId =
    0;


function updateInstallments() {

    const ccNo =
        ccNoInput.value
            .replace(/\D/g, '');


    const total =
        totalInput.value;


    const parsedTotal =
        parseFloat(
            total
        );


    if (
        ccNo.length !== 16
        ||
        total === ''
        ||
        isNaN(parsedTotal)
        ||
        parsedTotal <= 0
    ) {

        fallbackSinglePayment();

        return;

    }


    const currentRequestId =
        ++installmentRequestId;


    installmentSelect.innerHTML =
        '<option value="">Taksitler yükleniyor...</option>';


    










    fetch(
        'get_installment3dkomisyon.php',
        {

            method:
                'POST',

            headers: {

                'Content-Type':
                    'application/json'

            },

            body:
                JSON.stringify({

                    cc_no:
                        ccNo,

                    total:
                        total

                })

        }
    )

    .then(
        response => {

            if (!response.ok) {

                throw new Error(
                    'HTTP ' +
                    response.status
                );

            }


            return response.json();

        }
    )

    .then(
        data => {

            if (
                currentRequestId !==
                installmentRequestId
            ) {

                return;

            }


            installmentSelect.innerHTML =
                '';


            if (
                data.installments
                &&
                Array.isArray(
                    data.installments
                )
                &&
                data.installments.length > 0
            ) {

                data.installments.forEach(
                    installment => {

                        const option =
                            document.createElement(
                                'option'
                            );


                        const installmentNumber =
                            parseInt(
                                installment.installment_number
                            );


                        if (
                            isNaN(installmentNumber)
                            ||
                            installmentNumber <= 0
                        ) {

                            return;

                        }


                        option.value =
                            installmentNumber;


                        








                        let apiAmount =
                            parseFloat(
                                installment.amount
                            );


                        



                        if (isNaN(apiAmount)) {

                            apiAmount =
                                parseFloat(
                                    installment.amount_to_be_paid
                                );

                        }


                        if (isNaN(apiAmount)) {

                            apiAmount =
                                parseFloat(
                                    installment.payable_amount
                                );

                        }


                        


                        const totalAmount =
                            !isNaN(apiAmount)
                                ? apiAmount
                                : parsedTotal;


                        


                        option.dataset.total =
                            totalAmount;


                        if (
                            installmentNumber === 1
                        ) {

                            option.text =
                                'Tek Çekim - '
                                +
                                totalAmount.toFixed(2)
                                +
                                ' TL';

                        } else {

                            const monthlyAmount =
                                (
                                    totalAmount
                                    /
                                    installmentNumber
                                ).toFixed(2);


                            option.text =
                                installmentNumber
                                +
                                ' Taksit - Aylık: '
                                +
                                monthlyAmount
                                +
                                ' TL (Toplam: '
                                +
                                totalAmount.toFixed(2)
                                +
                                ' TL)';

                        }


                        installmentSelect.appendChild(
                            option
                        );

                    }
                );


                if (
                    installmentSelect.options.length > 0
                ) {

                    installmentSelect.selectedIndex =
                        0;


                    updateSelectedInstallment();

                } else {

                    fallbackSinglePayment();

                }

            } else {

                fallbackSinglePayment();

            }

        }
    )

    .catch(
        error => {

            console.error(
                'Taksit sorgu hatası:',
                error
            );


            fallbackSinglePayment();

        }
    );

}








function fallbackSinglePayment() {

    const original =
        parseFloat(
            totalInput.value
        );


    installmentSelect.innerHTML =
        '<option value="1">Tek Çekim</option>';


    selectedTotalInput.value =
        !isNaN(original)
            ? original
            : '';


    document.getElementById(
        'summaryInstallment'
    ).textContent =
        'Tek Çekim';


    updatePayableSummary(
        original
    );

}








updateCardPreview();

updateSummaryAmount();


if (
    ccNoInput.value
        .replace(/\D/g, '')
        .length === 16
    &&
    totalInput.value !== ''
) {

    updateInstallments();

}

</script>

</body>

</html>