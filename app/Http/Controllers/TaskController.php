<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

// MODELS
use App\Models\Task;


class TaskController extends Controller
{
    private $task;
    public function __construct(Task $task)
    {
        $this->task = $task;
    }
    /**
     * Mostra todas as tasks do usuario, exceto as deletadas
     */
    public function index()
    {
        $tasks = $this->task->where('user_id', auth()->id())
            ->whereNull('deleted_at')
            ->paginate(10);
        // $tasks= Task::all();
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
        if ($request->hasFile("arquivo")) {
            $file = $request->file('arquivo');
            $extension = $request->file('arquivo')->getClientOriginalExtension();
            $folder = $this->extFiles($extension);

            //  dd([
            //     "arquivo" => $file,
            //     "extensoes" => $extension,
            //     "diretorio pos extensoes" => $folder
            // ]);

            $path = $file->store('arquivos' . $folder, 's3', 'public');
            $url = Storage::disk('s3')->url($path);
        }


        $description = $this->filtro($request->description);
        $title = $this->filtro($request->title);

        // dd([$description, $title]);
        $task = $this->task->create([
            "user_id" => auth()->id(),
            "title" => $title,
            "description" => $description ?? null,
            "attachment_url" => $url ?? null
        ]);
        return response()->json($task, 201);
    }

    /**
     * Atualiza uma task do usuario
     */
    public function update(Request $request, Task $task)
    {

        $this->authorizeTask($task);

        $validated = $request->validate($this->task->rules(), $this->task->feedback());

        if ($request->hasFile('arquivo')) {
            // Se já tiver um arquivo, exclui
            if ($task->attachment_url) {
                $this->deleteFromS3($task->attachment_url);
            }

            $file = $request->file('arquivo');
            $extension = $request->file('arquivo')->getClientOriginalExtension();
            $folder = $this->extFiles($extension);

            $path = $file->store('arquivos' . $folder, 's3', 'public');
            $url = Storage::disk('s3')->url($path);
        }

        $task->update([
            'title' => $this->filtro($validated['title']) ?? $this->filtro($task->title),
            'description' => $this->filtro($validated['description']) ?? $this->filtro($task->description),
            'attachment_url' => $url ?? null,
        ]);

        return response()->json($task, 200);
    }

    /**
     * Deleta uma task do usuario
     */
    public function destroy(Task $task)
    {
        $this->authorizeTask($task);
        $task->delete();
        return response()->json(['message' => 'Tarefa excluída com sucesso.'], 200);
    }

    /**
     * Mostra todas as tasks deletadas
     */
    public function deleted()
    {
        $tasks = $this->task->onlyTrashed()
            ->where('user_id', auth()->id())
            ->get();

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

    /**
     * Retorna o diretorio apropriado para cada tipo de arquivo
     */
    private function extFiles($path)
    {
        switch (strtolower($path)) {
            case 'png':
            case 'jpg':
            case 'jpeg':
                return '/images';

            case 'doc':
            case 'docx':
            case 'txt':
                return '/documents';

            case 'pdf':
                return '/pdf';

            default:
                return '';
        }
    }

    private function filtro($filtro)
    {
        $stringNova = filter_var($filtro, FILTER_SANITIZE_SPECIAL_CHARS);
        return $stringNova;
    }
}
