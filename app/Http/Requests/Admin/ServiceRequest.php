<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'     => 'required|string|min:3|max:255',
            'excerpt'   => 'nullable|string|min:5|max:500',
            'body'      => 'nullable|string|min:10|max:10000',
            'icon'      => 'nullable|string|regex:/^[\s\-a-zA-Z0-9\p{L}\p{M}\p{Emoji}]*$/u|max:100',
            'order'     => 'integer|min:0|max:999',
            'is_active' => 'boolean',
            'plans'     => 'nullable|array',
            'plans.*.name' => 'nullable|string|max:255',
            'plans.*.subtitle' => 'nullable|string|max:255',
            'plans.*.price' => 'nullable|numeric|min:0|max:999999999',
            'plans.*.features_text' => 'nullable|string|max:5000',
            'plans.*.is_popular' => 'nullable|boolean',
            'plans.*.order' => 'nullable|integer|min:0|max:999',
            'plans.*.is_active' => 'nullable|boolean',
        ];
    }
}
