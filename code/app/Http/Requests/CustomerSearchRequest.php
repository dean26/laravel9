<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'query' => 'nullable|min:2|max:64',
            'per_page' => 'nullable|integer',
            'sort_by' => 'nullable|max:64',
            'order_by' => ['nullable', Rule::in(['asc', 'desc'])],
        ];
    }
}
