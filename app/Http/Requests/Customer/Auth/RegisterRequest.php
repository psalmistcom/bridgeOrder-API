<?php

namespace App\Http\Requests\Customer\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            'full_name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => ['required', Password::min(7)
                ->mixedCase()
                ->letters()
                ->numbers()
            ],
            'latitude' => ['sometimes', 'nullable', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'longitude' => [
                'sometimes', 'nullable', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'latitude.regex' => ':attribute appears to be incorrect format',
            'longitude.regex' => ':attribute appears to be incorrect format'
        ];
    }
}
