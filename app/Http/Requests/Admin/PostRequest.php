<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'            => 'required|string|min:5|max:255',
            'category_id'      => 'nullable|integer|exists:categories,id',
            'excerpt'          => 'nullable|string|min:10|max:500',
            'body'             => 'required|string|min:50|max:100000',
            'featured_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=800,min_height=600',
            'detail_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=800,min_height=600',
            'meta_title'       => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'is_published'     => 'boolean',
        ];
    }
}
