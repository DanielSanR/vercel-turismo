<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashflowRequest extends FormRequest
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
            'detail' => 'required|string',
            'amount' => 'required|numeric',
            'type' => 'required|string',
            'booking_id' => 'integer|nullable',
            'entrepreneurship_id' => 'required|integer',
        ];
    }
}
