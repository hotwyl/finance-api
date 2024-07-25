<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthForgotPasswordRequest;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Requests\AuthResetPasswordRequest;
use App\Http\Requests\AuthUpdatePasswordRequest;
use App\Http\Resources\AuthResource;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(AuthRegisterRequest $request)
    {
        try {
            // função para Validar nivel de segurança da senha (deve conter letra maiusculas, letra minusculas, números, caracteres especiais, minimos de 8 caracteres)
            if (!$resultado = validarSenha($request->input('password'))) {
                // Retornar erros de validação para o cliente
                return AuthResource::make(['status' => false, 'message' => array('errors' => $resultado['errors'])], 422);
            }

            if(!validarEmail($request->input('email'))) {
                return AuthResource::make(['status' => false, 'message' => 'Email inválido'], 422);
            }

            $validatedData = $request->validated();

            $validatedData['password'] = Hash::make($request->password);

            if(!$user = User::create($validatedData)){
                return AuthResource::make(['status' => false, 'message' => 'Erro ao cadastrar usuário.'], 401);
            }

            $accessToken = $user->createToken('authToken')->plainTextToken;

            return AuthResource::make(['status' => true, 'message' => 'Usuário Cadastrado com sucesso.','user' => $user, 'access_token' => $accessToken], 200);

        } catch (\Exception $ex) {
            return AuthResource::make(['status' => false, 'message' => $ex->getMessage()], 401);
        }
    }

    public function login(AuthLoginRequest $request)
    {
        try {
            $user = User::where('email', $request->input('email'))->first();

            if(!$user){
                return AuthResource::make([
                    'status' => false,
                    'message' => 'Credenciais inválidas.',
                ], 401);
            }

            if (Auth::attempt($request->validated())) {
                $user = Auth::user();
                $accessToken = $user->createToken('authToken')->plainTextToken;
                return AuthResource::make(['status'=> true, 'user' => $user, 'access_token' => $accessToken]);
            }

            return AuthResource::make(['error' => 'Unauthorized'], 401);
        } catch (\Exception $ex) {
            return AuthResource::make([
                'status' => 'false',
                'message' => $ex->getMessage()
            ], 401);
        }
    }

    public function logout()
    {
        try {
            $user = Auth::user();
            $user->tokens->each(function ($token, $key) {
                $token->delete();
            });
            return AuthResource::make(['status' => true, 'message' => 'Successfully logged out']);
        } catch (\Exception $ex) {
            return AuthResource::make([
                'status' => false,
                'message' => $ex->getMessage()
            ], 401);
        }
    }

    public function user()
    {
        try {
            $user = Auth::user();

            return AuthResource::make([
                'status' => true,
                'message' => 'Usuario Logado.',
                'user' => $user
            ], 200);

        } catch (\Exception $ex) {
            return AuthResource::make([
                'status' => false,
                'message' => $ex->getMessage()
            ], 401);
        }
    }

    public function unlogged()
    {
        return AuthResource::make(['status' => false, 'message' => 'Usuário não autenticado.'], 401);
    }

    public function updateUser(Request $request)
    {
        $user = $request->user();
        $user->update($request->all());
        return AuthResource::make($user);
    }

    public function refresh()
    {
        try {
            // Obter o usuário autenticado
            $user = Auth::user();
            // Revogar o token de acesso do usuário
            $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
            // Gerar um novo token de acesso
            $accessToken = $user->createToken('authToken')->plainTextToken;
            // Retornar o usuário e o novo token de acesso
            return AuthResource::make(['status' => true, 'user' => $user, 'access_token' => $accessToken]);
        } catch (\Exception $ex) {
            return AuthResource::make([
                'status' => false,
                'message' => $ex->getMessage()
            ], 401);
        }
    }

    public function forgotPassword(AuthForgotPasswordRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                $user->sendPasswordResetNotification($user->createToken('password_reset')->plainTextToken);
                return AuthResource::make(['status' => true, 'message' => 'Password reset link sent to your email']);
            }
            return AuthResource::make(['status' => false, 'message' => 'User not found'], 404);
        } catch (\Exception $ex) {
            return AuthResource::make([
                'status' => false,
                'message' => $ex->getMessage()
            ], 401);
        }
    }

    public function resetPassword(AuthResetPasswordRequest $request)
    {
        try {

            $user = User::where('email', $request->email)->first();
            if ($user) {
                if ($user->tokens->where('name', 'password_reset')->where('token', $request->token)->first()) {
                    $user->update(['password' => Hash::make($request->password)]);
                    return AuthResource::make(['status' => true, 'message' => 'Password reset successfully']);
                }
                return AuthResource::make(['status' => false, 'message' => 'Invalid token'], 401);
            }
            return AuthResource::make(['status' => false, 'message' => 'User not found'], 404);
        } catch (\Exception $ex) {
            return AuthResource::make([
                'status' => false,
                'message' => $ex->getMessage()
            ], 401);
        }
    }

    public function updatePassword(AuthUpdatePasswordRequest $request)
    {
        try {
            $user = Auth::user();
            if (Hash::check($request->current_password, $user->password)) {
                $user->update(['password' => Hash::make($request->new_password)]);
                return AuthResource::make(['status' => true, 'message' => 'Password updated successfully']);
            }
            return AuthResource::make(['status' => false, 'message' => 'Invalid password'], 401);
        } catch (\Exception $ex) {
            return AuthResource::make([
                'status' => false,
                'message' => $ex->getMessage()
            ], 401);
        }
    }

    public function me()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                throw new \Exception('Usuário não autenticado.');
            }

            return AuthResource::make([
                'status' => true,
                'message' => 'Informações do usuário recuperadas com sucesso.',
                'user' => $user
            ], 200);

        } catch (\Exception $ex) {
            return AuthResource::make([
                'status' => false,
                'message' => $ex->getMessage()
            ], 401);
        }
    }
}
