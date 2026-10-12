<?php
namespace App\Requests\Payment;

class ConfirmPaymentRequestModel
{
    /**
     * @var string
     */
    private string $total;

    /**
     * @var string
     */
    private string $invoice_id;

    /**
     * @var string
     */
    private string $status;

    /**
     * @var string
     */
    private string $merchant_key;

    /**
     * @var string
     */
    private string $hash_key;

    /**
     * @return string
     */
    public function getTotal(): string
    {
        return $this->total;
    }

    /**
     * @param string $total
     * @return void
     */
    public function setTotal(string $total): void
    {
        $this->total = $total;
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
     * @return void
     */
    public function setInvoiceId(string $invoice_id): void
    {
        $this->invoice_id = $invoice_id;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return void
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getMerchantKey(): string
    {
        return $this->merchant_key;
    }

    /**
     * @param string $merchant_key
     * @return void
     */
    public function setMerchantKey(string $merchant_key): void
    {
        $this->merchant_key = $merchant_key;
    }

    /**
     * @return string
     */
    public function getHashKey(): string
    {
        return $this->hash_key;
    }

    /**
     * @param string $hash_key
     * @return void
     */
    public function setHashKey(string $hash_key): void
    {
        $this->hash_key = $hash_key;
    }

    public function getData()
    {
        $data = [
            'total' => $this->getTotal(),
            'invoice_id' => $this->getInvoiceId(),
            'status' => $this->getStatus(),
            'merchant_key' => $this->getMerchantKey(),
            'hash_key' => $this->getHashKey(),
        ];

        return json_encode($data);
    }
}
