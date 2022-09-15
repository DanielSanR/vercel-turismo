<?php

namespace App\Http\Requests\DetailBooking;

use Illuminate\Foundation\Http\FormRequest;

class updateOptionalServicesDetailRequest extends FormRequest
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
            'booking_id' => 'requerid|integer',
            'optionalservice_id' => 'required|integer',
            'quantity' => 'required|integer',
        ];
    }
}
