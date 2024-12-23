<?php

namespace App\Http\Requests\User;

use App\Http\Requests\UserYearlyAnnualLeave\BulkUpsertUserYearlyAnnualLeaveRequest;
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
        $rules = [
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
        ];

        $bulkRequest = new BulkUpsertUserYearlyAnnualLeaveRequest();
        return array_merge($rules, $bulkRequest->rules());
    }
}
