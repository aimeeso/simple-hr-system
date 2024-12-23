<?php

namespace App\Http\Requests\UserYearlyAnnualLeave;

use Illuminate\Foundation\Http\FormRequest;

class BulkUpsertUserYearlyAnnualLeaveRequest extends FormRequest
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
            "yearlyAnnualLeaves" => "sometimes|array",
            "yearlyAnnualLeaves.*.year" => "required|integer|min:1990|max:2999",
            "yearlyAnnualLeaves.*.number_of_day" => "required|numeric|min:0|multiple_of:0.5",
            "yearlyAnnualLeaves.*.additional_number_of_day" => "required|numeric|min:0|multiple_of:0.5",
        ];
    }
}
