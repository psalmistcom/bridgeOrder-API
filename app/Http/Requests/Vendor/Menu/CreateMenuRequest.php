<?php

namespace App\Http\Requests\Vendor\Menu;

use Illuminate\Foundation\Http\FormRequest;

class CreateMenuRequest extends FormRequest
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
            'item_name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'variant' => 'nullable|array',
            'variant.*.item_name' => 'required_with:variant',
            'variant.*.price' => 'required_with:variant|numeric',
            'variant.*.image' => 'required_with:variant|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ];
    }
}
