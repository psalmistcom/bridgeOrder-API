<?php

namespace App\Http\Requests\Vendor\Auth;

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
            'email' => 'required|email|unique:vendors|max:255',
            'restaurant_name' => 'required|string|unique:restaurants,name|min:2|max:255',
            'password' => ['required', Password::min(7)
                ->mixedCase()
                ->letters()
                ->numbers()
            ],
        ];
    }
}
