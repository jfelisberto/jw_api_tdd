<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TasksRelationshipRequest extends FormRequest
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
            'task_id' => 'required|integer|exists:tasks,id',
            'user_id' => 'required|integer|exists:users,id',
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
            'task_id.required' => 'O ID da tarefa é obrigatório',
            'task_id.integer'  => 'O ID da tarefa deve ser um inteiro',
            'task_id.exists'   => 'O ID da tarefa é invalido',
            'user_id.required' => 'O ID do usuario é obrigatório',
            'user_id.integer'  => 'O ID do usuario deve ser um inteiro',
            'user_id.exists'   => 'O ID do usuario é invalido',
        ];
    }
}
