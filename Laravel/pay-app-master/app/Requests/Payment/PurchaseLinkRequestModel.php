<?php
namespace App\Requests\Payment;


class PurchaseLinkRequestModel
{
    /**
     * @var string
     */
    private string $merchant_key;

    /**
     * @var string
     */
    private string $currency_code;

    /**
     * @var string
     */
    private string $invoice_id;

    /**
     * @var string
     */
    private string $invoice_description;

    /**
     * @var string
     */
    private string $total;

    /**
     * @var string
     */
    private string $return_url;

    /**
     * @var string
     */
    private string $cancel_url;

    /**
     * @var ItemRequestModel[]
     */
    private array $items;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $surname;


    public function getMerchantKey(): string
    {
        return $this->merchant_key;
    }

    public function setMerchantKey(string $merchant_key): void
    {
        $this->merchant_key = $merchant_key;
    }

    public function getCurrencyCode(): string
    {
        return $this->currency_code;
    }

    public function setCurrencyCode(string $currency_code): void
    {
        $this->currency_code = $currency_code;
    }

    public function getInvoiceId(): string
    {
        return $this->invoice_id;
    }

    public function setInvoiceId(string $invoice_id): void
    {
        $this->invoice_id = $invoice_id;
    }

    public function getInvoiceDescription(): string
    {
        return $this->invoice_description;
    }

    public function setInvoiceDescription(string $invoice_description): void
    {
        $this->invoice_description = $invoice_description;
    }

    public function getTotal(): string
    {
        return $this->total;
    }

    public function setTotal(string $total): void
    {
        $this->total = $total;
    }

    public function getReturnUrl(): string
    {
        return $this->return_url;
    }

    public function setReturnUrl(string $return_url): void
    {
        $this->return_url = $return_url;
    }

    public function getCancelUrl(): string
    {
        return $this->cancel_url;
    }

    public function setCancelUrl(string $cancel_url): void
    {
        $this->cancel_url = $cancel_url;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     * @return void
     */
    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }


    public static function finalizePurchaseLinkData(PurchaseLinkRequestModel $model): array
    {
        $invoiceData = [
            'invoice_id' => $model->getInvoiceId(),
            'invoice_description' => $model->getInvoiceDescription(),
            'total' => (float)$model->getTotal(),
            'return_url' => $model->getReturnUrl(),
            'cancel_url' => $model->getCancelUrl(),
            'items' => array_map(fn($item) => $item->toArray(), $model->getItems())
        ];

        return [
            'merchant_key' => $model->getMerchantKey(),
            'currency_code' => $model->getCurrencyCode(),
            'invoice' => json_encode($invoiceData),
            'name' => $model->getName(),
            'surname' => $model->getSurname(),
        ];
    }

}
