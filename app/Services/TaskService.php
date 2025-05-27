<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Storage;

class TaskService
{
    public function __construct(private Task $task)
    {
        $this->task = $task;
    }
    public function getAllTasks($userId)
    {
        return $this->task->where('user_id', $userId)
            ->whereNull('deleted_at')
            ->orderBy('title','asc')
            ->paginate(10);
    }

    public function getDeletedTasks($userId)
    {
        return $this->task->onlyTrashed()
            ->where('user_id', $userId)
            ->get();
    }

    public function createTask(array $data, $userId)
    {
        $taskData = [
            'user_id' => $userId,
            'title' => $this->filtroXss($data['title']),
            'description' => $this->filtroXss($data['description']) ?? null,
            'attachment_url' => $this->uploadFile($data['arquivo']) ?? null
        ];
        return $this->task->create($taskData);
    }

    public function updateTask(Task $task, array $data)
    {
        if (isset($data['arquivo'])) {
            if ($task->attachment_url) {
                $this->deleteFile($task->attachment_url);
            }
            $task->attachment_url = $this->uploadFile($data['arquivo']);
        }

        $task->title = $this->filtroXss($data['title']);
        $task->description = $this->filtroXss($data['description']) ?? null;
        $task->save();

        return $task;
    }

    public function deleteTask(Task $task)
    {
        return $task->delete();
    }

    public function uploadFile($file)
    {
        if (isset($file)) {
            $extension = $file->getClientOriginalExtension();
            $folder = $this->getFolderByExtension($extension);

            $path = $file->store('arquivos' . $folder, 's3', 'public');
            return Storage::disk('s3')->url($path);
        }
    }

    public function deleteFile($url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        $path = ltrim($path, '/');
        Storage::disk('s3')->delete($path);
    }

    private function getFolderByExtension($ext)
    {
        switch (strtolower($ext)) {
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
                return null;
        }
    }

    private function filtroXss($value)
    {
        return filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
    }
}
