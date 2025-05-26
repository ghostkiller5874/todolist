<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class TaskControllerWeb extends Controller
{
    // localhost, 127.0.0.1 , com ou sem porta. oque rolar 
    private $apiUrl = 'http://127.0.0.1:8000/api';

    private function getToken() {
        return session('api_token');
    }

    public function index(Request $request) {
        $response = Http::withToken($this->getToken())
            ->get("{$this->apiUrl}/tasks");

        $tasks = $response->json();

        return view('tasks.index', compact('tasks'));
    }

    public function create() {
        return view('tasks.create');
    }

    public function store(Request $request) {
        $response = Http::withToken($this->getToken())
            ->post("{$this->apiUrl}/tasks", $request->all());

        return redirect()->route('tasks.index');
    }

    public function destroy($id) {
        Http::withToken($this->getToken())
            ->delete("{$this->apiUrl}/tasks/{$id}");

        return redirect()->route('tasks.index');
    }

    
}
