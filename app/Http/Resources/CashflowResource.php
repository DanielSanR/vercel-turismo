<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CashflowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'detail' => $this->detail,
            'amount' => $this->amount,
            'type' => $this->type,
            'booking_id' => $this->booking_id ?? '',
            'entrepreneurship' => new EntrepreneurshipResource($this->whenLoaded('entrepreneurship')),
        ];
    }

    public function with($request)
    {
        return [
            'ok' => true,
        ];
    }

}
