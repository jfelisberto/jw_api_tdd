<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReportsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Manipular falha de validação e retornar uma resposta JSON com os erros de validação.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator O objeto de validação que contém os erros de validação.
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'erros'  => $validator->errors(),
        ], 422));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type'              => 'required|string',
            'filter.created_at' => 'date_format:Y-m-d',
            'filter.duedate_at' => 'date_format:Y-m-d',
        ];
    }

    /**
     * Retorna as mensagens de erro personalizadas para as regras de validação.
     *
     * @return array
     */
    public function message()
    {
        return [
            'type.required'                 => 'O campo Type é obrigatório',
            'filter.type.string'            => 'O campo Type deve ser uma string',
            'filter.created_at.date_format' => 'A data de criação deve estar no formato Ano-Mês-Dia Ex. 2024-09-01',
            'filter.duedate_at.date_format' => 'A data de conclusão deve estar no formato Ano-Mês-Dia Ex. 2024-09-01',
        ];
    }
}
