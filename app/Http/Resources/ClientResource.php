<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'dni' => $this->dni,
            'date_birth' => date('d-m-Y', strtotime($this->date_birth)),
            'reason' => $this->reason,
            'departureLocality' => $this->departure_locality,
            'residenceLocality' => $this->residence_locality,
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
