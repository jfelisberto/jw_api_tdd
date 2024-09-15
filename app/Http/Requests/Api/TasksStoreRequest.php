<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class TasksStoreRequest extends FormRequest
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
            'title'         => 'required',
            'description'   => 'required',
            'status'        => 'required',
            'conclusion_at' => 'date',
        ];
    }

    public function message()
    {
        return [
            'title.required'       => 'O Titulo é obrigatório',
            'description.required' => 'A Descrição é obrigatória',
            'status.required'      => 'O Status é obrigatório',
            'duedate_at.date'      => 'A data de conclusão deve ser uma data valida',
        ];
    }
}
