<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'position' => ['required', 'string', 'max:50'],
            'studygrade' => ['required', 'string', 'max:50'],
            'familystatus' => ['required', 'string', 'max:50'],
            'contract' => ['required', 'string', 'max:30'],
            'contract_start_at' => ['nullable', 'date'],
            'contract_end_at' => ['nullable', 'date', 'after_or_equal:contract_start_at'],
            'emergency_name' => ['required', 'string', 'max:100'],
            'emergency_phone' => ['required', 'string', 'max:20'],
            'salary' => ['required'],
            'transport_indemnity' => ['nullable', 'numeric'],
            'meal_allowance' => ['nullable', 'numeric'],
            'housing_indemnity' => ['nullable', 'numeric'],
            'punctuality_allowance' => ['nullable', 'numeric'],
            'responsibility_allowance' => ['nullable', 'numeric'],
        ];
    }
}
