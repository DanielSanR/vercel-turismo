<?php

namespace App\Http\Requests\BookingWithoutAccommodation;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
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
            'client_id' => 'required|integer',
            'guests' => 'array',
            'quantityInstallations' => 'required|integer',
        ];
    }
}
