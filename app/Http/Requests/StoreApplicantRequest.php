<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'firstname' => ['required', 'string', 'max:80'],
            'address' => ['required', 'string', 'max:255'],
            'affiliate' => ['nullable', 'string', 'max:255'],
            'phone' => [
                'required', 'string', 'max:20',
                Rule::unique('applicants', 'phone')->where(fn ($q) => $q->where('deleted', 0)),
            ],
            'recruitment_id' => ['nullable', 'integer'],
            'photo' => ['nullable', 'image', 'max:4096'],
            'path' => ['nullable', 'mimes:pdf,doc,docx', 'max:8192'],
        ];
    }

    /**
     * Messages d'erreur personnalisés (formulaire public de candidature).
     */
    public function messages(): array
    {
        return [
            'phone.unique' => 'Une candidature a déjà été enregistrée avec ce numéro de téléphone.',
            'path.mimes' => 'Le dossier de candidature doit être un fichier PDF ou Word.',
            'photo.image' => 'La photo doit être une image (JPG, PNG…).',
        ];
    }
}
