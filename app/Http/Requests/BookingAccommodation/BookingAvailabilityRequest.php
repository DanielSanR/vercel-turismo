<?php

namespace App\Http\Requests\BookingAccommodation;

use Illuminate\Foundation\Http\FormRequest;

class BookingAvailabilityRequest extends FormRequest
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
            'adults' => 'required|numeric',
            'minors' => 'required|numeric',
            'dateFrom' => 'required',
            'dateTo' => 'required',
            'installation_id' => 'required|integer',
            'quantityInstallations' => 'required|integer',
        ];
    }
}