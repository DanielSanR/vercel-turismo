<?php

namespace App\Http\Requests\DetailBooking;

use Illuminate\Foundation\Http\FormRequest;

class addOptionalServicesDetailRequest extends FormRequest
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
            'optionalServices' => 'array',
            'optionalServices.*.id' => 'required|integer',
            'optionalServices.*.quantity' => 'required|integer',
        ];
    }
}
