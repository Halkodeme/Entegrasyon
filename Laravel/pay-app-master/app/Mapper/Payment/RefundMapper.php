<?php

namespace App\Mapper\Payment;

use App\Responses\IResponse;
use App\Responses\Payment\RefundResponse;
use Psr\Http\Message\ResponseInterface;

class RefundMapper
{
    public static function map(ResponseInterface $response): IResponse
    {
        $refundResponse = new RefundResponse();

        $responseData = json_decode($response->getBody(), true);

        $refundResponse->setStatusCode($responseData['status_code']);
        $refundResponse->setStatusDescription($responseData['status_description']);
        $refundResponse->setOrderNo($responseData['order_no'] ?? null);
        $refundResponse->setInvoiceId($responseData['invoice_id'] ?? null);
        $refundResponse->setRefNo($responseData['ref_no'] ?? null);
        $refundResponse->setRefNumber($responseData['ref_number'] ?? null);

        return $refundResponse;
    }
}
