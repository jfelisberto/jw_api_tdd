<?php

namespace Tests\Feature\Api;

use App\Models\Tasks;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use \Carbon\Carbon;

class TasksControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste da rota de listagem de itens
     */
    public function testGetListTasksEndpoint(): void
    {

        /**
         * Criando registros fake na base
         */
        Tasks::factory(3)->create();

        /**
         * Acessando o endpoint
         */
        $response = $this->getJson('/api/tasks');
        $tasks = $response->json();

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(200);

        /**
         * Validando a quantidade de resultados obtidos
         */
        $response->assertJsonCount(3);

        /**
         * Validando os dados do response
         */
        $response->assertJson(function (AssertableJson $json) use ($tasks) {

            /**
             * Validando o tipo de dados de cada coluna
             */
            $json->whereAllType([
                '0.id'          => 'integer',
                '0.title'       => 'string',
                '0.description' => 'string',
                '0.status'      => 'string',
                '0.duedate_at'  => 'string',
            ]);

            /**
             * Validando o retorno de todas as colunas do registro
             */
            $json->hasAll(['0.id', '0.title', '0.description', '0.status', '0.duedate_at']);

            /**
             * Obtendo o primeiro registro da lista para validar o retorno
             */
            $task = (object) $tasks[0];

            /**
             * Validando se os valores estao retornando corretamente
             */
            $json->whereAll([
                '0.id'          => $task->id,
                '0.title'       => $task->title,
                '0.description' => $task->description,
                '0.status'      => $task->status,
                '0.duedate_at'  => $task->duedate_at,
            ]);

        });
    }

    /**
     * Teste da rota de buscar o item na base
     */
    public function testGetSingleTasksEndpoint(): void
    {

        /**
         * Criando registros fake na base
         */
        $task = Tasks::factory(1)->createOne();

        /**
         * Acessando o endpoint
         */
        $response = $this->getJson('/api/tasks/' . $task->id);
        $task = (object) $response->json();

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(200);

        /**
         * Validando os dados do response
         */
        $response->assertJson(function (AssertableJson $json) use ($task) {

            /**
             * Validando o retorno de todas as colunas do registro
             */
            $json->hasAll(['id', 'title', 'description', 'status', 'duedate_at', 'created_at', 'updated_at', 'deleted_at']);

            /**
             * Validando o tipo de dados de cada coluna
             */
            $json->whereAllType([
                'id'          => 'integer',
                'title'       => 'string',
                'description' => 'string',
                'status'      => 'string',
                'duedate_at'  => 'string',
            ]);

            /**
             * Validando se os valores estao retornando corretamente
             */
            $json->whereAll([
                'id'          => $task->id,
                'title'       => $task->title,
                'description' => $task->description,
                'status'      => $task->status,
                'duedate_at'  => $task->duedate_at,
            ]);

        });

    }

    /**
     * Testa de rota para cadastro do item
     */
    public function testPostCreateTasksEndpoint(): void
    {

        /**
         * Criando um objeto fake com dados para inserir na base
         */
        $task = Tasks::factory(1)->makeOne()->toArray();

        /**
         * Acessando o endpoint
         */
        $response = $this->postJson('/api/tasks/create', $task);
        $task = (object) $response->json();

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(201);

        /**
         * Validando os dados do response
         */
        $response->assertJson(function (AssertableJson $json) use ($task) {

            /**
             * Validando o retorno de todas as colunas do registro
             */
            $json->hasAll(['id', 'title', 'description', 'status', 'duedate_at', 'created_at', 'updated_at']);

            /**
             * Validando se os valores estao retornando corretamente
             */
            $json->whereAll([
                'title'       => $task->title,
                'description' => $task->description,
                'status'      => $task->status,
                'duedate_at'  => $task->duedate_at,
            ])->etc();

        });

    }

    public function testPostCreateTasksShouldValidateInvalid(): void
    {

        /**
         * Criando um objeto fake com dados para inserir na base
         */
        // $task = Tasks::factory(1)->makeOne()->toArray();

        /**
         * Acessando o endpoint
         */
        $response = $this->postJson('/api/tasks/create', []);
        $task = (object) $response->json();

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(422);

        /**
         * Validando os dados do response
         */
        $response->assertJson(function (AssertableJson $json) {

            /**
             * Validando o retorno de todas as colunas do registro
             */
            $json->hasAll(['message', 'errors']);

        });

    }

    /**
     * Teste na rota para atualizar todos os campos da base
     */
    public function testPutTasksEndpoint(): void
    {

        /**
         * Criando registros fake na base
         */
        $task = Tasks::factory(1)->createOne();

        /**
         * Array com dados a atualizar
         */
        $task_update = [
            'title'       => 'Tarefa Mussum Ipsum',
            'description' => "Cacilds vidis litro abertis.  Viva Forevis aptent taciti sociosqu ad litora torquent. Manduma pindureta quium dia nois paga. Si u mundo tá muito paradis? Toma um mé que o mundo vai girarzis! Nulla id gravida magna, ut semper sapien.\n
                Vehicula non. Ut sed ex eros. Vivamus sit amet nibh non tellus tristique interdum. Eu nunca mais boto a boca num copo de cachaça, agora eu só uso canudis! Suco de cevadiss, é um leite divinis, qui tem lupuliz, matis, aguis e fermentis. A ordem dos tratores não altera o pão duris.\n
                Posuere libero varius. Nullam a nisl ut ante blandit hendrerit. Aenean sit amet nisi. Copo furadis é disculpa de bebadis, arcu quam euismod magna. Casamentiss faiz malandris se pirulitá. Suco de cevadiss, é um leite divinis, qui tem lupuliz, matis, aguis e fermentis.\n
                A ordem dos tratores não altera o pão duris. Posuere libero varius. Nullam a nisl ut ante blandit hendrerit. Aenean sit amet nisi. Pra lá, depois divoltis porris, paradis. Suco de cevadiss, é um leite divinis, qui tem lupuliz, matis, aguis e fermentis.\n
                Per aumento de cachacis, eu reclamis. Si u mundo tá muito paradis? Toma um mé que o mundo vai girarzis! Mais vale um bebadis conhecidiss, que um alcoolatra anonimis. Delegadis gente finis, bibendum egestas augue arcu ut est.",
            'status'      => 'progress',
            'duedate_at'  => Carbon::now()->format('Y-m-d'),
        ];

        /**
         * Acessando o endpoint
         */
        $response = $this->putJson('/api/tasks/' . $task->id, $task_update);
        $task = (object) $response->json();

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(200);

        /**
         * Validando os dados do response
         */
        $response->assertJson(function (AssertableJson $json) use ($task) {

            /**
             * Validando o retorno de todas as colunas do registro
             */
            $json->hasAll(['id', 'title', 'description', 'status', 'duedate_at', 'created_at', 'updated_at', 'deleted_at']);

            /**
             * Validando se os valores estao retornando corretamente
             */
            $json->whereAll([
                'title'       => $task->title,
                'description' => $task->description,
                'status'      => $task->status,
                'duedate_at'  => $task->duedate_at,
            ])->etc();

        });

    }

    /**
     * Teste na rota para atualizar um campo especifico na base
     */
    public function testPatchTasksEndpoint(): void
    {

        /**
         * Criando registros fake na base
         */
        $task = Tasks::factory(1)->createOne();

        /**
         * Array com dados a atualizar
         */
        $task_update = [
            'status' => 'conclusion',
        ];

        /**
         * Acessando o endpoint
         */
        $response = $this->patchJson('/api/tasks/' . $task->id, $task_update);
        $task = (object) $response->json();

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(200);

        /**
         * Validando os dados do response
         */
        $response->assertJson(function (AssertableJson $json) use ($task) {

            /**
             * Validando o retorno de todas as colunas do registro
             */
            $json->hasAll(['id', 'title', 'description', 'status', 'duedate_at', 'created_at', 'updated_at', 'deleted_at']);

            /**
             * Validando se os valores estao retornando corretamente
             */
            $json->where('status', $task->status);

        });

    }

    /**
     * Testa na rota para deletar o registro na base
     */
    public function testDeleteTasksEndpoint(): void
    {

        /**
         * Criando registros fake na base
         */
        $task = Tasks::factory(1)->createOne();

        /**
         * Acessando o endpoint
         */
        $response = $this->deleteJson('/api/tasks/' . $task->id);

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(204);

    }

}
