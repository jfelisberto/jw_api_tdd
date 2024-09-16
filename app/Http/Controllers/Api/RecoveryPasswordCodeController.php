<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordCodeRequest;
use App\Http\Requests\ResetPasswordValidateCodeRequest;
use App\Mail\SendEmailForgotPasswordCode;
use App\Models\User;
use App\Models\PasswordResetTokens;
use App\Service\ResetPasswordValidateCodeService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class RecoveryPasswordCodeController extends Controller
{
    /**
     * Retorna o codigo de validacao para alteracao da senha
     *
     * Esse metodo verifica se o usuario com e-mail informado existe na base
     * Se o usuario for encontrado, gera um codigo de recuperacao de senha e salva na base
     * Envia um e-mail para o usuario com o codigo criado
     * Se houver algum erro durante o processo retorna o mesmo para API
     * @param ForgotPasswordRequest $request O request contendo o e-mail do usuario
     * @return Resposta indicando sucesso ou falha para API
     */
    public function forgotPasswordCode(ForgotPasswordRequest $request)
    {
        /**
         * Recupera os dados do usuario baseado no e-mail
         */
        $user = User::where('email', $request->email)->first();

        /**
         * Verifica se encontrou o usuario
         */
        if (!$user) {
            /**
             * Salva um registro no log
             */
            Log::warning('Tentativa recuperar senha com e-mail não cadastrado.', ['email' => $request->email]);

            return response()->json([
                'status'  => false,
                'message' => 'E-mail não encontrado!',
            ], 400);
        }

        try {

            /**
             * Recuperar os registros recuperar senha do usuario
             */
            $userPasswordResets = PasswordResetTokens::where('email', $request->email);

            /**
             * Se existir token cadastrado para o usuario recuperar senha, excluir o mesmo
             */
            if ($userPasswordResets) {
                $userPasswordResets->delete();
            }

            /**
             * Gerar o codigo com 6 digitos
             */
            $code = mt_rand(100000, 999999);

            /**
             * Criptografar o codigo
             */
            $token = Hash::make($code);

            /**
             * Salvar o token no banco de dados
             */
            $userNewPasswordResets = PasswordResetTokens::create([
                'email'      => $request->email,
                'token'      => $token,
            ]);

            /**
             * Enviar e-mail apos cadastrar no banco de dados novo token recuperar senha
             */
            if ($userNewPasswordResets) {

                /**
                 * Obter a data atual
                 */
                $currentDate = Carbon::now();

                /**
                 * Adicionar uma hora
                 */
                $oneHourLater = $currentDate->addHour();

                /**
                 * Formatar data e hora
                 */
                $formattedTime = $oneHourLater->format('H:i');
                $formattedDate = $oneHourLater->format('d/m/Y');

                /**
                 * Dados para enviar e-mail
                 */
                Mail::to($user->email)->send(new SendEmailForgotPasswordCode($user, $code, $formattedDate, $formattedTime));
            }

            /**
             * Salvar log
             */
            Log::info('Recuperar senha.', ['email' => $request->email]);

            /*
             * Retorno da API
             */
            return response()->json([
                'status'     => true,
                'reset_code' => $code,
                'message'    => 'Enviado e-mail com instrucões para recuperar a senha. Acesse a sua caixa de e-mail para recuperar a senha!',
            ], 200);

        } catch (Exception $e) {

            /**
             * Salvar log
             */
            Log::warning('Erro ao recuperar senha.', ['email' => $request->email, 'error' => $e->getMessage()]);

            /*
             * Retorno da API
             */
            return response()->json([
                'status'  => false,
                'message' => 'Erro ao recuperar senha. Tente mais tarde!',
            ], 400);
        }
    }

    /**
     * Validar o codigo de recuperacao de senha enviado pelo usuario.
     *
     * Este metodo valida o codigo de recuperacao de senha enviado pelo usuario.
     * Utiliza o servico ResetPasswordValidateCodeService para validar o codigo. Se o codigo for valido,
     * retorna uma resposta de sucesso. Caso contrario, retorna uma resposta de erro.
     *
     * @param ResetPasswordValidateCodeRequest $request O request contendo o e-mail e o codigo de recuperacao de senha
     * @param ResetPasswordValidateCodeService $ResetPasswordValidateCodeService O servico utilizado para validar o codigo de recuperacao de senha
     * Injecao de Dependência: o Laravel automaticamente resolve e injeta uma instância dessa classe no metodo quando e chamado.
     * @return \Illuminate\Http\JsonResponse Resposta indicando sucesso ou falha na validacao do codigo
     */
    // public function resetPasswordValidateCode(ResetPasswordValidateCodeRequest $request, ResetPasswordValidateCodeService $resetPasswordValidateCode)
    public function resetPasswordValidateCode(ResetPasswordValidateCodeRequest $request, ResetPasswordValidateCodeService $resetPasswordValidateCode)
    {

        try {
            /**
             * Validar o codigo do token
             */
            $validationResult = $resetPasswordValidateCode->resetPasswordValidateCode($request->email, $request->code);

            /**
             * Verificar o resultado da validacao
             */
            if (!$validationResult['status']) {

                /*
                 * Retorno da API
                 */
                return response()->json([
                    'status'  => false,
                    'message' => $validationResult['message'],
                ], 400);

            }

            /**
             * Recuperar os dados do usuario
             */
            $user = User::where('email', $request->email)->first();

            /**
             * Verificar existe o usuario no banco de dados
             */
            if (!$user) {

                /**
                 * Salvar log
                 */
                Log::notice('Usuário não encontrado.', ['email' => $request->email]);

                /*
                 * Retorno da API
                 */
                return response()->json([
                    'status'  => false,
                    'message' => 'Usuário não encontrado!',
                ], 400);

            }

            /**
             * Salvar log
             */
            Log::info('Código de recuperar senha válido.', ['email' => $request->email]);

            /*
             * Retorno da API
             */
            return response()->json([
                'status'         => true,
                'validated_code' => $request->code,
                'message'        => 'Código de recuperar senha válido!',
            ], 200);

        } catch (Exception $e) {

            /**
             * Salvar log
             */
            Log::warning('Erro ao validar o código recuperar senha.', ['email' => $request->email, 'error' => $e->getMessage()]);

            /*
             * Retorno da API
             */
            return response()->json([
                'status'  => false,
                'message' => 'Codigo invalido!',
            ], 400);
        }
    }

    /**
     * Resetar a senha do usuario com base no codigo de recuperacao.
     *
     * Este metodo resetar a senha do usuario com base no codigo de recuperacao enviado pelo usuario.
     * Utiliza o servico ResetPasswordValidateCodeService para validar o codigo. Se o codigo for valido, atualiza a senha
     * do usuario no banco de dados e retorna uma resposta de sucesso com o token de acesso JWT.
     * Caso contrario, retorna uma resposta de erro.
     *
     * @param ResetPasswordCodeRequest $request O request contendo o e-mail, o codigo de recuperacao de senha e a nova senha
     * @param ResetPasswordValidateCodeService $resetPasswordValidateCode O servico utilizado para validar o codigo de recuperacao de senha
     * Injecao de Dependência: o Laravel automaticamente resolve e injeta uma instância dessa classe no metodo quando e chamado.
     * @return \Illuminate\Http\JsonResponse Resposta indicando sucesso ou falha na resetar da senha do usuario
     */
    public function resetPasswordCode(ResetPasswordCodeRequest $request, ResetPasswordValidateCodeService $resetPasswordValidateCode)
    {

        try {
            /**
             * Validar o codigo do token
             */
            $validationResult = $resetPasswordValidateCode->resetPasswordValidateCode($request->email, $request->code);

            /**
             * Verificar o resultado da validacao
             */
            if (!$validationResult['status']) {

                /*
                 * Retorno da API
                 */
                return response()->json([
                    'status'  => false,
                    'message' => $validationResult['message'],
                ], 400);

            }

            /**
             * Recuperar os dados do usuario
             */
            $user = User::where('email', $request->email)->first();

            /**
             * Verificar existe o usuario no banco de dados
             */
            if (!$user) {

                /**
                 * Salvar log
                 */
                Log::notice('Usuario nao encontrado.', ['email' => $request->email]);

                /*
                 * Retorno da API
                 */
                return response()->json([
                    'status'  => false,
                    'message' => 'Usuario nao encontrado!',
                ], 400);

            }

            /**
             * Alterar a senha do usuario no banco de dados
             */
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            /**
             * Recuperar os registros recuperar senha do usuario
             */
            $userPasswordResets = PasswordResetTokens::where('email', $request->email);

            /**
             * Se existir token cadastrado para o usuario recuperar senha, excluir o mesmo
             */
            if ($userPasswordResets) {
                $userPasswordResets->delete();
            }

            /**
             * Salvar log
             */
            Log::info('Senha atualizada com sucesso.', ['email' => $request->email]);

            /*
             * Retorno da API
             */
            return response()->json([
                'status'  => true,
                'message' => 'Senha atualizada com sucesso!',
            ], 200);
        } catch (Exception $e) {

            /**
             * Salvar log
             */
            Log::warning('Senha nao atualizada.', ['email' => $request->email, 'error' => $e->getMessage()]);

            /*
             * Retorno da API
             */
            return response()->json([
                'status'  => false,
                'message' => 'Senha nao atualizada!',
            ], 400);

        }
    }
}
