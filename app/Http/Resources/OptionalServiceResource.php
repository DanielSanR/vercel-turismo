<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OptionalServiceResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'icon' => $this->icon,
            'quantityOptionalServicesDetail' => $this->whenPivotLoaded('detailables', function() {
                return $this->pivot->quantity;
            }),
        ];
    }

    public function with($request)
    {
        return [
            'ok' => true,
        ];
    }
}
