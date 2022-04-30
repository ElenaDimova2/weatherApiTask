<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCityRequest extends FormRequest
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
            'name' => 'string|max:255|required',
            'state_code' => 'string|required|max:255',
            'latitude' => 'numeric|between:-90,90|required',
            'longitude' => 'numeric|between:-180,180|required'
        ];
    }

    public function messages()
    {
        return [
            'name.max' => 'The name has exceeded the limit',
            'latitude.between' => 'The latitude must be in range between -90 and 90',
            'longitude.between' => 'The longitude mus be in range between -180 and 180'
        ];
    }
}
