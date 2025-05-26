<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class AuthControllerWeb extends Controller
{
    // localhost, 127.0.0.1 , com ou sem porta. oque rolar 
    private $apiUrl = 'http://127.0.0.1:8000/api';

    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $response = Http::post("{$this->apiUrl}/login", [
            'email' => $this->filtro($request->email),
            'password' => $this->filtro($request->password)
        ]);

        if ($response->successful()) {
            $token = $response->json()['token'];

            session(['api_token' => $token]);

            return redirect()->route('tasks.index');
        }

        return back()->withErrors(['email' => 'Credenciais inválidas']);
    }
    
    public function showRegister(){
        return view('auth.register');
    }

    public function register(Request $request) {
        $response = Http::post("{$this->apiUrl}/register", [
            'name' => $this->filtro($request->name),
            'email' => $this->filtro($request->email),
            'password' => $this->filtro($request->password),
        ]);
        dd($response);
        if ($response->successful()) {
            $token = $response->json()['token'];
            session(['api_token' => $token]);
            return redirect()->route('tasks.index');
        }

        return back()->withErrors(['email' => 'Não foi possível cadastrar. Verifique os dados.']);
    }

    public function logout() {
        Http::withToken(session('api_token'))
        ->post("{$this->apiUrl}/logout");

        session()->forget('api_token');
        return redirect('/login');
    }

    private function filtro($filtro)
    {
        $stringNova = filter_var($filtro, FILTER_SANITIZE_SPECIAL_CHARS);
        return $stringNova;
    }
}
