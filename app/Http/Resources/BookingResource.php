<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'id' => $this->id ?? '',
            'phoneContact' => $this->phone_contact,
            'adults' => $this->adults ?? '',
            'minors' => $this->minors ?? '',
            'dateFrom' => date('d-m-Y', strtotime($this->date_from)),
            'dateTo' => date('d-m-Y', strtotime($this->date_to)),
            'checkinDate' => $this->checkin_date !== null ? date('d-m-Y H:i:s', strtotime($this->checkin_date)) : '',
            'checkoutDate' => $this->checkout_date !== null ? date('d-m-Y H:i:s', strtotime($this->checkout_date)) : '',
            'amount' => $this->amount ?? '',
            'observations' => ObservationResource::collection($this->whenLoaded('observations')),
            'checkinEmployee' => [
                'id'=> $this->employeeCheckin->id ?? '',
                'fullname' => $this->employeeCheckin->fullName ?? ''
            ],
            'checkoutEmployee' => [
                'id' => $this->employeeCheckout->id ?? '',
                'fullname' => $this->employeeCheckout->fullName ?? '',
            ],
            'client' => new ClientResource($this->whenLoaded('client')),
            'guests' => ClientResource::collection($this->whenLoaded('guests')),
            'optionalServices' => OptionalServiceResource::collection($this->whenLoaded('optionalServices')),
            'installations' => InstallationResource::collection($this->whenLoaded('installations')),
            'extras' => ExtraResource::collection($this->whenLoaded('extras')),
            'payments' => CashflowResource::collection($this->whenLoaded('payments')),
        ];
    }
    
    public function with($request)
    {
        return [
            'ok' => true,
        ];
    }

}
