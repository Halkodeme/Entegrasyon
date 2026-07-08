<?php

namespace App\Responses\Payment;

use App\Responses\IResponse;

class ConfirmPaymentResponse implements IResponse
{
    /**
     * @var integer
     */
    private int $status_code;

    /**
     * @var string|null
     */
    private ?string $status_description = null;

    /**
     * @var string|null
     */
    private ?string $transaction_status = null;

    /**
     * @var string|null
     */
    private ?string $order_id = null;

    /**
     * @var string|null
     */
    private ?string $invoice_id = null;

    public function getStatusCode(): int
    {
        return $this->status_code;
    }

    public function getStatusDescription(): ?string
    {
        return $this->status_code;
    }

    public function getSuccessMessage(): ?string
    {
        return $this->status_description;
    }

    public function getTransactionStatus(): ?string
    {
        return $this->transaction_status;
    }

    public function getOrderId(): ?string
    {
        return $this->order_id;
    }

    public function getInvoiceId(): ?string
    {
        return $this->invoice_id;
    }


    public function setStatusCode(int $statusCode): void
    {
        $this->status_code = $statusCode;
    }

    public function setStatusDescription(string $statusDescription): void
    {
        $this->status_description = $statusDescription;
    }

    public function setTransactionStatus(?string $transactionStatus): void
    {
        $this->transaction_status = $transactionStatus;
    }

    public function setOrderId(?string $id): void
    {
        $this->order_id = $id;
    }

    public function setInvoiceId(?string $id): void
    {
        $this->invoice_id = $id;
    }


    /**
     * @return false|string
     */
    public function toJson(): bool|string
    {
        return json_encode($this->toArray());
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
