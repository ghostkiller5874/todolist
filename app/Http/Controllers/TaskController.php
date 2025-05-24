<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// MODELS
use App\Models\Task;
use Illuminate\Support\Facades\Storage;

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
        $tasks = Task::where('user_id', auth()->id())
            ->whereNull('deleted_at')
            ->paginate(10);

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
        // if ($request->hasFile("arquivo")) {
        //     $file = $request->file('arquivo');
        //     $extension = $request->file('arquivo')->getClientOriginalExtension();
        //     $folder = $this->extFiles($extension);

        //     //  dd([
        //     //     "arquivo" => $file,
        //     //     "extensoes" => $extension,
        //     //     "diretorio pos extensoes" => $folder
        //     // ]);

        //     $path = $file->store('arquivos' . $folder, 's3', 'public');
        //     $url = Storage::disk('s3')->url($path);
        // }


        // $task = Task::create($request->all());
        $descricao = filter_var($request->description, FILTER_SANITIZE_SPECIAL_CHARS);
        $title = filter_var($request->title, FILTER_SANITIZE_SPECIAL_CHARS);

        dd([$descricao, $title]);
        $task = Task::create([
            "user_id" => auth()->id(),
            "title" => $title,
            "description" => $descricao ?? null,
            "attachment_url" => $url ?? null
        ]);
        return response()->json($task, 201);
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
        return ['msg' => 'Task apagada'];
    }

    /**
     * Mostra todas as tasks deletadas
     */
    public function deleted()
    {
        // Tem que puxar as tasks deletadas, baseado no deleted_at
        // $task = Task::all('deleted_at');

        $tasks = Task::onlyTrashed()
            ->where('user_id', auth()->id())
            ->get();

        return response()->json($tasks);
        return $task;
    }

    /**
     * Apenas o dono da tarefa tem acesso a ela
     * @param Task $task
     */
    private function authorizeTask(Task $task)
    {
        // if ($task->user_id !== auth()->id()) {
        if ($task->user_id !== 1) {
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
}
