<?php

namespace App\Http\Requests\Vendor\Auth\Password;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(7)
                ->mixedCase()
                ->letters()
                ->numbers()
            ],
        ];
    }
}
