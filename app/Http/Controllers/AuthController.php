<?php

namespace App\Http\Controllers;

use App\Services\AuthService;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(private User $user, private AuthService $authService)
    {
        $this->user = $user;
        $this->authService = $authService;
    }

        /**
     * Cadastro de usuário
     */
    public function register(Request $request)
    {
        $register = $request->validate($this->user->rules(), $this->user->feedback());

        $user = $this->authService->register($register);
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Login do usuário
     */
    public function login(Request $request)
    {
        $login = $request->validate($this->user->rulesLogin(), $this->user->feedback());

        $user = $this->authService->login($login);
        if(!$user){
            return response()->json(['message'=>'Credenciais inválidas'], 401);
        }

        return response()->json($user, 200);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $this->authService->logout($request->user());
        return response()->json([
            'message' => 'Logout realizado com sucesso.',
        ]);
    }

}
