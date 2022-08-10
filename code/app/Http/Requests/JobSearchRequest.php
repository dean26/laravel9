<?php

namespace App\Http\Requests;

use App\Services\JobService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobSearchRequest extends FormRequest
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
            'status' => [
                'nullable',
                Rule::in(JobService::getPossibleStatuses()),
            ],
            'customer_id' => 'nullable|integer',
            'user_id' => 'nullable|integer',
            'per_page' => 'nullable|integer',
            'sort_by' => 'nullable|max:64',
            'expected_installation_date_start' => 'nullable|date',
            'expected_installation_date_end' => 'nullable|date',
            'order_by' => ['nullable', Rule::in(['asc', 'desc'])],
        ];
    }
}
