@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Minhas Tarefas</h2>
    <a href="{{ route('tasks.create') }}" class="btn btn-success">Nova Tarefa</a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Título</th>
            <th>Descrição</th>
            <th>Anexo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tasks as $task)
            <tr>
                <td>{{ $task->title }}</td>
                <td>{{ $task->description }}</td>
                <td>
                    @if ($task->attachment_url)
                        <a href="{{ $task->attachment_url }}" target="_blank">Ver Anexo</a>
                    @else
                        -
                    @endif
                </td>
                <td>
                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning btn-sm">Editar</a>

                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Deseja realmente excluir?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Excluir</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
