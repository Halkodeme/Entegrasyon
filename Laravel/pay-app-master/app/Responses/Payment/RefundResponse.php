<?php

namespace App\Responses\Payment;

use App\Responses\Common\BaseResponse;
use App\Responses\IResponse;

class RefundResponse extends BaseResponse  implements IResponse
{
    /**
     * @var string|null
     */
    private ?string $order_no = null;

    /**
     * @var string|null
     */
    private ?string $invoice_id = null;

    /**
     * @var string|null
     */
    private ?string $ref_no = null;

    /**
     * @var string|null
     */
    private ?string $ref_number = null;

    /**
     * @return string
     */
    public function getOrderNo(): ?string
    {
        return $this->order_no;
    }

    /**
     * @param string $order_no
     */
    public function setOrderNo(?string $order_no): void
    {
        $this->order_no = $order_no;
    }

    /**
     * @return string
     */
    public function getInvoiceId(): ?string
    {
        return $this->invoice_id;
    }

    /**
     * @param string $invoice_id
     */
    public function setInvoiceId(?string $invoice_id): void
    {
        $this->invoice_id = $invoice_id;
    }

    /**
     * @return string
     */
    public function getRefNo(): ?string
    {
        return $this->ref_no;
    }

    /**
     * @param string $ref_no
     */
    public function setRefNo(?string $ref_no): void
    {
        $this->ref_no = $ref_no;
    }

    /**
     * @return string
     */
    public function getRefNumber(): ?string
    {
        return $this->ref_number;
    }

    /**
     * @param string $ref_number
     */
    public function setRefNumber(?string $ref_number): void
    {
        $this->ref_number = $ref_number;
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
