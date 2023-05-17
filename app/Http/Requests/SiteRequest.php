<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SiteRequest extends FormRequest
{
   
    
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'url' => 'required|url',
            'depth' => 'required|numeric|min:1',
            'update' => '',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
