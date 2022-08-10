<?php

namespace App\Http\Requests;

use App\Services\JobService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobStatusUpdateRequest extends FormRequest
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
        ];
    }
}
