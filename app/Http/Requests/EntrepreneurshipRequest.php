<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntrepreneurshipRequest extends FormRequest
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
            'name' => 'string|required',
            'email' => 'required|email',
            'address' => 'string|required',
            'phone' => 'string|required',
            'locality' => 'string|required',
            'department' => 'string|required',
            'coordinates' => 'required',
            'accommodation' => 'required|string',        
        ];
    }
}
