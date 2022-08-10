<?php

namespace App\Http\Requests;

use App\Dictionaries\UserRolesDictionary;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
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
            'name' => 'required|min:2|max:255',
            'phone' => 'nullable|min:2|max:255',
            'address' => 'nullable|min:2|max:255',
            'email' => 'required|email|min:8|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => ['nullable',
                Rule::in(
                    [
                        UserRolesDictionary::ROLE_DIRECTOR,
                        UserRolesDictionary::ROLE_INSTALLER,
                        UserRolesDictionary::ROLE_WAREHOUSE
                    ]
                )
            ],
        ];
    }
}
