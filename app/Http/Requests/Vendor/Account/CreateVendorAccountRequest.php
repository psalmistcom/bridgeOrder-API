<?php

namespace App\Http\Requests\Vendor\Account;

use Illuminate\Foundation\Http\FormRequest;

class CreateVendorAccountRequest extends FormRequest
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
            'full_name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:vendors|max:255', // dynamically check if it exists in the same restaurant
            'role_id' => 'required|integer|exists:roles,id',
        ];
    }
}
