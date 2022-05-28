<?php

namespace App\Http\Requests\Vendor\Order;

use App\Enum\Status;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatus extends FormRequest
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
            'status' => 'required|in:'
                . Status::ACCEPTED->value . ','
                . Status::CANCELED->value . ','
                . Status::DECLINED->value . ','
                . Status::PENDING->value . ','
                . Status::DELIVERED->value . ','
                . Status::PROCESSING->value . ','
        ];
    }
}
