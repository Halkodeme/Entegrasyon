<?php
require_once __DIR__ . '/degisken.php';
 require_once __DIR__ . '/security.php';  halkode_require_sensitive_action();
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>3D Ödeme İşlemi</title>

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
        }

        footer {
            flex-shrink: 0;
        }

        .payment-page {
            max-width: 1350px;
            margin: 35px auto;
            padding-left: 20px;
            padding-right: 20px;
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

        
        .form-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 28px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
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
            border: none;
            padding: 13px 20px;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 10px;
        }

        
        .right-panel {
            position: sticky;
            top: 25px;
        }

        .card-preview-wrapper {
            background: #ffffff;
            border-radius: 14px;
            padding: 30px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
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
            color: #ffffff;

            background:
                radial-gradient(
                    circle at top right,
                    rgba(255, 255, 255, 0.25),
                    transparent 35%
                ),
                linear-gradient(
                    135deg,
                    #151515,
                    #343a40 55%,
                    #111111
                );

            box-shadow:
                0 20px 45px rgba(0, 0, 0, 0.23);

            position: relative;
            overflow: hidden;
        }

        .credit-card-preview::before {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            right: -80px;
            bottom: -100px;
        }

        .credit-card-preview::after {
            content: "";
            position: absolute;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.08);
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
            border: 1px solid rgba(80, 60, 10, 0.35);
        }

        .chip::before {
            left: 17px;
            top: 0;
            bottom: 0;
        }

        .chip::after {
            top: 18px;
            left: 0;
            right: 0;
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
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            z-index: 2;
        }

        .card-label {
            display: block;
            font-size: 9px;
            opacity: 0.7;
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
            border-top: 1px solid #eeeeee;
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
            border-bottom: none;
        }

        .summary-label {
            color: #6c757d;
        }

        .summary-value {
            font-weight: 600;
            text-align: right;
        }

        
        @media (max-width: 992px) {
            .payment-layout {
                grid-template-columns: 1fr;
            }

            .right-panel {
                position: static;
            }

            .card-preview-wrapper {
                margin-bottom: 25px;
            }
        }

        @media (max-width: 576px) {
            .payment-page {
                padding-left: 12px;
                padding-right: 12px;
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


$total = 2000;



$invoice_id = halkode_random_invoice_id('3D');

?>


<div class="main-content">

    <div class="container-fluid payment-page">

        <h2 class="payment-title">
            3D Ödeme İşlemleri
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
                            required>

                    </div>


                    <div class="form-row">

                        <div class="form-group col-md-4">

                            <label for="expiry_month">
                                Son Kullanım Ayı
                            </label>

                            <input
                                type="text"
                                placeholder="AA"
                                maxlength="2"
                                class="form-control"
                                id="expiry_month"
                                name="expiry_month"
                                inputmode="numeric"
                                required>

                        </div>


                        <div class="form-group col-md-4">

                            <label for="expiry_year">
                                Son Kullanım Yılı
                            </label>

                            <input
                                type="text"
                                placeholder="YY"
                                maxlength="2"
                                class="form-control"
                                id="expiry_year"
                                name="expiry_year"
                                inputmode="numeric"
                                required>

                        </div>


                        <div class="form-group col-md-4">

                            <label for="cvv">
                                CVV
                            </label>

                            <input
                                type="password"
                                placeholder="***"
                                maxlength="3"
                                class="form-control"
                                id="cvv"
                                name="cvv"
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
                                type="text"
                                class="form-control"
                                id="total"
                                name="total"
                                value="<?= htmlspecialchars($total) ?>"
                                readonly>

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

                                <option value="Auth">
                                    Auth
                                </option>

                                <option value="PreAuth">
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
                                required>

                        </div>

                    </div>


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
                                Tutar
                            </span>

                            <span
                                class="summary-value"
                                id="summaryAmount">

                                <?= number_format(
                                    $total,
                                    2,
                                    ',',
                                    '.'
                                ) ?>

                                TL

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
                                İşlem Tipi
                            </span>

                            <span
                                class="summary-value"
                                id="summaryTransaction">

                                Auth

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


<?php



if (isset($_POST['process_payment'])) {

    $baseUrl =
        "https://testapp.halkode.com.tr/ccpayment/api/paySmart3D";


    
    require_once __DIR__ . '/api_security.php';

    $token = halkode_get_token($app_id, $appSecret);
    if ($token === null) {
        echo '<div class="alert alert-danger mt-4"><strong>Token alınamadı.</strong></div>';
        return;
    }


    
    $ccNo =
        preg_replace(
            '/\D/',
            '',
            $_POST['cc_no'] ?? ''
        );


    $installments_number =
        $_POST['installments_number']
        ?? 1;


    $currencyCode =
        'TRY';


    
    $paymentTotal =
        $total;


    $postedInvoiceId =
        $_POST['invoice_id']
        ?? $invoice_id;


    $invoiceDescription =
        $_POST['invoice_description']
        ?? '';


    $transactionType =
        $_POST['transaction_type']
        ?? 'Auth';


    

    $returnUrl = halkode_public_url('succes.php');
    $cancelUrl = halkode_public_url('fail.php');

    if ($returnUrl === '' || $cancelUrl === '') {
        echo '<div class="alert alert-danger mt-4"><strong>HALKODE_PUBLIC_BASE_URL yapılandırması eksik veya güvenli değil.</strong></div>';
        return;
    }

    if (!halkode_remember_payment_attempt($postedInvoiceId, (float)$paymentTotal, (string)$transactionType, (int)$installments_number)) {
        echo '<div class="alert alert-danger mt-4"><strong>Ödeme doğrulama kaydı oluşturulamadı.</strong></div>';
        return;
    }

    $data = array(

        "cc_holder_name" =>
            $_POST['cc_holder_name'] ?? '',

        "cc_no" =>
            $ccNo,

        "expiry_month" =>
            $_POST['expiry_month'] ?? '',

        "expiry_year" =>
            $_POST['expiry_year'] ?? '',

        "cvv" =>
            $_POST['cvv'] ?? '',

        "currency_code" =>
            $currencyCode,

        "installments_number" =>
            $installments_number,

        "invoice_id" =>
            $postedInvoiceId,

        "invoice_description" =>
            $invoiceDescription,

        "total" =>
            $paymentTotal,

        "merchant_key" =>
            $merchantKey,

        "items" =>
            array(
                array(
                    "name" => "Item1",
                    "price" => $paymentTotal,
                    "quantity" => 1,
                    "description" => $invoiceDescription
                )
            ),

        "name" =>
            "John",

        "surname" =>
            "Dao",

        "hash_key" =>
            generateHashKey(

                $paymentTotal,

                $installments_number,

                $currencyCode,

                $merchantKey,

                $postedInvoiceId,

                $appSecret

            ),

        "return_url" =>
            $returnUrl,

        "cancel_url" =>
            $cancelUrl,

        "transaction_type" =>
            $transactionType,

        "commission_by" =>
            "merchant",

        "is_commission_from_user" =>
            "1"

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

                    'Content-Type: application/json',
                    "Authorization: Bearer $token"

                ),

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


    $httpCode =
        curl_getinfo(
            $ch,
            CURLINFO_HTTP_CODE
        );


    $error =
        curl_error(
            $ch
        );


    curl_close(
        $ch
    );


    
    if (
        $httpCode !== 200 ||
        !$response
    ) {

        echo '

        <div
            class="alert alert-danger mt-4">

            <strong>
                API isteği başarısız.
            </strong>

            <br>

            İstek HalkÖde servisine güvenli şekilde iletilemedi.

        </div>

        ';

    } else {

        
        echo $response;

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

    $data =
        $total .
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








ccNoInput.addEventListener(
    'input',
    function () {

        let digits =
            this.value
                .replace(/\D/g, '')
                .substring(0, 16);


        let groups =
            digits.match(/.{1,4}/g);


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








installmentSelect.addEventListener(
    'change',
    function () {

        document.getElementById(
            'summaryInstallment'
        ).textContent =
            this.options[
                this.selectedIndex
            ].text;

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
        expiryMonthInput.value || 'AA';

    const year =
        expiryYearInput.value || 'YY';


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
        /^5[1-5]/.test(digits) ||
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








function updateInstallments() {

    const ccNo =
        ccNoInput.value
            .replace(/\D/g, '');


    const total =
        totalInput.value;


    


    if (
        ccNo.length !== 16 ||
        !total
    ) {

        installmentSelect.innerHTML =
            '<option value="1">Tek Çekim</option>';

        document.getElementById(
            'summaryInstallment'
        ).textContent =
            'Tek Çekim';

        return;

    }


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
            response =>
                response.json()
        )

        .then(
            data => {

                installmentSelect.innerHTML =
                    '';


                if (
                    data.installments &&
                    Array.isArray(
                        data.installments
                    ) &&
                    data.installments.length > 0
                ) {

                    data.installments.forEach(
                        installment => {

                            const option =
                                document.createElement(
                                    'option'
                                );


                            option.value =
                                installment.installment_number;


                            const totalAmount =
                                parseFloat(
                                    installment.amount
                                );


                            if (
                                installment.installment_number === 1
                            ) {

                                option.text =
                                    `Tek Çekim - ${totalAmount.toFixed(2)} ${installment.currency}`;

                            } else {

                                const monthlyAmount =
                                    (
                                        totalAmount /
                                        installment.installment_number
                                    ).toFixed(2);


                                option.text =
                                    `${installment.installment_number} Taksit - Aylık: ${monthlyAmount} ${installment.currency} (Toplam: ${totalAmount.toFixed(2)} ${installment.currency})`;

                            }


                            installmentSelect.appendChild(
                                option
                            );

                        }
                    );


                    


                    document.getElementById(
                        'summaryInstallment'
                    ).textContent =
                        installmentSelect.options[
                            0
                        ].text;

                } else {

                    installmentSelect.innerHTML =
                        '<option value="1">Tek Çekim</option>';


                    document.getElementById(
                        'summaryInstallment'
                    ).textContent =
                        'Tek Çekim';

                }

            }
        )

        .catch(
            error => {

                console.error(
                    'Taksit sorgu hatası:',
                    error
                );


                installmentSelect.innerHTML =
                    '<option value="1">Tek Çekim</option>';


                document.getElementById(
                    'summaryInstallment'
                ).textContent =
                    'Tek Çekim';

            }
        );

}








updateCardPreview();

</script>

</body>

</html>