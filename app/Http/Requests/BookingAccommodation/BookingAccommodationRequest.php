<?php

namespace App\Http\Requests\BookingAccommodation;

use Illuminate\Foundation\Http\FormRequest;

class BookingAccommodationRequest extends FormRequest
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
            'adults' => 'required|numeric',
            'minors' => 'required|numeric',
            'dateFrom' => 'required|date',
            'dateTo' => 'required|date',
            'client_id' => 'required|integer',
            'installation_id' => 'required|integer',
            'quantityInstallations' => 'required|integer',
            'dataOptionalServices' => 'array',
            'dataOptionalServices.*.id' => 'integer',
            'dataOptionalServices.*.quantity' => 'numeric',
        ];
    }
}
