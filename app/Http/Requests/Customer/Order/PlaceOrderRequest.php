<?php

namespace App\Http\Requests\Customer\Order;

use App\Enum\OrderType;
use App\Enum\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
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
            'restaurant_id' => 'required|exists:restaurants,id',
            'order_type'
            => 'required|in:'
                . OrderType::PICKUP->value . ','
                . OrderType::TABLE->value,
            'payment_method'
            => 'required|in:'
                . PaymentMethod::BRIDGE_WALLET->value . ','
                . PaymentMethod::BRIDGE_CARD->value,
            'items' => 'required|array',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|numeric',
            'items.*.variant_id' => 'nullable|exists:variants,id',
        ];
    }
}
