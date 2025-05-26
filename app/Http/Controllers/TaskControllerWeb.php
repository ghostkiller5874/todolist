<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class TaskControllerWeb extends Controller
{
    // localhost, 127.0.0.1 , com ou sem porta. oque rolar 
    // private $apiUrl = 'http://127.0.0.1:8000/api';

    public function __construct(private Task $tasks, private TaskService $taskService)
    {
        $this->tasks = $tasks;
        $this->taskService = $taskService;
    }

    private function getToken()
    {
        return session('api_token');
    }

    private function getUserId()
    {
        return session('user_id');
    }

    public function index(Request $request)
    {
        if (!session()->has('api_token')) {
            return redirect()->route('login');
        }
        $tasks = $this->taskService->getAllTasks($this->getUserId());

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        if (!session()->has('api_token')) {
            return redirect()->route('login');
        }
        return view('tasks.forrm');
    }

    public function store(Request $request)
    {
        if (!session()->has('api_token')) {
            return redirect()->route('login');
        }
        $request->validate($this->tasks->rules(), $this->tasks->feedback());

        $this->taskService->createTask($request->all(), $this->getUserId());
        return redirect()->route('tasks.index');
    }

    public function edit($id)
    {
        if (!session()->has('api_token')) {
            return redirect()->route('login');
        }
        $task = $this->tasks->findOrFail($id);
        return view('tasks.forrm', compact('task'));
    }
    public function update(Request $request, $id)
    {
        if (!session()->has('api_token')) {
            return redirect()->route('login');
        }
        $task = $this->tasks->findOrFail($id);

        $request->validate($this->tasks->rules(), $this->tasks->feedback());

        $response = $this->taskService->updateTask($task, $request->all());
        if ($response) {
            return redirect()->route('tasks.index')->with('success', 'Tarefa atualizada com sucesso.');
        }


        return back()->withErrors('Erro ao atualizar tarefa');
    }

    public function destroy($id)
    {
        if (!session()->has('api_token')) {
            return redirect()->route('login');
        }
        
        $task = $this->tasks->findOrFail($id);

        if ($task->user_id !== $this->getUserId()) {
            abort(403, 'Acesso não autorizado.');
        }

        $this->taskService->deleteTask($task);

        return redirect()->route('tasks.index');
    }

    public function deleted()
    {
        if (!session()->has('api_token')) {
            return redirect()->route('login');
        }
        $tasks = Task::onlyTrashed()->where('user_id', $this->getUserId())->get();

        return view('tasks.index', compact('tasks'));
    }

    public function restore($id)
    {
        if (!session()->has('api_token')) {
            return redirect()->route('login');
        }
        $task = Task::onlyTrashed()->where('id', $id)->where('user_id', $this->getUserId())->firstOrFail();

        $task->restore();

        return redirect()->route('tasks.index')->with('success', 'Tarefa restaurada com sucesso!');
    }
}
