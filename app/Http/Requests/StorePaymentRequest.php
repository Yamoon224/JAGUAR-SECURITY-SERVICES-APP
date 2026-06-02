<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
            "salary" => ['required'],
            "amount" => ['required'],
            "salary" => ['required'],
            "prime" => ['required'],
            "employee_id" => ['required'],
            "month_id" => ['required'],
            "year_id" => ['required'],
        ];
    }
}
