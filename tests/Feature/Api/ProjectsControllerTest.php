<?php

namespace Tests\Feature\Api;

use App\Models\Projects;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use \Carbon\Carbon;

class ProjectsControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste da rota de listagem de itens
     */
    public function testGetListProjectsEndpoint(): void
    {
        /**
         * Criando registro fake do usuario para autenticacao
         */
        $user = User::factory(1)->createOne();

        /**
         * Criando usuarios fake na base
         */
        User::factory(3)->create();

        /**
         * Criando registros fake na base
         */
        Projects::factory(3)->create();

        /**
         * Acessando o endpoint
         */
        $response = $this->actingAs($user, 'web')
            ->getJson('/api/projects');
        $projects = $response->json();

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(200);

        /**
         * Validando a quantidade de resultados do objeto pai
         */
        $response->assertJsonCount(2);

    }

    /**
     * Teste da rota de buscar o item na base
     */
    public function testGetSingleProjectsEndpoint(): void
    {

        /**
         * Criando registro fake do usuario para autenticacao
         */
        $user = User::factory(1)->createOne();

        /**
         * Criando registros fake na base
         */
        $project = Projects::factory(1)->createOne();

        /**
         * Acessando o endpoint
         */
        $response = $this->actingAs($user, 'web')
            ->getJson('/api/projects/' . $project->id);
        $project = (object) $response->json();

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(200);

    }

    /**
     * Testa de rota para cadastro do item
     */
    public function testPostCreateProjectsEndpoint(): void
    {
        /**
         * Criando registro fake do usuario para autenticacao
         */
        $user = User::factory(1)->createOne();

        /**
         * Criando um objeto fake com dados para inserir na base
         */
        $project = Projects::factory(1)->makeOne()->toArray();

        /**
         * Acessando o endpoint
         */
        $response = $this->actingAs($user, 'web')
            ->postJson('/api/projects/create', $project);
        $project = (object) $response->json();

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(201);

    }

    /**
     * Teste para validar a validacao de campos
     */
    public function testPostCreateProjectsShouldValidateInvalid(): void
    {

        /**
         * Criando registro fake do usuario para autenticacao
         */
        $user = User::factory(1)->createOne();

        /**
         * Acessando o endpoint
         */
        $response = $this->actingAs($user, 'web')
            ->postJson('/api/projects/create', []);
        $project = (object) $response->json();

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(422);

    }

    /**
     * Teste na rota para atualizar todos os campos da base
     */
    public function testPutProjectsEndpoint(): void
    {

        /**
         * Criando registro fake do usuario para autenticacao
         */
        $user = User::factory(1)->createOne();

        /**
         * Criando registros fake na base
         */
        $project = Projects::factory(1)->createOne();

        /**
         * Array com dados a atualizar
         */
        $project_update = [
            'title'       => 'Projeto Mussum Ipsum',
            'description' => "Mussum Ipsum, cacilds vidis litro abertis.  Não sou faixa preta cumpadi, sou preto inteiris, inteiris. Atirei o pau no gatis, per gatis num morreus. Nulla id gravida magna, ut semper sapien. Quem manda na minha terra sou euzis!\n
            Nulla id gravida magna, ut semper sapien. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis. Pellentesque nec nulla ligula. Donec gravida turpis a vulputate ultricies. Detraxit consequat et quo num tendi nada.\n
            Cevadis im ampola pa arma uma pindureta. Eu nunca mais boto a boca num copo de cachaça, agora eu só uso canudis! Manduma pindureta quium dia nois paga. Mauris nec dolor in eros commodo tempor. Aenean aliquam molestie leo, vitae iaculis nisl.",
            'conclusion_at'  => Carbon::now()->format('Y-m-d'),
        ];

        /**
         * Acessando o endpoint
         */
        $response = $this->actingAs($user, 'web')
            ->putJson('/api/projects/' . $project->id, $project_update);
        $project = (object) $response->json();

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(200);

    }

    /**
     * Teste na rota para atualizar um campo especifico na base
     */
    public function testPatchProjectsEndpoint(): void
    {

        /**
         * Criando registro fake do usuario para autenticacao
         */
        $user = User::factory(1)->createOne();

        /**
         * Criando registros fake na base
         */
        $project = Projects::factory(1)->createOne();

        /**
         * Array com dados a atualizar
         */
        $project_update = [
            'conclusion_at' => Carbon::now()->format('Y-m-d'),
        ];

        /**
         * Acessando o endpoint
         */
        $response = $this->actingAs($user, 'web')
            ->patchJson('/api/projects/' . $project->id, $project_update);
        $project = (object) $response->json();

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(200);

    }

    /**
     * Testa na rota para deletar o registro na base
     */
    public function testDeleteProjectsEndpoint(): void
    {

        /**
         * Criando registro fake do usuario para autenticacao
         */
        $user = User::factory(1)->createOne();

        /**
         * Criando registros fake na base
         */
        $project = Projects::factory(1)->createOne();

        /**
         * Acessando o endpoint
         */
        $response = $this->actingAs($user, 'web')
            ->deleteJson('/api/projects/' . $project->id);

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(204);

    }

}
