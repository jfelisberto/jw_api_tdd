<?php

namespace App\Http\Controllers\Api;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserStoreRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Jetstream;

class AuthController extends Controller
{
    use PasswordValidationRules;

    /**
     * Login do usuario
     *
     * Esse metodo efetua o loging do usuario validando o e-mail e a senha
     *
     * Se confirmado cria um token para o usuario e o retorna na resposta da API junto ao seu id
     *
     * @param \Illuminate\Http\Request {'email', 'password'}
     * @return \Illuminate\Http\JsonResponse Uma resposta JSON indicado o status do login, o token, o user_id e uma mensagem correspondente
     */
    public function login(Request $request)
    {

        $code = 200;
        $message = 'Login realizado com sucesso';
        $response = [];
        $status = true;

        /**
         * Validar E-mail e a Senha
         */
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            /**
             * Obtenho os dados do usuário
             */
            $user = Auth::user();

            /**
             * Obtenho o token para o usuario
             */
            $token = $request->user()->createToken('api-token')->plainTextToken;

            /**
             * Passando os dados para o response
             */
            $message .= ' - Para o Postman o token já será atribuido a variável da Collection';
            $response['token'] = $token;
            $response['token_type'] = 'Bearer';
            $response['user_id'] = $user->id;
        } else {
            /**
             * Passando o dados para o response na falha de autenticacao
             */
            $status = false;
            $message = 'Credenciais invalidas';
            $code = 404;
        }

        /**
         * Retorno as informacoes para API
         */
        $response['status'] = $status;
        $response['message'] = $message;

        return response($response, $code);
    }

    /**
     * Deslogar o usuario
     *
     * Este metodo revoga todos os token associados ao usuario efetuando assim o logout
     *
     * Se o logout for bem sucessido, retorna uma resposta JSON indicando sucesso
     *
     * Se ocorrer alguma falha, retorna uma  resposta JSON indicando a falha
     *
     * @param \App\Models\User $user O usuario para o qual logout sera efetuado;
     * @return \Illuminate\Http\JsonResponse Uma resposta JSON indicado o status do logout e uma mensagem correspondente
     */
    public function logout($id)
    {
        try {

            /**
             * Busca o usuario
             */
            if ($user = User::find($id)) {
                /**
                 * Apaga todos os tokens relacionado ao usuario selecionado
                 */
                $user->tokens()->delete();

                /**
                 * Retorno da API
                 */
                $status = true;
                $message = 'Deslogado com sucesso';
                $code = 200;
            } else {
                /**
                 * Retorno da API em caso de erro
                 */
                $status = false;
                $message = 'Houve um erro ao processar o logout';
                $code = 400;
            }


        } catch(Exception $e) {
            /**
             * Retorno da API em caso de erro
             */
            $status = false;
            $message = 'Houve um erro ao processar o logout';
            $code = 400;
        }

        /**
         * Retorno as informacoes para API
         */
        $response['status'] = $status;
        $response['message'] = $message;

        return response($response, $code);
    }

    /**
     * Registra o usuario
     *
     * Este metodo registra o usuario na base
     *
     * Se a criacao for bem sucessido, retorna uma resposta JSON indicando sucesso
     *
     * Se ocorrer alguma falha, retorna uma  resposta JSON indicando a falha
     *
     * @param \App\Models\User $user O usuario para o qual logout sera efetuado;
     * @return \Illuminate\Http\JsonResponse Uma resposta JSON indicado o status do logout e uma mensagem correspondente
     */
    public function create(UserStoreRequest $request)
    {

        $code = 200;
        $message = 'Usuário cadastrado com sucesso';
        $response = [];
        $status = true;
        $user = false;

        try {
            /**
             * Insere o susário no banco
             */
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            /**
             * Valido se foi criado corretamente o usuario
             */
            if ($user) {
                $response['user_id'] = $user->id;
            } else {
                /**
                 * Retorno da API
                 */
                $response['user_id'] = $user;
                $status = false;
                $message = 'Houve um erro ao cadastrar o usuário';
                $code = 404;
            }
        } catch (Exception $e) {
            /**
             * Retorno da API
             */
            $status = false;
            $message = 'Houve um erro ao cadastrar o usuário, tente novamente mais tarde';
        }

        /**
         * Retorno as informacoes para API
         */
        $response['status'] = $status;
        $response['message'] = $message;

        return response($response, $code);
    }
}
