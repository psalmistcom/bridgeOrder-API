<?php

namespace App\Http\Requests\Admin\Auth\Password;

use Illuminate\Foundation\Http\FormRequest;

class PasswordOtpRequest extends FormRequest
{
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
            'email' => 'required|email|exists:admins,email',
        ];
    }
}
