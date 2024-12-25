<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserLeaveRequestUpdateRequest extends FormRequest
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
            "leave_type_id" => "required|exists:leave_types,id",
            "start_date" => "required|date",
            "start_type" => ["required", Rule::in(['AM', 'PM'])],
            "end_date" => "required|date",
            "end_type" => ["required", Rule::in(['AM', 'PM'])],
            "status" => ["required", Rule::in(['DRAFT', 'PENDING'])],
        ];
    }
}
