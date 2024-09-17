<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Load class this specifc model and relations
     */
    public function __construct(private User $model)
    {
        //
    }

    /**
     * Retorna uma lista com todos usuarios validos
     *
     * Este metodo recupera uma lista de usuarios da base
     * e a retorna como uma resposta JSON
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        $code = 200;

        /**
         * Validando a permissao de acesso do usuario
         */
        if (!(auth()->user()->tokenCan('post:read'))) {
            return response()->json([
                'status' => false,
                'message' => 'Acesso não autorizado'
            ], 403);
        }

        /**
         * Validando os parametros da request
         */
        $validator = Validator::make($request->all(), [
            'limit' => 'int',
            'page'  => 'int'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'message' => 'Parametros invalidos para limit ou page, só pode conter valores numéricos'
            ], 404);
        }

        /**
         * Buscando os registros na base
         */
        if (($request->limit >= 1 && $request->limit <> 'all')) {
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
        return response()->json([
            'status' => true,
            'data' => $result,
        ], 200);
    }
}
