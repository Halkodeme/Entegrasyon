<?php

namespace App\Helpers;

use App\Requests\Payment\ItemRequestModel;


class RequestHelper
{
    /**
     * @param $payment3dRequest
     * @param $data
     * @return void
     */
    public static function payment3dRequest($payment3dRequest,$data)
    {
        self::extracted($payment3dRequest, $data);
        $payment3dRequest->setReturnUrl(route('success'));
        $payment3dRequest->setCancelUrl(route('error'));
    }

    /**
     * @param $payment2dRequest
     * @param $data
     * @return void
     */
    public static function payment2dRequest($payment2dRequest,$data)
    {
        $payment2dRequest->setCvv($data['cvv']);
        self::extracted($payment2dRequest, $data);
    }

    /**
     * @param $request
     * @param $data
     * @return void
     */
    public static function extracted($request, $data): void
    {

        $request->setCcHolderName($data['cc_holder_name']);
        $request->setCcNo($data['cc_no']);
        $request->setExpiryMonth($data['expiry_month']);
        $request->setExpiryYear($data['expiry_year']);
        $request->setMerchantKey(getenv('MERCHANT_KEY'));
        $request->setCurrencyCode(getenv('CURRENCY_CODE'));
        $request->setInvoiceDescription(getenv('INVOICE_DESCRIPTION'));
        $request->setName(CommonHelper::nameSplit($data['cc_holder_name'])['firstName']);
        $request->setSurname(CommonHelper::nameSplit($data['cc_holder_name'])['lastName']);
        $request->setTotal((float)$data['total']);
        $request->setInstallmentsNumber($data['installments_number']);
        $request->setHashKey(HashGeneratorHelper::hashGenerator((float)$data['total'], $data['installments_number'],$data['invoice_id']));
        $request->setInvoiceId($data['invoice_id']);
    }

    /**
     * @param $request
     * @param $data
     * @return void
     */
    public static function getPosRequest($request,$data)
    {
        $request->setCreditCard($data['credit_card']);
        $request->setAmount($data['amount']);
        $request->setCurrencyCode(getenv('CURRENCY_CODE'));
        $request->setMerchantKey(getenv('MERCHANT_KEY'));

    }

    public static function getRefundRequest($request,$data)
    {
        $request->setInvoiceId($data['invoice_id']);
        $request->setAmount($data['amount']);
        $request->setAppId(getenv('APP_ID'));
        $request->setAppSecret(getenv('APP_SECRET'));
        $request->setMerchantKey(getenv('MERCHANT_KEY'));
        $request->setHashKey(HashGeneratorHelper::refundHashGenerator($data['amount'],$data['invoice_id']));
    }

    public static function getCheckStatusRequest($request,$data)
    {
        $request->setInvoiceId($data['invoice_id']);
        $request->setMerchantKey(getenv('MERCHANT_KEY'));
    }

    public static function getPurchaseLinkRequest($request,$data)
    {
        $request->setInvoiceId($data['invoice_id']);
        $request->setMerchantKey(getenv('MERCHANT_KEY'));
        $request->setInvoiceDescription($data['invoice_description']);
        $request->setTotal((float)$data['total']);
        $request->setName($data['name']);
        $request->setSurname($data['surname']);
        $request->setCurrencyCode(getenv('CURRENCY_CODE'));
        $request->setReturnUrl(route('success'));
        $request->setCancelUrl(route('error'));

        $item = new ItemRequestModel();
        $item->setName( "Ödeme İşlemi");
        $item->setPrice((float)$data['total']);
        $item->setQuantity(1);
        $item->setDescription($data['invoice_description']);

        $request->setItems([$item]);
    }

    public static function getConfirmPaymentRequest($request,$data)
    {
        $request->setInvoiceId($data['invoice_id']);
        $request->setMerchantKey(getenv('MERCHANT_KEY'));
        $request->setTotal((float)$data['total']);
        $request->setStatus("1");
        $request->setHashKey(HashGeneratorHelper::confirmPaymentHashGenerator($data['invoice_id'],"1"));

    }
}
