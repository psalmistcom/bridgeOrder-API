<?php

namespace App\Http\Requests\Customer\Reservation;

use App\Enum\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;

class CreateReservationRequest extends FormRequest
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
            'restaurant_id' => 'required|exists:restaurants,id',
            'number_of_guests' => 'required|numeric',
            'date' => 'required',
            'payment_method'
            => 'required|in:'
                . PaymentMethod::BRIDGE_CARD->value . ','
                . PaymentMethod::BRIDGE_WALLET->value ,

            'check_in' => 'required',
            'reservation_type' => 'required|string',
            'special_request' => 'nullable|string',
        ];
    }
}
