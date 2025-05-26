<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function __construct(private User $user)
    {
        $this->user = $user;
    }
    public function register(array $data)
    {
        return $this->user->create([
            'name' => $this->filtroXss($data['name']),
            'email' => $this->filtroXss($data['email']),
            'password' => bcrypt($data['password']),
        ]);
    }

    public function login(array $data)
    {
        $credentials = [
            'email' => $this->filtroXss($data['email']),
            'password' => $this->filtroXss($data['password'])
        ];

        if (!Auth::attempt($credentials)) {
            return null;
        }

        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function logout($user)
    {
        $user->tokens()->delete();
    }

    private function filtroXss($value)
    {
        return filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
    }
}
