<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProjectsStoreRequest;
use App\Models\Projects;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{

    /**
     * Load class this specifc model
     */
    public function __construct(private Projects $model)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /**
         * Buscando os registros na base
         */
        $result = $this->model->all();

        /**
         * Retorno da API
         */
        return response()->json($result);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectsStoreRequest $request)
    {
        $data = $request->all();

        /**
         * Cadastrando o registro
         */
        $result = $this->model->create($data);

        /**
         * Retorno da API
         */
        return response()->json($result, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        /**
         * Acessando o registro
         */
        $result = $this->model->find($id);

        /**
         * Retorno da API
         */
        return response()->json($result);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $data = $request->all();

        /**
         * Acessando o registro
         */
        $result = $this->model->find($id);

        /**
         * Atualizando os dados recebidos
         */
        $result->update($data);

        /**
         * Retorno da API
         */
        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        /**
         * Acessando o registro
         */
        $result = $this->model->find($id);

        /**
         * Apagando o registro ao setar o campo deleted_at
         */
        $result->delete();

        /**
         * Retorno da API
         */
        return response()->json([], 204);
    }
}
