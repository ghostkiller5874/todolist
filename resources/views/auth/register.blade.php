@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">Cadastro</div>
    <div class="card-body">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label>Nome</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Senha</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary">Cadastrar</button>
        </form>
        <a href="{{ route('login') }}" class="mt-3">Já tem conta? Login</a>
    </div>
</div>
@endsection
