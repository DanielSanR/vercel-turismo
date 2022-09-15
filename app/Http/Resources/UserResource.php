<?php

namespace App\Http\Resources;

use App\Http\Resources\EntrepreneurshipResource;
use App\Models\Entrepreneurship;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'username' => $this->username,
            'email' => $this->email,
            'image_path' => $this->image_path,
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
