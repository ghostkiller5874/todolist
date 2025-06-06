<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;


class AuthControllerWeb extends Controller
{
    // localhost, 127.0.0.1 , com ou sem porta. oque rolar 
    // private $apiUrl = 'http://127.0.0.1:8000/api';


    public function __construct(private User $user, private AuthService $authService)
    {
        $this->user = $user;
        $this->authService = $authService;
    }
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {

        $login = $request->validate($this->user->rulesLogin(), $this->user->feedback());


        $user = $this->authService->login(['email' => $login['email'], 'password' => $login['password']]);
        if (!$user) {
            return back()->withErrors(['email' => 'Credenciais inválidas']);
        }

        session(['api_token' => $user['token']]);
        session(['user_id' => $user['user']['id']]);

        return redirect()->route('tasks.index');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $register = $request->validate($this->user->rules(), $this->user->feedback());
        

        $user = $this->authService->register(['name'=>$register['name'],'email'=>$register['email'], 'password'=>$register['password']]);
        $token = $user->createToken('api-token')->plainTextToken;

        if(!$user){
            return back()->withErrors(['email' => 'Não foi possível cadastrar. Verifique os dados.']);
        }

        session(['api_token' => $token]);
        session(['user_id' => $user->id]);

        return redirect()->route('tasks.index');
        
    }

    public function logout()
    {
        $this->authService->logout(Auth::user());
        session()->forget(['api_token','user_id']);
        return redirect('/login');
    }

}