<?php


namespace App\Requests\Payment;

class GetCheckStatusRequestModel
{

    /**
     * @var string
     */
    private string $invoice_id;
    /**
     * @var string
     */
    private string $merchant_key;

    /**
     * @return string
     */
    public function getMerchantKey(): string
    {
        return $this->merchant_key;
    }

    /**
     * @param string $merchant_key
     */
    public function setMerchantKey(string $merchant_key): void
    {
        $this->merchant_key = $merchant_key;
    }


    /**
     * @return string
     */
    public function getInvoiceId(): string
    {
        return $this->invoice_id;
    }

    /**
     * @param string $invoice_id
     */
    public function setInvoiceId(string $invoice_id): void
    {
        $this->invoice_id = $invoice_id;
    }

    public function getData()
    {
        $data = [
            'invoice_id' => $this->getInvoiceId(),
            'merchant_key' => $this->getMerchantKey(),
        ];

        return json_encode($data);
    }
}
