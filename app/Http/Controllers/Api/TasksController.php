<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TasksStoreRequest;
use App\Http\Requests\Api\TasksUpdateRequest;
use App\Http\Resources\Api\TasksResources;
use App\Models\Tasks;
use App\Models\TasksRelationship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class TasksController extends Controller
{

    /**
     * Load class this specifc model and relations
     */
    public function __construct(private Tasks $model, TasksRelationship $relations)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /**
         * Validando a permissao de acesso do usuario
         */
        if (!(auth()->user()->tokenCan('post:read'))) {
            return response()->json([
                'status' => false,
                'message' => 'Acesso não autorizado'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'limit' => 'int',
            'page'  => 'int'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'message' => 'Parametros invalidos para limit ou page, só pode conter valores numéricos'
            ], 404);
        }

        $code = 200;

        /**
         * Buscando os registros na base
         */
        if (($request->limit >= 1 && $request->limit <> 'all') && $request->page >= 1) {
            $result = $this->model->paginate($request->limit);
        } else {
            $result = $this->model->all();
        }

        if (!$result) {
            $code = 204;
        }
        /**
         * Retorno da API
         */
        return response()->json($result, $code);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TasksStoreRequest $request)
    {
        /**
         * Validando a permissao de acesso do usuario
         */
        if (!(auth()->user()->tokenCan('post:create'))) {
            return response()->json([
                'status' => false,
                'message' => 'Acesso não autorizado'
            ], 403);
        }

        $data = $request->all();

        /**
         * Atribuindo usuario logado como owner da Task
         */
        $data['user_id'] = auth()->user()->id;

        /**
         * Cadastrando o registro
         */
        $result = $this->model->create($data);

        /**
         * Retorno da API
         */
        return response()->json(new TasksResources($result), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        /**
         * Validando a permissao de acesso do usuario
         */
        if (!(auth()->user()->tokenCan('post:read'))) {
            return response()->json([
                'status' => false,
                'message' => 'Acesso não autorizado'
            ], 403);
        }
        $code = 200;
        /**
         * Acessando o registro
         */
        $result = $this->model->find($id);

        if (!$result) {
            $code = 204;
        }

        /**
         * Retorno da API
         */
        // return response()->json($result);
        return response()->json(new TasksResources($result), $code);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TasksUpdateRequest $request, int $id)
    {
        /**
         * Validando a permissao de acesso do usuario
         */
        if (!(auth()->user()->tokenCan('post:update'))) {
            return response()->json([
                'status' => false,
                'message' => 'Acesso não autorizado'
            ], 403);
        }

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
        // return response()->json($result);
        return response()->json(new TasksResources($result));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        /**
         * Validando a permissao de acesso do usuario
         */
        if (!(auth()->user()->tokenCan('post:delete'))) {
            return response()->json([
                'status' => false,
                'message' => 'Acesso não autorizado'
            ], 403);
        }

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
