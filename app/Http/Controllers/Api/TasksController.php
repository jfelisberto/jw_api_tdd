<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\tasksRelationshipRequest;
use App\Http\Requests\Api\TasksStoreRequest;
use App\Http\Requests\Api\TasksUpdateRequest;
use App\Http\Resources\Api\TasksResources;
use App\Jobs\sendEmailJob;
use App\Models\Tasks;
use App\Models\TasksRelationship;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TasksController extends Controller
{

    /**
     * Load class this specifc model and relations
     */
    public function __construct(private Tasks $model, private TasksRelationship $relationship, private User $user)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $code = 200;

        /**
         * Validando a permissao de acesso do usuario
         */
        if (!(auth()->user()->tokenCan('post:read'))) {
            return response()->json([
                'status'  => false,
                'message' => 'Acesso não autorizado',
            ], 403);
        }

        /**
         * Validando os parametros da request
         */
        $validator = Validator::make($request->all(), [
            'limit' => 'int',
            'page'  => 'int',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'message' => 'Parametros invalidos para limit ou page, só pode conter valores numéricos',
            ], 404);
        }

        /**
         * Buscando os registros na base
         */
        if (($request->limit >= 1 && $request->limit != 'all') && $request->page >= 1) {
            $result = $this->model->paginate($request->limit);
        } else {
            $result = $this->model->all();
        }

        if (!$result) {
            $code = 204;
            $response = [];
        } else {
            $response = [
                'status' => true,
                'data'   => $result,
            ];
        }
        /**
         * Retorno da API
         */
        return response()->json($response, $code);
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
                'status'  => false,
                'message' => 'Acesso não autorizado',
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
        return response()->json([
            'status' => true,
            'data'   => new TasksResources($result),
        ], 201);
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
                'status'  => false,
                'message' => 'Acesso não autorizado',
            ], 403);
        }
        $code = 200;

        /**
         * Acessando o registro
         */
        $result = $this->model->find($id);

        if (!$result) {
            $code = 204;
            $response = [];
        } else {
            $result->tasks_relationship = $result->tasksRelationship;
            $response = [
                'status' => true,
                'data'   => new TasksResources($result),
            ];
        }

        /**
         * Retorno da API
         */
        return response()->json($response, $code);
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
                'status'  => false,
                'message' => 'Acesso não autorizado',
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
         * Envia o e-mail de notificacao aos usuarios relacionados a tarefa
         */
        $task = $this->model->find($id);
        $task->tasks_relationship = $task->tasksRelationship;

        if ($task->tasks_relationship) {
            $task->duedate_at = Carbon::parse($task->duedate_at)->format('d/m/Y');
            foreach ($task->tasks_relationship as $relationship) {
                $user = $this->user->find($relationship->user_id);
                $object = (object) [
                    'template' => 'notfy-tasks',
                    'action'   => 'update',
                    'email'    => $user->email,
                    'user'     => $user,
                    'task'     => $task,
                ];
                sendEmailJob::dispatch($object)->onQueue('sendemail');

                /**
                 * Salvar log
                 */
                Log::info('Gerenciamento de tarefa - atualizacao.', ['email' => $user->email]);

            }
        }

        /**
         * Retorno da API
         */
        return response()->json([
            'status' => true,
            'data'   => new TasksResources($result),
        ]);
    }

    /**
     * Relacionar a task a um usuario
     */
    public function relationshipStore(tasksRelationshipRequest $request)
    {

        $code = 200;
        $data = $request->all();

        /**
         * Checa se ja existe um relacionamento para a Tarefa e o Usuario
         */
        $check_relation = $this->relationship->where('task_id', $request->task_id)->where('user_id', $request->user_id)->exists();

        if (!$check_relation) {
            $result = $this->relationship->create($data);

            /**
             * Envia o e-mail de notificacao ao usuario relacionado a tarefa
             */
            $task = $this->model->find($request->task_id);
            $task->duedate_at = Carbon::parse($task->duedate_at)->format('d/m/Y');
            $user = $this->user->find($request->user_id);
            $object = (object) [
                'template' => 'notfy-tasks',
                'action'   => 'relation',
                'email'    => $user->email,
                'user'     => $user,
                'task'     => $task,
            ];
            sendEmailJob::dispatch($object)->onQueue('sendemail');

            /**
             * Salvar log
             */
            Log::info('Gerenciamento de tarefa - relacionamento.', ['email' => $user->email]);

            $response = [
                'status' => true,
                'data'   => $result,
            ];
        } else {
            $response = [
                'status'  => false,
                'message' => 'Já existem um relacionamento entre esta tarefa e o usuário',
            ];

            $code = 400;
        }

        /**
         * Retorno da API
         */
        return response()->json($response, $code);
    }

    /**
     * Relacionar a task a um usuario
     */
    public function relationshipDelete(tasksRelationshipRequest $request)
    {

        $code = 204;
        $data = $request->all();

        /**
         * Checa se ja existe um relacionamento para a Tarefa e o Usuario
         */
        $check_relation = $this->relationship->where('task_id', $request->task_id)->where('user_id', $request->user_id);

        if ($check_relation->exists()) {
            $result = $this->relationship->where('task_id', $request->task_id)->where('user_id', $request->user_id)->forceDelete();

            $response = [];
        } else {
            $response = [
                'status'  => false,
                'message' => 'Relacionamento entre a tarefa e o usuário não encontradp',
            ];

            $code = 400;
        }

        /**
         * Retorno da API
         */
        return response()->json($response, $code);
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
                'status'  => false,
                'message' => 'Acesso não autorizado',
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
