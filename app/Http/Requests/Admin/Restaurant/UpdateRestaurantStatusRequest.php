<?php

namespace App\Http\Requests\Admin\Restaurant;

use App\Enum\Status;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRestaurantStatusRequest extends FormRequest
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
            'status' => 'required|string|in:'
                . Status::PENDING->value . ','
                . Status::SUSPENDED->value . ','
                . Status::APPROVED->value,
        ];
    }
}
