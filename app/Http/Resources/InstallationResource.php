<?php

namespace App\Http\Resources;

use App\Http\Resources\LocalServiceResource;
use App\Http\Resources\EntrepreneurshipResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InstallationResource extends JsonResource
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
            'category' => $this->category,
            'description' => $this->description,
            'capacity' => $this->capacity,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'image_path' => $this->image_path,
            'entrepreneurship' => new EntrepreneurshipResource($this->whenLoaded('entrepreneurship')),
            'localServices' => LocalServiceResource::collection($this->whenLoaded('localServices')),
            'quantityInstallationsDetail' => $this->whenPivotLoaded('detailables', function() {
                return $this->pivot->quantity;
            }),
        ];
    }
}