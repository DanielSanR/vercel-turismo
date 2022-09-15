<?php

namespace App\Http\Requests\BookingAccommodation;

use Illuminate\Foundation\Http\FormRequest;

class CheckinRequest extends FormRequest
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
            'booking_id' => 'required|integer',
            'checkinDate' => 'required',
            'guests' => 'array',
            'guests.*.firstName' => 'required|string',
            'guests.*.lastName' => 'required|string',
            'guests.*.dni' => 'required|string',
            'guests.*.dateBirth' => 'required|date',
            'guests.*.reason' => 'required|string',
            'guests.*.departureLocality' => 'required|string',
            'guests.*.residenceLocality' => 'required|string',
        ];
    }
}
