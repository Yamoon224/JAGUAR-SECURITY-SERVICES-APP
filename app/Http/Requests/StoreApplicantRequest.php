<?php

namespace App\Http\Requests;

use App\Models\Applicant;
use Illuminate\Foundation\Http\FormRequest;

class StoreApplicantRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string'],
            'firstname' => ['required', 'string', 'max:80'],
            'phone' => ['required', 'string', 'max:20', 'unique:'.Applicant::class],
            'file' => ['required|mimes:pdf']
        ];
    }
}
