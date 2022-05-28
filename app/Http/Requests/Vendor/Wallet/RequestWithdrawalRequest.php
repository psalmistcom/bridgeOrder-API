<?php

namespace App\Http\Requests\Vendor\Wallet;

use Illuminate\Foundation\Http\FormRequest;

class RequestWithdrawalRequest extends FormRequest
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
            'code' => 'required|digits:6',
            'otp_verification_id' => 'required|integer',
            'amount' => 'required|numeric|min:1',
        ];
    }
}
