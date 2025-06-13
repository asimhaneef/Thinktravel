<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProvinceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $Id = $this->get('id');

        return [
            'province_name' => [
                'required',
                'max:255',
                $Id ? Rule::unique('provinces')->ignore($Id) : 'unique:provinces',
            ],
            'province_code' => [
                'required',
                'max:255',
                $Id ? Rule::unique('provinces')->ignore($Id) : 'unique:provinces',
            ],
            'country_id' => [
                'required'
            ],
        ];
    }
}
