<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
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
            "name" => "required|string",
            "last_name" => "required|string",
            "first_name" => "required|string",
            "birthday" => "required|date",
            "gender" => ["required", Rule::in(['F', 'M'])],
            "on_board_date" => "required|date",
            "exit_date" => "nullable|date",
            "is_active" => "required|boolean",
            "email" => "required|email:rfc,dns|unique:users,email",
            "password" => "required|string",
            "role_id" => "nullable|exists:roles,id"
        ];
    }
}
