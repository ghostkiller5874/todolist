<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// MODELS
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Mostra todas as tasks do usuario, exceto as deletadas
     */
    public function index()
    {
        $task = Task::all();
        return $task;
    }

    /**
     * Mostra uma Task do usuario
     * @param Task $task
     * @return json
     */
    public function show(Task $task)
    {
        $this->authorizeTask($task);
        return response()->json($task, 200);
    }

    /**
     * Cria uma task 
     */
    public function store(Request $request)
    {
        $task = Task::create($request->all());
        return $task;
    }

    /**
     * Atualiza uma task do usuario
     */
    public function update(Request $request, Task $task)
    {
        $task->update($request->all());
        return $task;
    }

    /**
     * Deleta uma task do usuario
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return ['msg'=>'Task apagada'];
    }

    /**
     * Apenas o dono da tarefa tem acesso a ela
     * @param Task $task
     */
    private function authorizeTask(Task $task)
    {
        if ($task->user_id !== auth()) {
            abort(403, 'Acesso não autorizado.');
        }
    }

    /**
     * Mostra todas as tasks deletadas
     */
    public function deleted()
    {
        // Tem que puxar as tasks deletadas, baseado no deleted_at
        $task = Task::all('deleted_at');
        return $task;
    }

}
