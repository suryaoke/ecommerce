<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCategoryUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            'image' => 'nullable|max:2048',
            'name' => 'required|string|max:255|unique:product_categories,name,' . $this->route('product_category'),
            'tagline' => 'nullable|string|max:255',
            'description' => 'required|string'
        ];
    }

    public function attributes(): array
    {
        return [
            'image' => 'Foto',
            'name' => 'Nama Kategori',
            'tagline' => 'Tagline',
            'description' => 'Deskripsi',
        ];
    }
}
