<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TasksRelationshipRequest;
use App\Http\Requests\Api\TasksStoreRequest;
use App\Http\Requests\Api\ReportsRequest;
use App\Http\Requests\Api\TasksUpdateRequest;
use App\Http\Resources\Api\TasksResources;
use App\Jobs\sendEmailJob;
use App\Models\Tasks;
use App\Models\TasksRelationship;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
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
                'ststus' => false,
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
    public function relationshipStore(TasksRelationshipRequest $request)
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
    public function relationshipDelete(TasksRelationshipRequest $request)
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
     * Report tasks
     */
    public function reports(Request $request)
    // public function reports(ReportsRequest $request)
    {

        $code = 200;

        $filter = (object) $request->filter;

        $tasks = $this->model->select('tasks.*','users.name as owner')
            ->leftJoin('users', 'users.id', '=', 'tasks.user_id');

            if (isset($filter->status) && !empty($filter->status)) {
            $tasks = $tasks->where('tasks.status', $filter->status);
        }

        if (isset($filter->created_at) && !empty($filter->created_at)) {
            $created_at = Carbon::parse($filter->created_at)->format('Y-m-d');
            $tasks = $tasks->whereBetween('tasks.created_at', ["{$created_at} 00:00:00", "{$created_at} 23:59:59"]);
        }

        if (isset($filter->duedate_at) && !empty($filter->duedate_at)) {
            $duedate_at = Carbon::parse($filter->duedate_at)->format('Y-m-d');
            $tasks = $tasks->where('tasks.duedate_at', $duedate_at);
        }

        $tasks = $tasks->get();

        if ($tasks->count() >= 1) {

            foreach ($tasks as $key => $task) {
                $relations = $this->relationship->where('tasks_relationship.task_id', $task->id)
                    ->leftJoin('users', 'users.id', '=', 'tasks_relationship.user_id')
                    ->select('tasks_relationship.*','users.name as user_relation')
                    ->get();

                $task->relationship = $relations;
                $list[] = $task;
            }

            $data = (object) ['itens' => $list];
            $data->filename = 'relatorio_tarefas';
            $data->filetile = 'Relatorio de tarefas';
            $data->module = 'tasks';
            $data->tr_titles = [
                'id' => '#',
                'title' => 'Titulo',
                'description' => 'Descrição',
                'owner' => 'Proprietário',
                'status' => 'Status',
                'duedate_at' => 'Data de conclusão',
                'created_at' => 'Data de criação',
                'relationship' => 'Usuários'
            ];

            $date_filename = Carbon::now()->format('YmdHis');
            $path = storage_path("app/public/");
            $filename = "{$date_filename}_{$data->filename}.{$request->type}";
            $path_filename = "{$path}{$filename}";
            $web_filename = "{$_SERVER['HTTP_HOST']}/public/storage/{$date_filename}_{$data->filename}.{$request->type}";

            if ($request->type == 'pdf') {
                /**
                 * Inicia a isntancia do PDF
                 */
                $pdf = PDF::setPaper('a4', 'P');

                /**
                 * Setando as opcoes do arquivo
                 */
                $pdf->set_option("default_media_type", 'print');
                $pdf->set_option("default_paper_size", 'a4');
                $pdf->set_option("enable_unicode", true);
                $pdf->set_option("enable_php", true);
                $pdf->set_option("enable_remote", true);
                $pdf->set_option("enable_css_float", true);
                $pdf->set_option("enable_javascript", true);
                $pdf->set_option("enable_html5_parser", true);
                $pdf->set_option("enable_font_subsetting", true);

                /**
                 * Renderizando o conteudo para conversao
                 */
                $pdf->loadView('pdf.reports.task', compact('data'));
                $pdf->render();

                /**
                 * Executando a saida do arquivo conforma arequisicao
                 */
                switch ($request->render) {
                    case 'download':
                        return $pdf->download($filename);
                        break;
                    case 'save':
                        $path = 'storage/reports';
                        $filename = "{$path}/{$filename}";
                        return $pdf->save($filename);
                        break;
                    default:
                        return $pdf->stream($filename);
                        break;
                }

            }

            if ($request->type == 'xlsx' || $request->type == 'csv') {

                /**
                 * Instancia principal da planilha
                 */
                $spreadsheet = new Spreadsheet();

                /**
                 * Aba ativa da planilha
                 */
                $sheet = $spreadsheet->getActiveSheet()->setTitle('Tarefas');

                /**
                 * Imprime os titulos nas celulas
                 */
                $sheet->setCellValue("A1", "#");
                $sheet->setCellValue("B1", "Titulo");
                $sheet->setCellValue("C1", "Descrição");
                $sheet->setCellValue("D1", "Proprietário");
                $sheet->setCellValue("E1", "Status");
                $sheet->setCellValue("F1", "Data de conclusão");
                $sheet->setCellValue("G1", "Data de criação");
                $sheet->setCellValue("H1", "Usuários");

                $i = 2;
                foreach ($data->itens as $item) {
                    $sheet->setCellValue("A{$i}", $item->id);
                    $sheet->setCellValue("B{$i}", $item->title);
                    $sheet->setCellValue("C{$i}", $item->description);
                    $sheet->setCellValue("D{$i}", $item->owner);
                    $sheet->setCellValue("E{$i}", $item->status);
                    $sheet->setCellValue("F{$i}", Carbon::parse($item->duedate_at)->format('d/m/Y'));
                    $sheet->setCellValue("G{$i}", Carbon::parse($item->created_at)->format('d/m/Y H:i:s'));

                    foreach($item->relationship as $key => $relation) {
                        if ($key == 0) {
                            $user_relation = $relation->user_relation;
                        } else {
                            $user_relation .= ", {$relation->user_relation}";
                        }
                    }
                    $sheet->setCellValue("H{$i}", $user_relation);
                    $i++;
                }

                if ($request->type == 'xlsx') {
                    /**
                     * Instanciando o arquivo EXCEL
                     */
                    $writer = new Xls($spreadsheet);
                    $content_type = 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;';
                } else {
                    /**
                     * Instanciando o arquivo CSV
                     */
                    $writer = new Csv($spreadsheet);
                    $content_type = 'Content-Type: text/csv;';
                    $writer->setUseBOM(true);
                    $writer->setDelimiter(';');
                    $writer->setEnclosure('"');
                    $writer->setLineEnding("\r\n");
                }

                if ($request->render == 'download') {
                    header($content_type);
                    header('Content-Disposition: attachment; filename="' . urlencode($filename  ) . '"');
                    header('Cache-Control: max-age=0'); // If you're serving to IE 9, then the following may be needed
                    header('Cache-Control: max-age=1'); // If you're serving to IE over SSL, then the following may be needed
                    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                    header('Pragma: public'); // HTTP/1.0

                    setlocale(LC_ALL, 'en_US');

                    $writer->save('php://output');

                } else {
                    $writer->save($filename);
                    return response()->download($filename);
                }

            }

            $response = [
                'status' => true,
                'data'   => $filename
            ];

        } else {
            $response = [
                'status'  => false,
                'message' => 'Nenhum registro encontrado',
            ];

            $code = 400;
            /**
             * Retorno da API
             */
            return response()->json($response, $code);
        }


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
