<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $productId = $this->route('product') ? $this->route('product')->id : null;

        $rules = [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sku' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($productId)
            ],
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ];

        if ($this->isMethod('POST')) {
            $rules['product_image'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
        } else {
            $rules['product_image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'اسم المنتج مطلوب.',
            'name.max' => 'اسم المنتج يجب ألا يتجاوز 255 حرف.',
            'category_id.required' => 'يجب اختيار فئة.',
            'category_id.exists' => 'الفئة المحددة غير موجودة.',
            'sku.unique' => 'رقم SKU هذا مستخدم بالفعل.',
            'sku.max' => 'رقم SKU يجب ألا يتجاوز 100 حرف.',
            'price.required' => 'السعر مطلوب.',
            'price.numeric' => 'السعر يجب أن يكون رقماً.',
            'price.min' => 'السعر يجب أن يكون على الأقل 0.',
            'quantity.required' => 'الكمية مطلوبة.',
            'quantity.integer' => 'الكمية يجب أن تكون رقماً صحيحاً.',
            'quantity.min' => 'الكمية يجب أن تكون على الأقل 0.',
            'product_image.required' => 'صورة المنتج مطلوبة.',
            'product_image.image' => 'الملف يجب أن يكون صورة.',
            'product_image.mimes' => 'نوع الصورة يجب أن يكون: jpeg, png, jpg, gif, أو webp.',
            'product_image.max' => 'حجم الصورة يجب ألا يتجاوز 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'اسم المنتج',
            'category_id' => 'الفئة',
            'sku' => 'SKU',
            'description' => 'الوصف',
            'price' => 'السعر',
            'quantity' => 'الكمية',
            'is_active' => 'الحالة',
            'product_image' => 'صورة المنتج',
        ];
    }
}