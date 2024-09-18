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
        $response = $this->getJson('/api/projects');
        $projects = $response->json();

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
        $response->assertJson(function (AssertableJson $json) use ($projects) {

            /**
             * Validando o tipo de dados de cada coluna
             */
            $json->whereAllType([
                '0.id'          => 'integer',
                '0.title'       => 'string',
                '0.description' => 'string',
                '0.conclusion_at'  => 'string',
            ]);

            /**
             * Validando o retorno de todas as colunas do registro
             */
            $json->hasAll(['0.id', '0.title', '0.description', '0.conclusion_at']);

            /**
             * Obtendo o primeiro registro da lista para validar o retorno
             */
            $project = (object) $projects[0];

            /**
             * Validando se os valores estao retornando corretamente
             */
            $json->whereAll([
                '0.id'          => $project->id,
                '0.title'       => $project->title,
                '0.description' => $project->description,
                '0.conclusion_at'  => $project->conclusion_at,
            ]);

        });
    }

    /**
     * Teste da rota de buscar o item na base
     */
    public function testGetSingleProjectsEndpoint(): void
    {

        /**
         * Criando registros fake na base
         */
        $project = Projects::factory(1)->createOne();

        /**
         * Acessando o endpoint
         */
        $response = $this->getJson('/api/projects/' . $project->id);
        $project = (object) $response->json();

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(200);

        /**
         * Validando os dados do response
         */
        $response->assertJson(function (AssertableJson $json) use ($project) {

            /**
             * Validando o retorno de todas as colunas do registro
             */
            $json->hasAll(['id', 'title', 'description', 'conclusion_at', 'created_at', 'updated_at', 'deleted_at']);

            /**
             * Validando o tipo de dados de cada coluna
             */
            $json->whereAllType([
                'id'          => 'integer',
                'title'       => 'string',
                'description' => 'string',
                'conclusion_at'  => 'string',
            ]);

            /**
             * Validando se os valores estao retornando corretamente
             */
            $json->whereAll([
                'id'          => $project->id,
                'title'       => $project->title,
                'description' => $project->description,
                'conclusion_at'  => $project->conclusion_at,
            ]);

        });

    }

    /**
     * Testa de rota para cadastro do item
     */
    public function testPostCreateProjectsEndpoint(): void
    {

        /**
         * Criando um objeto fake com dados para inserir na base
         */
        $project = Projects::factory(1)->makeOne()->toArray();

        /**
         * Acessando o endpoint
         */
        $response = $this->postJson('/api/projects/create', $project);
        $project = (object) $response->json();

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(201);

        /**
         * Validando os dados do response
         */
        $response->assertJson(function (AssertableJson $json) use ($project) {

            /**
             * Validando o retorno de todas as colunas do registro
             */
            $json->hasAll(['id', 'title', 'description', 'conclusion_at', 'created_at', 'updated_at']);

            /**
             * Validando se os valores estao retornando corretamente
             */
            $json->whereAll([
                'title'       => $project->title,
                'description' => $project->description,
                'conclusion_at'  => $project->conclusion_at,
            ])->etc();

        });

    }

    public function testPostCreateProjectsShouldValidateInvalid(): void
    {

        /**
         * Criando um objeto fake com dados para inserir na base
         */
        // $project = Projects::factory(1)->makeOne()->toArray();

        /**
         * Acessando o endpoint
         */
        $response = $this->postJson('/api/projects/create', []);
        $project = (object) $response->json();

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
    public function testPutProjectsEndpoint(): void
    {

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
        $response = $this->putJson('/api/projects/' . $project->id, $project_update);
        $project = (object) $response->json();

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(200);

        /**
         * Validando os dados do response
         */
        $response->assertJson(function (AssertableJson $json) use ($project) {

            /**
             * Validando o retorno de todas as colunas do registro
             */
            $json->hasAll(['id', 'title', 'description', 'conclusion_at', 'created_at', 'updated_at', 'deleted_at']);

            /**
             * Validando se os valores estao retornando corretamente
             */
            $json->whereAll([
                'title'       => $project->title,
                'description' => $project->description,
                'conclusion_at'  => $project->conclusion_at,
            ])->etc();

        });

    }

    /**
     * Teste na rota para atualizar um campo especifico na base
     */
    public function testPatchProjectsEndpoint(): void
    {

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
        $response = $this->patchJson('/api/projects/' . $project->id, $project_update);
        $project = (object) $response->json();

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(200);

        /**
         * Validando os dados do response
         */
        $response->assertJson(function (AssertableJson $json) use ($project) {

            /**
             * Validando o retorno de todas as colunas do registro
             */
            $json->hasAll(['id', 'title', 'description', 'conclusion_at', 'created_at', 'updated_at', 'deleted_at']);

            /**
             * Validando se os valores estao retornando corretamente
             */
            $json->where('conclusion_at', $project->conclusion_at);

        });

    }

    /**
     * Testa na rota para deletar o registro na base
     */
    public function testDeleteProjectsEndpoint(): void
    {

        /**
         * Criando registros fake na base
         */
        $project = Projects::factory(1)->createOne();

        /**
         * Acessando o endpoint
         */
        $response = $this->deleteJson('/api/projects/' . $project->id);

        /**
         * Validando o retorno de sucesso
         */
        $response->assertStatus(204);

    }

}
