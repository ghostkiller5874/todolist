<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

// MODELS
use App\Models\Task;
use App\Services\TaskService;

class TaskController extends Controller
{
    public function __construct(private Task $task, private TaskService $taskService)
    {
        $this->taskService = $taskService;
        $this->task = $task;
    }

    /**
     * Mostra todas as tasks do usuario, exceto as deletadas
     */
    public function index()
    {
        $tasks = $this->taskService->getAllTasks(auth()->id());
        return response()->json($tasks);
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

        $request->validate($this->task->rules(), $this->task->feedback());
        
        $task = $this->taskService->createTask($request->all(), auth()->id());
        return response()->json($task, 201);
    }

    /**
     * Atualiza uma task do usuario
     */
    public function update(Request $request, Task $task)
    {

        $this->authorizeTask($task);

        $request->validate($this->task->rules(), $this->task->feedback());

        $updated = $this->taskService->updateTask($task, $request->all());

        return response()->json($updated, 200);
    }

    /**
     * Deleta uma task do usuario
     */
    public function destroy(Task $task)
    {
        $this->authorizeTask($task);
        // $task->delete();
        $this->taskService->deleteTask($task);
        return response()->json(['message' => 'Tarefa excluída com sucesso.'], 200);
    }

    /**
     * Mostra todas as tasks deletadas
     */
    public function deleted()
    {
        $tasks = $this->taskService->getDeletedTasks(auth()->id());

        if ($tasks->isEmpty()) {
            return response()->json(['message' => 'Nenhuma tarefa deletada encontrada.'], 404);
        }

        return response()->json($tasks);
        // return $task;
    }

    /**
     * Apenas o dono da tarefa tem acesso a ela
     * @param Task $task
     */
    private function authorizeTask(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Acesso não autorizado.');
        }
    }

}
