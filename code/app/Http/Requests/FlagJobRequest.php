<?php

namespace App\Http\Requests;

use App\Rules\FlagUnique;
use App\Services\JobService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FlagJobRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'content' => [
                'required',
                Rule::in(JobService::getPossibleFlags()),
                new FlagUnique('Job', request()->segment(3)),
            ],
        ];
    }
}
