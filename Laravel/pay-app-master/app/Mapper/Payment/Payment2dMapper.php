<?php

namespace App\Mapper\Payment;

use App\Responses\IResponse;
use App\Responses\Payment\Payment2d\GetPayment2dResponse;
use App\Responses\Payment\Payment2d\Payment2dResponse;
use Psr\Http\Message\ResponseInterface;

class Payment2dMapper
{
    public static function map(ResponseInterface $response): IResponse
    {
        $getPayment2dResponse = new GetPayment2dResponse();
        $responseData = json_decode($response->getBody(), true);

        $getPayment2dResponse->setStatusCode($responseData['status_code'] ?? null);
        $getPayment2dResponse->setStatusDescription($responseData['status_description'] ?? '');

        if (isset($responseData['data'])) {
            $payment2dResponse = new Payment2dResponse();
            $d = $responseData['data'];

            $payment2dResponse->setOrderNo($d['order_no'] ?? '');
            $payment2dResponse->setOrderId($d['order_id'] ?? '');
            $payment2dResponse->setInvoiceId($d['invoice_id'] ?? '');
            $payment2dResponse->setCreditCardNo($d['credit_card_no'] ?? '');
            $payment2dResponse->setTransactionType($d['transaction_type'] ?? '');
            $payment2dResponse->setPaymentStatus($d['payment_status'] ?? 0);
            $payment2dResponse->setPaymentMethod($d['payment_method'] ?? 1);
            $payment2dResponse->setErrorCode($d['error_code'] ?? ($responseData['status_code'] ?? null));
            $payment2dResponse->setError($d['error'] ?? ($responseData['status_description'] ?? ''));
            $payment2dResponse->setHashKey($d['hash_key'] ?? '');

            $payment2dResponse->setAuthCode($d['auth_code'] ?? '');
            $payment2dResponse->setMerchantCommission($d['merchant_commission'] ?? 0);
            $payment2dResponse->setUserCommission($d['user_commission'] ?? 0);
            $payment2dResponse->setMerchantCommissionPercentage($d['merchant_commission_percentage'] ?? 0);
            $payment2dResponse->setMerchantCommissionFixed($d['merchant_commission_fixed'] ?? 0);
            $payment2dResponse->setOriginalBankErrorCode($d['original_bank_error_code'] ?? '');
            $payment2dResponse->setOriginalBankErrorDescription($d['original_bank_error_description'] ?? '');

            $getPayment2dResponse->setData($payment2dResponse);
        } else {
            $getPayment2dResponse->setData(null);
        }

        return $getPayment2dResponse;
    }
}
