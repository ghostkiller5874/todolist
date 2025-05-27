@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Minhas Tarefas</h1>

    <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">Nova Tarefa</a>
    @if (request()->routeIs('tasks.deleted'))
        <a href="{{ route('tasks.index') }}" class="btn btn-light mb-3">Ver Minhas Tarefas</a>
    @else
        <a href="{{ route('tasks.deleted') }}" class="btn btn-warning mb-3">Ver Tarefas Deletadas</a>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Título</th>
                <th>Descrição</th>
                <th>Arquivo</th>
                <th>Estado</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->description }}</td>
                    <td>
                        @if($task->attachment_url)
                            <a href="{{ $task->attachment_url }}" target="_blank">Abrir</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($task->deleted_at)
                            <span class="badge bg-danger">Deletada</span>
                        @else
                            <span class="badge bg-success">Ativa</span>
                        @endif
                    </td>
                    <td>
                        @if(!$task->deleted_at)
                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja excluir?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Excluir</button>
                            </form>
                        @else
                            <form action="{{ route('tasks.restore', $task->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Restaurar essa tarefa?')">
                                @csrf
                                <button class="btn btn-sm btn-success">Restaurar</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Nenhuma tarefa encontrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection