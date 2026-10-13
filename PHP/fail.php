<?php require_once __DIR__ . '/security.php';  ?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İşlem Sonucu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <?php include 'style.php'; ?>
</head>

<body>

    
    <?php include 'nav.php'; ?>

    
    <div class="container mt-5">
        <h1>İşlem Başarısız!</h1>

        <div class="result-item">
            <span>order_no:</span> <?php echo htmlspecialchars((string)($_GET['order_no'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <div class="result-item">
            <span>order_id:</span> <?php echo htmlspecialchars((string)($_GET['order_id'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <div class="result-item">
            <span>invoice_id:</span> <?php echo htmlspecialchars((string)($_GET['invoice_id'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <div class="result-item">
            <span>status_code:</span> <?php echo htmlspecialchars((string)($_GET['status_code'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <div class="result-item">
            <span>transaction_type:</span> <?php echo htmlspecialchars((string)($_GET['transaction_type'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <div class="result-item">
            <span>payment_status:</span> <?php echo htmlspecialchars((string)($_GET['payment_status'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <div class="result-item">
            <span>md_status:</span> <?php echo htmlspecialchars((string)($_GET['md_status'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
        </div>
    </div>

    
    <footer>
        
    <?php include 'footer.php'; ?>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>