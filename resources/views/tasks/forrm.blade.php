@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ isset($task) ? 'Editar Tarefa' : 'Nova Tarefa' }}</h1>
    <form action="{{ isset($task) ? route('tasks.update', $task->id) : route('tasks.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($task))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $task->title ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="description" class="form-control">{{ old('description', $task->description ?? "") }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Arquivo (opcional)</label>
            <input type="file" name="arquivo" class="form-control">
            @if(isset($task) && $task->attachment_url)
                <p>Arquivo atual: <a href="{{ $task->attachment_url }}" target="_blank">Visualizar</a></p>
            @endif
        </div>

        <button type="submit" class="btn btn-success">
            {{ isset($task) ? 'Atualizar' : 'Criar' }}
        </button>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Voltar</a>
    </form>
</div>
@endsection
