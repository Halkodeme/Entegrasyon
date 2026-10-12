<?php

namespace App\Responses\Payment\Payment2d;

use App\Responses\IResponse;
use App\Responses\Common\BaseResponse;

class GetPayment2dResponse extends BaseResponse implements IResponse
{
    private ?Payment2dResponse $data = null;

    public function getData(): ?Payment2dResponse
    {
        return $this->data;
    }

    public function setData(?Payment2dResponse $data): void
    {
        $this->data = $data;
    }

    public function toJson(): string|false
    {
        return json_encode($this->toArray());
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $params = [
            'status_code'        => $this->getStatusCode(),
            'status_description' => $this->getStatusDescription(),
        ];

        if ($this->data !== null) {
            $params['data'] = $this->data->toArray();
        }

        return $params;
    }
}