<?php

namespace App\Http\Requests\BookingWithoutAccommodation;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phoneContact' => 'required|string|max:10',
            'adults' => 'required|integer',
            'minors' => 'required|integer',
            'dateFrom' => 'required|date',
            'dateTo' => 'required|date',      
            'client.firstName' => 'required|string',
            'client.lastName' => 'required|string',
            'client.dni' => 'required|string',
            'client.dateBirth' => 'required|date',
            'client.reason' => 'required|string',
            'client.departureLocality' => 'required|string',
            'client.residenceLocality' => 'required|string',
            'client.entrepreneurship_id' => 'required|integer',
            'guests' => 'array',
            'guests.*.firstName' => 'required|string',
            'guests.*.lastName' => 'required|string',
            'guests.*.dni' => 'required|string',
            'guests.*.dateBirth' => 'required|date',
            'guests.*.reason' => 'required|string',
            'guests.*.departureLocality' => 'required|string',
            'guests.*.residenceLocality' => 'required|string',
            'guests.*.entrepreneurship_id' => 'required|integer',
        ];
    }
}
