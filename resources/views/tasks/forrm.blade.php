@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        {{ isset($task) ? 'Editar Tarefa' : 'Nova Tarefa' }}
    </div>
    <div class="card-body">
        <form method="POST"
              action="{{ isset($task) ? route('tasks.update', $task->id) : route('tasks.store') }}"
              enctype="multipart/form-data">
            @csrf
            @if (isset($task))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label>Título</label>
                <input type="text" name="title" class="form-control"
                       value="{{ old('title', $task->title ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label>Descrição</label>
                <textarea name="description" class="form-control">{{ old('description', $task->description ?? '') }}</textarea>
            </div>
            <div class="mb-3">
                <label>Anexo (opcional)</label>
                <input type="file" name="attachment" class="form-control">
                @if (isset($task) && $task->attachment_url)
                    <p>Arquivo atual: <a href="{{ $task->attachment_url }}" target="_blank">Ver</a></p>
                @endif
            </div>
            <button class="btn btn-primary">Salvar</button>
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
