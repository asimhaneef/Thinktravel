<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMemberRequest extends FormRequest
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
        return [
            //
            'phone_no' => [
                'required',
                'string',
                'max:25',
                Rule::unique('members', 'phone_no')->ignore($this->route('member')),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('members', 'email')->ignore($this->route('member')),
            ],
        ];
    }
}
