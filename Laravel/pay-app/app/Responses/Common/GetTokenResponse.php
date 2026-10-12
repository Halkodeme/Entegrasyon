<?php

namespace App\Responses\Common;

use App\Responses\IResponse;

class GetTokenResponse extends BaseResponse implements IResponse
{
    private ?TokenResponse $data = null;

    public function getData(): ?TokenResponse
    {
        return $this->data;
    }

    public function setData(?TokenResponse $data): void
    {
        $this->data = $data;
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
