<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignupStoreRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
  public function rules(): array
{
    return [
        'role' => 'required|in:Student,Parent',
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|regex:/^[6-9][0-9]{9,11}$/',
        'password' => 'required|string|min:6',
        'confirm_password' => 'required|same:password',

        'current_education' => 'nullable|string|max:255',
        'subject_group' => 'nullable|string|max:255',

        'children' => 'required_if:role,Parent|array',
        'children.*.name' => 'required_if:role,Parent|string|max:255',
        'children.*.education_level' => 'required_if:role,Parent|string|max:255',
        'children.*.subject_group' => 'required_if:role,Parent|string|max:255',
    ];
}


    public function messages()
    {
        return [
            'children.required_if' => 'Parent must have at least one child.',
        ];
    }
}
