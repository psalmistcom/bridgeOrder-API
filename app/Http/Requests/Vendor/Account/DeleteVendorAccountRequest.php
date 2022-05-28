<?php

namespace App\Http\Requests\Vendor\Account;

use Illuminate\Foundation\Http\FormRequest;

class DeleteVendorAccountRequest extends FormRequest
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
            'vendor_account.*' => 'required|array|exists:vendors,id',
        ];
    }
}
