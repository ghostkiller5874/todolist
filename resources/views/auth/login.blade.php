@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">Login</div>
    <div class="card-body">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Senha</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary">Entrar</button>
        </form>
        <a href="{{ route('register') }}" class="mt-3">Cadastre-se conosco!</a>
    </div>
</div>
@endsection
