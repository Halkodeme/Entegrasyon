<?php

namespace App\Responses\Payment;

use App\Responses\Common\BaseResponse;
use App\Responses\IResponse;

class CheckStatusResponse extends BaseResponse implements IResponse
{
    private ?string $transaction_status = null;
    private ?string $order_id = null;
    private ?string $transaction_id = null;
    private ?string $reason = null;
    private ?string $bank_status_code = null;
    private ?string $bank_status_description = null;
    private ?string $invoice_id = null;
    private ?float $total_refunded_amount = null;
    private ?string $product_price = null;
    private ?float $transaction_amount = null;
    private ?string $ref_number = null;
    private ?string $transaction_type = null;
    private ?string $original_bank_error_code = null;
    private ?string $original_bank_error_description = null;
    private ?string $merchant_commission = null;
    private ?string $user_commission = null;
    private ?string $settlement_date = null;

    // Getters
    public function getTransactionStatus(): ?string
    {
        return $this->transaction_status;
    }

    public function getOrderId(): ?string
    {
        return $this->order_id;
    }

    public function getTransactionId(): ?string
    {
        return $this->transaction_id;
    }

    public function getInvoiceId(): ?string
    {
        return $this->invoice_id;
    }

    public function getTotalRefundedAmount(): ?float
    {
        return $this->total_refunded_amount;
    }

    public function getTransactionAmount(): ?float
    {
        return $this->transaction_amount;
    }

    public function getTransactionType(): ?string
    {
        return $this->transaction_type;
    }

    public function getSettlementDate(): ?string
    {
        return $this->settlement_date;
    }

    public function getMerchantCommission(): ?string
    {
        return $this->merchant_commission;
    }

    // Setters
    public function setTransactionStatus(?string $status): void
    {
        $this->transaction_status = $status;
    }

    public function setOrderId(?string $id): void
    {
        $this->order_id = $id;
    }

    public function setTransactionId(?string $id): void
    {
        $this->transaction_id = $id;
    }

    public function setReason(?string $reason): void
    {
        $this->reason = $reason;
    }

    public function setBankStatusCode(?string $code): void
    {
        $this->bank_status_code = $code;
    }

    public function setBankStatusDescription(?string $desc): void
    {
        $this->bank_status_description = $desc;
    }

    public function setInvoiceId(?string $id): void
    {
        $this->invoice_id = $id;
    }

    public function setTotalRefundedAmount(?float $amount): void
    {
        $this->total_refunded_amount = $amount;
    }

    public function setProductPrice(?string $price): void
    {
        $this->product_price = $price;
    }

    public function setTransactionAmount(?float $amount): void
    {
        $this->transaction_amount = $amount;
    }

    public function setRefNumber(?string $ref): void
    {
        $this->ref_number = $ref;
    }

    public function setTransactionType(?string $type): void
    {
        $this->transaction_type = $type;
    }

    public function setOriginalBankErrorCode(?string $code): void
    {
        $this->original_bank_error_code = $code;
    }

    public function setOriginalBankErrorDescription(?string $desc): void
    {
        $this->original_bank_error_description = $desc;
    }

    public function setMerchantCommission(?string $comm): void
    {
        $this->merchant_commission = $comm;
    }

    public function setUserCommission(?string $comm): void
    {
        $this->user_commission = $comm;
    }

    public function setSettlementDate(?string $date): void
    {
        $this->settlement_date = $date;
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
