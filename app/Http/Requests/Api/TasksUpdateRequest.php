<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TasksUpdateRequest extends FormRequest
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
            'title'         => 'string|max:255',
            'description'   => 'string',
            'status'        => 'string',
            'conclusion_at' => 'date|date_format:Y-m-d',
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
            'title.string'           => 'O Titulo deve ser um texto',
            'title.max:255'          => 'Você ultrapassou a quantidade de caracteres para campo Titulo',
            'description.string'     => 'A Descrição deve ser um texto',
            'status.string'          => 'O Status deve ser um texto',
            'duedate_at.date'        => 'A data de conclusão deve ser uma data valida',
            'duedate_at.date_format' => 'A data de conclusão deve estar no formato Ano-Mês-Dia Ex. 2024-09-01',
        ];
    }
}
