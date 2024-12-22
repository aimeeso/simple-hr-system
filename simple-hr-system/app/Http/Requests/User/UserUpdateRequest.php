<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
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
            "name" => "sometimes|string",
            "last_name" => "sometimes|string",
            "first_name" => "sometimes|string",
            "birthday" => "sometimes|date",
            "gender" => [
                "sometimes",
                Rule::in(['F', 'M'])
            ],
            "on_board_date" => "sometimes|date",
            "exit_date" => "sometimes|nullable|date",
            "is_active" => "sometimes|date",
            "password" => "sometimes|string",
            "role_id" => "sometimes|nullable|exists:roles,id",
            "yearlyAnnualLeaves" => "sometimes|array",
            "yearlyAnnualLeaves.*.year" => "required|integer",
            "yearlyAnnualLeaves.*.number_of_day" => "required|numeric",
            "yearlyAnnualLeaves.*.additional_number_of_day" => "required|numeric",
        ];
    }
}
