@extends('layouts.app')

@section('title', 'Página Inicial')

@section('content')
    <form>
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Endereço de E-mail</label>
        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Seu melhor e-mail...">
        <div id="emailHelp" class="form-text">Nunca compartilharemos seu e-mail com mais ninguém.</div>
    </div>
    <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Senha</label>
        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Digite sua senha...">
    </div>
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="exampleCheck1">
        <label class="form-check-label" for="exampleCheck1">Lembrar-me</label>
    </div>
    <button type="submit" class="btn btn-primary">Logar</button>
</form>
@endsection
