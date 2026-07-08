<?php
namespace App\Requests\Payment;

class RefundRequestModel
{

    /**
     * @var string
     */
    private string $invoice_id;

    /**
     * @var string
     */
    private string $amount;

    /**
     * @var string
     */
    private string $app_id;

    /**
     * @var string
     */
    private string $app_secret;

    /**
     * @var string
     */
    private string $hash_key;

    /**
     * @var string
     */
    private string $merchant_key;

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

    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     */
    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getAppId(): string
    {
        return $this->app_id;
    }

    /**
     * @param string $app_id
     */
    public function setAppId(string $app_id): void
    {
        $this->app_id = $app_id;
    }

    /**
     * @return string
     */
    public function getAppSecret(): string
    {
        return $this->app_secret;
    }

    /**
     * @param string $app_secret
     */
    public function setAppSecret(string $app_secret): void
    {
        $this->app_secret = $app_secret;
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
     */
    public function setHashKey(string $hash_key): void
    {
        $this->hash_key = $hash_key;
    }

    public function getData()
    {
        $data = [
            'invoice_id' => $this->getInvoiceId(),
            'amount' => $this->getAmount(),
            'app_id' => $this->getAppId(),
            'app_secret' => $this->getAppSecret(),
            'merchant_key' => $this->getMerchantKey(),
            'hash_key' => $this->getHashKey(),
        ];

        return json_encode($data);
    }
}
