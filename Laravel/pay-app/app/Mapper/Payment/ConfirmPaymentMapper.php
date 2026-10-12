<?php

namespace App\Mapper\Payment;

use App\Responses\IResponse;
use App\Responses\Payment\ConfirmPaymentResponse;
use Psr\Http\Message\ResponseInterface;

class ConfirmPaymentMapper
{
    public static function map(ResponseInterface $response): IResponse
    {
        $responseData = json_decode($response->getBody(), true);
        $model = new ConfirmPaymentResponse();

        $model->setStatusCode(($responseData['status_code'] ?? 0));
        $model->setStatusDescription($responseData['status_description'] ?? null);
        $model->setTransactionStatus($responseData['transaction_status'] ?? null);
        $model->setOrderId($responseData['order_id'] ?? null);
        $model->setInvoiceId($responseData['invoice_id'] ?? null);

        return $model;
    }
}
