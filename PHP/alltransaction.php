<?php
require_once __DIR__ . '/degisken.php';
require_once __DIR__ . '/security.php';
halkode_require_sensitive_action();
halkode_security_headers();
halkode_require_remote_access();
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tüm Yapılan İşlemleri Görme Entegrasyonu</title>

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

        .page-container {
            max-width: 1450px;
            margin: 35px auto;
            padding-left: 20px;
            padding-right: 20px;
        }

        .card-box {
            background: #ffffff;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
        }

        .card-box h2 {
            margin-bottom: 20px;
            font-size: 26px;
            font-weight: 600;
        }

        .filter-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .filter-box h5 {
            margin-bottom: 15px;
            font-weight: 600;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .transactions-table {
            background: #ffffff;
            font-size: 13px;
            margin-bottom: 0;
        }

        .transactions-table th {
            white-space: nowrap;
            vertical-align: middle !important;
        }

        .transactions-table td {
            white-space: nowrap;
            vertical-align: middle !important;
        }

        .result-cell {
            white-space: normal !important;
            min-width: 250px;
            max-width: 400px;
        }

        .invoice-cell {
            font-weight: 600;
        }

        .amount-cell {
            font-weight: 700;
        }

        .summary-box {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

        .summary-item {
            min-width: 150px;
            padding: 13px 18px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background: #f8f9fa;
        }

        .summary-item span {
            display: block;
            color: #6c757d;
            font-size: 13px;
        }

        .summary-item strong {
            display: block;
            margin-top: 3px;
            font-size: 20px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-success {
            background: #d4edda;
            color: #155724;
        }

        .status-failed {
            background: #f8d7da;
            color: #721c24;
        }

        .status-other {
            background: #fff3cd;
            color: #856404;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 25px;
        }

        .pagination {
            flex-wrap: wrap;
            gap: 4px;
        }

        .page-item .page-link {
            border-radius: 6px !important;
            min-width: 40px;
            text-align: center;
        }

        .page-item.active .page-link {
            font-weight: 600;
        }

        .page-info {
            margin-top: 15px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }

        .search-info {
            font-size: 13px;
            color: #6c757d;
            margin-top: 10px;
        }

        .btns {
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .page-container {
                margin-top: 20px;
            }

            .card-box {
                padding: 18px;
            }

            .summary-item {
                flex: 1;
            }
        }
    </style>
</head>

<body>

<?php include 'nav.php'; ?>

<div class="main-content">

    <div class="container-fluid page-container">

        <div class="card-box">

            <h2>
                Tüm Yapılan İşlemleri Görme Entegrasyonu
            </h2>

            <div class="alert alert-info">
                Hesabınıza ait ödeme hareketlerini görüntüleyebilir,
                sipariş numarası veya tarihe göre arayabilirsiniz.
            </div>

            <form method="get">

                <input
                    type="hidden"
                    name="show"
                    value="1">

                <div class="filter-box">

                    <h5>İşlem Ara</h5>

                    <div class="form-row">

                        <div class="form-group col-md-5">

                            <label for="order_id">
                                Sipariş No
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="order_id"
                                name="order_id"
                                placeholder="Örn: VP17910059599321360"
                                value="<?= htmlspecialchars($_GET['order_id'] ?? '') ?>">

                        </div>

                        <div class="form-group col-md-4">

                            <label for="transaction_date">
                                Tarih
                            </label>

                            <input
                                type="date"
                                class="form-control"
                                id="transaction_date"
                                name="transaction_date"
                                value="<?= htmlspecialchars($_GET['transaction_date'] ?? '') ?>">

                        </div>

                        <div class="form-group col-md-3 d-flex align-items-end">

                            <button
                                type="submit"
                                class="btn btn-primary btn-block">

                                Ara

                            </button>

                        </div>

                    </div>

                    <div class="mt-2">

                        <a
                            href="?show=1&page=1"
                            class="btn btn-outline-secondary btn-sm">

                            Filtreleri Temizle

                        </a>

                    </div>

                    <div class="search-info">
                        Tam sipariş numarası girildiğinde işlem bulunduğu anda arama durur.
                    </div>

                </div>

            </form>

        </div>


<?php



$showTransactions =
    isset($_GET['show']) &&
    $_GET['show'] == '1';


$page =
    isset($_GET['page'])
        ? (int) $_GET['page']
        : 1;


if ($page < 1) {
    $page = 1;
}


$orderSearch =
    trim(
        $_GET['order_id']
        ?? ''
    );


$dateSearch =
    trim(
        $_GET['transaction_date']
        ?? ''
    );


$isSearch =
    $orderSearch !== '' ||
    $dateSearch !== '';




if ($showTransactions) {

    


    

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
        <div class="card-box">

            <div class="alert alert-danger mb-0">

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


        

        if ($isSearch) {

            $transactions = array();

            
            $scannedPages = 0;


            

            $firstResponse =
                getTransactionsPage(
                    1,
                    $merchantKey,
                    $token
                );


            if (
                !is_array($firstResponse) ||
                ($firstResponse['status_code'] ?? 0) != 100
            ) {

                echo '
                <div class="card-box">

                    <div class="alert alert-danger mb-0">

                        İşlemler alınamadı.

                    </div>

                </div>
                ';

            } else {

                $paginationData =
                    $firstResponse['data']
                    ?? array();


                $lastPage =
                    max(
                        1,
                        (int)(
                            $paginationData['last_page']
                            ?? 1
                        )
                    );


                
                for (
                    $apiPage = 1;
                    $apiPage <= $lastPage;
                    $apiPage++
                ) {

                    
                    if ($apiPage === 1) {

                        $pageResponse =
                            $firstResponse;

                    } else {

                        $pageResponse =
                            getTransactionsPage(
                                $apiPage,
                                $merchantKey,
                                $token
                            );

                    }


                    if (
                        !is_array($pageResponse) ||
                        ($pageResponse['status_code'] ?? 0) != 100
                    ) {

                        continue;

                    }


                    $scannedPages++;


                    $pageItems =
                        $pageResponse['data']['data']
                        ?? array();


                    if (empty($pageItems)) {
                        break;
                    }


                    

                    $pageNewestDate = null;
                    $pageOldestDate = null;


                    foreach (
                        $pageItems
                        as $transaction
                    ) {

                        $createdAt =
                            $transaction['created_at']
                            ?? '';


                        if ($createdAt !== '') {

                            $dateOnly =
                                substr(
                                    $createdAt,
                                    0,
                                    10
                                );


                            if (
                                $pageNewestDate === null ||
                                $dateOnly > $pageNewestDate
                            ) {

                                $pageNewestDate =
                                    $dateOnly;

                            }


                            if (
                                $pageOldestDate === null ||
                                $dateOnly < $pageOldestDate
                            ) {

                                $pageOldestDate =
                                    $dateOnly;

                            }

                        }

                    }


                    
                    if (
                        $dateSearch !== '' &&
                        $pageNewestDate !== null &&
                        $pageNewestDate < $dateSearch
                    ) {

                        break;

                    }


                    

                    $exactOrderFound = false;


                    foreach (
                        $pageItems
                        as $transaction
                    ) {

                        $matchOrder = true;
                        $matchDate = true;


                        

                        if ($orderSearch !== '') {

                            $orderId =
                                trim(
                                    $transaction['order_id']
                                    ?? ''
                                );


                            
                            $matchOrder =
                                stripos(
                                    $orderId,
                                    $orderSearch
                                ) !== false;


                            
                            if (
                                strcasecmp(
                                    $orderId,
                                    $orderSearch
                                ) === 0
                            ) {

                                $exactOrderFound =
                                    true;

                            }

                        }


                        

                        if ($dateSearch !== '') {

                            $createdAt =
                                $transaction['created_at']
                                ?? '';


                            if ($createdAt !== '') {

                                
                                $transactionDate =
                                    substr(
                                        $createdAt,
                                        0,
                                        10
                                    );


                                $matchDate =
                                    $transactionDate ===
                                    $dateSearch;

                            } else {

                                $matchDate =
                                    false;

                            }

                        }


                        

                        if (
                            $matchOrder &&
                            $matchDate
                        ) {

                            $transactions[] =
                                $transaction;

                        }

                    }


                    

                    if (
                        $orderSearch !== '' &&
                        $exactOrderFound
                    ) {

                        break;

                    }


                    

                    if (
                        $dateSearch !== '' &&
                        $pageOldestDate !== null &&
                        $pageOldestDate < $dateSearch
                    ) {

                        break;

                    }

                }


                

                renderTransactions(
                    $transactions,
                    true,
                    1,
                    1,
                    count($transactions),
                    $orderSearch,
                    $dateSearch,
                    $scannedPages
                );

            }

        }

        

        else {

            $response =
                getTransactionsPage(
                    $page,
                    $merchantKey,
                    $token
                );


            if (
                !is_array($response) ||
                ($response['status_code'] ?? 0) != 100
            ) {

                echo '
                <div class="card-box">

                    <div class="alert alert-danger mb-0">

                        İşlemler alınamadı.

                    </div>

                </div>
                ';

            } else {

                $paginationData =
                    $response['data']
                    ?? array();


                $transactions =
                    $paginationData['data']
                    ?? array();


                $currentPage =
                    (int)(
                        $paginationData['current_page']
                        ?? $page
                    );


                $lastPage =
                    (int)(
                        $paginationData['last_page']
                        ?? 1
                    );


                $total =
                    (int)(
                        $paginationData['total']
                        ?? count($transactions)
                    );


                renderTransactions(
                    $transactions,
                    false,
                    $currentPage,
                    $lastPage,
                    $total,
                    '',
                    '',
                    1
                );

            }

        }

    }

}

?>

    </div>

</div>


<?php include 'footer.php'; ?>


<?php



function getTransactionsPage(
    $page,
    $merchantKey,
    $token
) {

    $baseUrl =
        "https://testapp.halkode.com.tr/ccpayment/api/alltransaction?page="
        . (int) $page;


    $data = array(

        "merchant_key" =>
            $merchantKey

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

        return array(

            'status_code' =>
                0,

            'status_description' =>
                'HalkÖde servisine bağlantı kurulamadı.'

        );

    }


    $decoded =
        json_decode(
            $response,
            true
        );


    if (!is_array($decoded)) {

        return array(

            'status_code' =>
                0,

            'status_description' =>
                'Geçersiz JSON yanıtı'

        );

    }


    return $decoded;

}




function renderTransactions(
    $transactions,
    $isSearch,
    $currentPage,
    $lastPage,
    $total,
    $orderSearch,
    $dateSearch,
    $scannedPages = 1
) {

    $successCount = 0;
    $failedCount = 0;
    $totalAmount = 0;


    foreach (
        $transactions
        as $transaction
    ) {

        $result =
            $transaction['result']
            ?? '';


        if (
            stripos(
                $result,
                'Approved'
            ) !== false
        ) {

            $successCount++;

        } elseif (
            stripos(
                $result,
                'Failed'
            ) !== false
        ) {

            $failedCount++;

        }


        $totalAmount +=
            (float)(
                $transaction['product_price']
                ?? 0
            );

    }

    ?>

    <div class="card-box">


        <?php if ($isSearch): ?>


            <div class="alert alert-info">

                <strong>
                    Arama Sonucu
                </strong>

                <br>

                <?= count($transactions) ?>
                işlem bulundu.


                <?php if ($orderSearch !== ''): ?>

                    <br>

                    Sipariş No:

                    <strong>
                        <?= htmlspecialchars($orderSearch) ?>
                    </strong>

                <?php endif; ?>


                <?php if ($dateSearch !== ''): ?>

                    <br>

                    Tarih:

                    <strong>
                        <?= htmlspecialchars($dateSearch) ?>
                    </strong>

                <?php endif; ?>


                <br>

                <small>
                    <?= (int)$scannedPages ?>
                    API sayfası tarandı.
                </small>

            </div>


        <?php else: ?>


            <div class="alert alert-success">

                <strong>
                    İşlemler başarıyla getirildi.
                </strong>

            </div>


        <?php endif; ?>


        <div class="summary-box">

            <div class="summary-item">

                <span>
                    Gösterilen İşlem
                </span>

                <strong>
                    <?= count($transactions) ?>
                </strong>

            </div>


            <div class="summary-item">

                <span>
                    Başarılı
                </span>

                <strong>
                    <?= $successCount ?>
                </strong>

            </div>


            <div class="summary-item">

                <span>
                    Başarısız
                </span>

                <strong>
                    <?= $failedCount ?>
                </strong>

            </div>


            <div class="summary-item">

                <span>
                    Gösterilen Tutar
                </span>

                <strong>

                    <?= number_format(
                        $totalAmount,
                        2,
                        ',',
                        '.'
                    ) ?>

                    ₺

                </strong>

            </div>

        </div>


        <?php if (count($transactions) > 0): ?>


            <div class="table-responsive">

                <table
                    class="table table-bordered table-hover transactions-table">

                    <thead class="thead-dark">

                        <tr>

                            <th>ID</th>
                            <th>Fatura No</th>
                            <th>Sipariş No</th>
                            <th>Ödeme ID</th>
                            <th>Tarih</th>
                            <th>Kart</th>
                            <th>Banka</th>
                            <th>Kart Programı</th>
                            <th>Tutar</th>
                            <th>Taksit</th>
                            <th>POS</th>
                            <th>MD Status</th>
                            <th>Auth Code</th>
                            <th>Durum</th>
                            <th>Sonuç</th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php foreach ($transactions as $transaction): ?>


                        <?php

                        $result =
                            $transaction['result']
                            ?? '';


                        

                        if (
                            stripos(
                                $result,
                                'Approved'
                            ) !== false
                        ) {

                            $statusText =
                                'Başarılı';

                            $statusClass =
                                'status-success';

                            $rowClass =
                                'table-success';

                        } elseif (
                            stripos(
                                $result,
                                'Failed'
                            ) !== false
                        ) {

                            $statusText =
                                'Başarısız';

                            $statusClass =
                                'status-failed';

                            $rowClass =
                                'table-danger';

                        } else {

                            $statusText =
                                'Diğer';

                            $statusClass =
                                'status-other';

                            $rowClass =
                                '';

                        }


                        

                        $amount =
                            (float)(
                                $transaction['product_price']
                                ?? 0
                            );


                        

                        $currency =
                            $transaction['currency_symbol']
                            ?? '₺';


                        

                        $installment =
                            (int)(
                                $transaction['installment']
                                ?? 1
                            );


                        $installmentText =
                            $installment <= 1
                                ? 'Tek Çekim'
                                : $installment . ' Taksit';


                        

                        $createdAt =
                            $transaction['created_at']
                            ?? '-';


                        if ($createdAt !== '-') {

                            $timestamp =
                                strtotime(
                                    $createdAt
                                );


                            if ($timestamp) {

                                $createdAt =
                                    date(
                                        'd.m.Y H:i:s',
                                        $timestamp
                                    );

                            }

                        }

                        ?>


                        <tr class="<?= $rowClass ?>">


                            <td>

                                <?= htmlspecialchars(
                                    $transaction['id']
                                    ?? '-'
                                ) ?>

                            </td>


                            <td class="invoice-cell">

                                <?= htmlspecialchars(
                                    $transaction['invoice_id']
                                    ?? '-'
                                ) ?>

                            </td>


                            <td>

                                <strong>

                                    <?= htmlspecialchars(
                                        $transaction['order_id']
                                        ?? '-'
                                    ) ?>

                                </strong>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $transaction['payment_id']
                                    ?? '-'
                                ) ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $createdAt
                                ) ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $transaction['credit_card_no']
                                    ?? '-'
                                ) ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $transaction['card_holder_bank']
                                    ??
                                    $transaction['issuer_name']
                                    ??
                                    '-'
                                ) ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $transaction['card_program']
                                    ?? '-'
                                ) ?>

                            </td>


                            <td class="amount-cell">

                                <?= number_format(
                                    $amount,
                                    2,
                                    ',',
                                    '.'
                                ) ?>

                                <?= htmlspecialchars(
                                    $currency
                                ) ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $installmentText
                                ) ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $transaction['pos_name']
                                    ?? '-'
                                ) ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $transaction['md_status']
                                    ?? '-'
                                ) ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $transaction['auth_code']
                                    ?? '-'
                                ) ?>

                            </td>


                            <td>

                                <span class="status-badge <?= $statusClass ?>">

                                    <?= htmlspecialchars(
                                        $statusText
                                    ) ?>

                                </span>

                            </td>


                            <td class="result-cell">

                                <?= htmlspecialchars(
                                    $result ?: '-'
                                ) ?>

                            </td>


                        </tr>


                    <?php endforeach; ?>


                    </tbody>

                </table>

            </div>


            <?php

            

            if (
                !$isSearch &&
                $lastPage > 1
            ):

            ?>


                <div class="pagination-wrapper">

                    <nav>

                        <ul class="pagination">


                            

                            <li
                                class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">

                                <a
                                    class="page-link"
                                    href="?show=1&page=1">

                                    «

                                </a>

                            </li>


                            

                            <li
                                class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">

                                <a
                                    class="page-link"
                                    href="?show=1&page=<?= max(1, $currentPage - 1) ?>">

                                    Önceki

                                </a>

                            </li>


                            <?php

                            $startPage =
                                max(
                                    1,
                                    $currentPage - 3
                                );


                            $endPage =
                                min(
                                    $lastPage,
                                    $currentPage + 3
                                );

                            ?>


                            <?php if ($startPage > 1): ?>


                                <li class="page-item">

                                    <a
                                        class="page-link"
                                        href="?show=1&page=1">

                                        1

                                    </a>

                                </li>


                                <?php if ($startPage > 2): ?>


                                    <li class="page-item disabled">

                                        <span class="page-link">
                                            ...
                                        </span>

                                    </li>


                                <?php endif; ?>


                            <?php endif; ?>


                            <?php

                            for (
                                $i = $startPage;
                                $i <= $endPage;
                                $i++
                            ):

                            ?>


                                <li
                                    class="page-item <?= $i == $currentPage ? 'active' : '' ?>">

                                    <a
                                        class="page-link"
                                        href="?show=1&page=<?= $i ?>">

                                        <?= $i ?>

                                    </a>

                                </li>


                            <?php endfor; ?>


                            <?php if ($endPage < $lastPage): ?>


                                <?php if ($endPage < $lastPage - 1): ?>


                                    <li class="page-item disabled">

                                        <span class="page-link">
                                            ...
                                        </span>

                                    </li>


                                <?php endif; ?>


                                <li class="page-item">

                                    <a
                                        class="page-link"
                                        href="?show=1&page=<?= $lastPage ?>">

                                        <?= $lastPage ?>

                                    </a>

                                </li>


                            <?php endif; ?>


                            

                            <li
                                class="page-item <?= $currentPage >= $lastPage ? 'disabled' : '' ?>">

                                <a
                                    class="page-link"
                                    href="?show=1&page=<?= min($lastPage, $currentPage + 1) ?>">

                                    Sonraki

                                </a>

                            </li>


                            

                            <li
                                class="page-item <?= $currentPage >= $lastPage ? 'disabled' : '' ?>">

                                <a
                                    class="page-link"
                                    href="?show=1&page=<?= $lastPage ?>">

                                    »

                                </a>

                            </li>


                        </ul>

                    </nav>

                </div>


                <div class="page-info">

                    Sayfa

                    <strong>
                        <?= $currentPage ?>
                    </strong>

                    /

                    <strong>
                        <?= $lastPage ?>
                    </strong>

                    —

                    Toplam

                    <strong>
                        <?= $total ?>
                    </strong>

                    işlem

                </div>


            <?php endif; ?>


        <?php else: ?>


            <div class="alert alert-warning mb-0">

                Arama kriterlerine uygun işlem bulunamadı.

            </div>


        <?php endif; ?>


    </div>


<?php

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


    curl_close(
        $ch
    );


    return $response;

}

?>

</body>

</html>