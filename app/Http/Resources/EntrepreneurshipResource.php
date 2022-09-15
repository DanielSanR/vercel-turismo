<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class EntrepreneurshipResource extends JsonResource
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
            'email' => $this->email,
            'address' => $this->address,
            'phone' => $this->phone, 
            'locality' => $this->locality,
            'department' => $this->department,
            'coordinates' => $this->coordinates,
            'accommodation' => $this->accommodation,
            'users' => UserResource::collection($this->whenLoaded('users')),
            'employees' => EmployeeResource::collection($this->whenLoaded('employees')),
            'workdays' => WorkdayResource::collection($this->whenLoaded('workdays')),
            'localServices' => LocalServiceResource::collection($this->whenLoaded('localServices')),
            'optionalServices' => OptionalServiceResource::collection($this->whenLoaded('optionalServices')),
        ];
        
    }

    public function with($request)
    {
        return [
            'ok' => true,
        ];
    }
}
