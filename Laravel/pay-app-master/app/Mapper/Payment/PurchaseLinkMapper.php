<?php

namespace App\Mapper\Payment;

use App\Responses\IResponse;
use App\Responses\Payment\PurchaseLinkResponse;
use Psr\Http\Message\ResponseInterface;

class PurchaseLinkMapper
{
    public static function map(ResponseInterface $response): IResponse
    {
        $responseData = json_decode($response->getBody(), true);
        $model = new PurchaseLinkResponse();

        $model->setStatus((bool)($responseData['status'] ?? false));
        $model->setStatusCode($responseData['status_code'] ?? 0);
        $model->setSuccessMessage($responseData['success_message'] ?? null);
        $model->setLink($responseData['link'] ?? null);
        $model->setOrderId($responseData['order_id'] ?? null);

        return $model;
    }
}
