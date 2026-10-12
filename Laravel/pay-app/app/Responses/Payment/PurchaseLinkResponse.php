<?php

namespace App\Responses\Payment;

use App\Responses\IResponse;

class PurchaseLinkResponse implements IResponse
{
    /**
     * @var bool
     */
    private bool $status;

    /**
     * @var string|null
     */
    private ?string $success_message = null;

    /**
     * @var string|null
     */
    private ?string $status_code = null;

    /**
     * @var string|null
     */
    private ?string $link = null;

    /**
     * @var string|null
     */
    private ?string $order_id = null;

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function getStatusCode(): ?string
    {
        return $this->status_code;
    }

    public function getSuccessMessage(): ?string
    {
        return $this->success_message;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function getOrderId(): ?string
    {
        return $this->order_id;
    }


    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    public function setStatusCode(string $statusCode): void
    {
        $this->status_code = $statusCode;
    }

    public function setSuccessMessage(?string $message): void
    {
        $this->success_message = $message;
    }

    public function setLink(?string $link): void
    {
        $this->link = $link;
    }

    public function setOrderId(?string $id): void
    {
        $this->order_id = $id;
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
