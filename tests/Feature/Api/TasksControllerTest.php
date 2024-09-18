<?php

namespace Tests\Feature\Api;

use App\Models\Tasks;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Auth\AuthManager;
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
         * Criando registro fake do usuario para autenticacao
         */
        $user = User::factory(1)->createOne();

        /**
         * Criando registros fake na base
         */
        Tasks::factory(3)->create();

        /**
         * Acessando o endpoint
         */
        $response = $this->actingAs($user, 'web')
            ->getJson('/api/tasks');
        $tasks = (object) $response->json();

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(200);

        /**
         * Validando a quantidade de resultados obtidos do objeto pai
         */
        $response->assertJsonCount(2);
    }

    /**
     * Teste da rota de buscar o item na base
     */
    public function testGetSingleTasksEndpoint(): void
    {

        /**
         * Criando registro fake do usuario para autenticacao
         */
        $user = User::factory(1)->createOne();

        /**
         * Criando registros fake na base
         */
        $task = Tasks::factory(1)->createOne();

        /**
         * Acessando o endpoint
         */
        $response = $this->actingAs($user, 'web')
            ->getJson('/api/tasks/' . $task->id);

        $task = (object) $response->json();

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(200);
    }

    /**
     * Testa de rota para cadastro do item
     */
    public function testPostCreateTasksEndpoint(): void
    {

        /**
         * Criando registro fake do usuario para autenticacao
         */
        $user = User::factory(1)->createOne();

        /**
         * Criando um objeto fake com dados para inserir na base
         */
        $task = Tasks::factory(1)->makeOne()->toArray();

        /**
         * Acessando o endpoint
         */
        $response = $this->actingAs($user, 'web')
            ->postJson('/api/tasks/create', $task);
        $task = (object) $response->json();

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(201);
    }

    /**
     * Teste para validar a validacao de campos
     */
    public function testPostCreateTasksShouldValidateInvalid(): void
    {

        /**
         * Criando registro fake do usuario para autenticacao
         */
        $user = User::factory(1)->createOne();

        /**
         * Acessando o endpoint
         */
        $response = $this->actingAs($user, 'web')
            ->postJson('/api/tasks/create', []);
        $task = (object) $response->json();

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(422);
    }

    /**
     * Teste na rota para atualizar todos os campos da base
     */
    public function testPutTasksEndpoint(): void
    {
        /**
         * Criando registro fake do usuario para autenticacao
         */
        $user = User::factory(1)->createOne();

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
        $response = $this->actingAs($user, 'web')
            ->putJson('/api/tasks/' . $task->id, $task_update);
        $task = (object) $response->json();

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(200);
    }

    /**
     * Teste na rota para atualizar um campo especifico na base
     */
    public function testPatchTasksEndpoint(): void
    {

        /**
         * Criando registro fake do usuario para autenticacao
         */
        $user = User::factory(1)->createOne();

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
        $response = $this->actingAs($user, 'web')
            ->patchJson('/api/tasks/' . $task->id, $task_update);
        $task = (object) $response->json();

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(200);

    }

    /**
     * Testa na rota para deletar o registro na base
     */
    public function testDeleteTasksEndpoint(): void
    {

        /**
         * Criando registro fake do usuario para autenticacao
         */
        $user = User::factory(1)->createOne();

        /**
         * Criando registros fake na base
         */
        $task = Tasks::factory(1)->createOne();

        /**
         * Acessando o endpoint
         */
        $response = $this->actingAs($user, 'web')
            ->deleteJson('/api/tasks/' . $task->id);

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(204);
    }
}
