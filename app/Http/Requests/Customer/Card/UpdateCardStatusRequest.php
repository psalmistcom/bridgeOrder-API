<?php

namespace App\Http\Requests\Customer\Card;

use App\Enum\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCardStatusRequest extends FormRequest
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
            'card_id' => 'required|numeric|exists:payment_cards,id',
            'status' => ['required', 'string',  Rule::in(Status::ACTIVE->value, Status::INACTIVE->value)]
        ];
    }
}
