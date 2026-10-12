<?php

namespace App\Mapper\Payment;

use App\Responses\IResponse;
use App\Responses\Payment\CheckStatusResponse;
use Psr\Http\Message\ResponseInterface;

class CheckStatusMapper
{
    public static function map(ResponseInterface $response): IResponse
    {
        $checkStatusResponse = new CheckStatusResponse();
        $responseData = json_decode($response->getBody(), true);

        $checkStatusResponse->setStatusCode($responseData['status_code'] ?? null);
        $checkStatusResponse->setStatusDescription($responseData['status_description'] ?? '');

        $checkStatusResponse->setTransactionStatus($responseData['transaction_status'] ?? null);
        $checkStatusResponse->setOrderId($responseData['order_id'] ?? null);
        $checkStatusResponse->setTransactionId($responseData['transaction_id'] ?? null);
        $checkStatusResponse->setReason($responseData['reason'] ?? null);
        $checkStatusResponse->setBankStatusCode($responseData['bank_status_code'] ?? null);
        $checkStatusResponse->setBankStatusDescription($responseData['bank_status_description'] ?? null);
        $checkStatusResponse->setInvoiceId($responseData['invoice_id'] ?? null);

        $checkStatusResponse->setTotalRefundedAmount((float)($responseData['total_refunded_amount'] ?? 0));
        $checkStatusResponse->setProductPrice($responseData['product_price'] ?? null);
        $checkStatusResponse->setTransactionAmount((float)($responseData['transaction_amount'] ?? 0));

        $checkStatusResponse->setRefNumber($responseData['ref_number'] ?? null);
        $checkStatusResponse->setTransactionType($responseData['transaction_type'] ?? null);
        $checkStatusResponse->setOriginalBankErrorCode($responseData['original_bank_error_code'] ?? null);
        $checkStatusResponse->setOriginalBankErrorDescription($responseData['original_bank_error_description'] ?? null);

        $checkStatusResponse->setMerchantCommission($responseData['merchant_commission'] ?? null);
        $checkStatusResponse->setUserCommission($responseData['user_commission'] ?? null);
        $checkStatusResponse->setSettlementDate($responseData['settlement_date'] ?? null);

        return $checkStatusResponse;
    }
}
