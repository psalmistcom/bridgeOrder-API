<?php

namespace App\Http\Requests\Vendor\Menu;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuRequest extends FormRequest
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
            'menu_id' => 'required|exists:menus,id',
            'item_name' => 'nullable',
            'price' => 'nullable|numeric',
            'in_stock' => 'nullable|boolean',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'variant' => 'nullable|array',
            'variant.*.id' => 'nullable|exists:variants,id',
            'variant.*.item_name' => 'nullable',
            'variant.*.price' => 'nullable|numeric',
            'variant.*.image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ];
    }
}
