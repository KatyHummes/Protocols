<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\DataPrazoRule;

class ProtocolRequest extends FormRequest
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
            'date' => ['required', 'date', new DataPrazoRule()],
            'description' => ['required'],
            'term' => ['required'],
            'people_id' => ['required'],
            'files.*' => ['max:3000', 'mimes:pdf,jpg,jpeg,png'],
            'files' => ['max:5', ] 
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Este campo é obrigatório',
            'date' => 'Deve ser uma data válida',
            'DataPrazoRule' => 'O atributo não atende aos requisitos de validação.',
            'mimes' => 'O arquivo deve ser do tipo: pdf, jpg, jpeg ou png',
            'max' => 'O arquivo não pode ser maior que 3MB',
            'files.max' => 'Não é permitido enviar mais de 5 arquivos'
        ];
    }
}
