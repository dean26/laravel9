<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobUpdateRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id',
            'item_id' => 'required|integer|exists:items,id',
            'customer_id' => 'required|integer|exists:customers,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'expected_installation_date' => 'nullable|date',
        ];
    }
}
