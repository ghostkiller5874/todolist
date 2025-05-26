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
        $request->validate($this->user->rules(), $this->user->feedback());

        $name = $this->filtro($request->name);
        $email = $this->filtro($request->email);
        $password = $this->filtro($request->password);
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ]);

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
        $request->validate($this->user->rulesLogin(), $this->user->feedback());
        
        $email = $this->filtro($request->email);
        $password = $this->filtro($request->password);

        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciais inválidas.'],
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso.',
        ]);
    }

    // HELPERS

    private function filtro($filtro)
    {
        $stringNova = filter_var($filtro, FILTER_SANITIZE_SPECIAL_CHARS);
        return $stringNova;
    }

    // VIEWS

    public function showRegisterForm()
    {
        return view('auth.register');
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }
}
