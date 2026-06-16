@extends('layouts.app')

@section('title', 'Login')

@section('styles')
<link
    rel="stylesheet"
    href="{{ asset('css/login.css') }}"
>
@endsection

@section('content')


<form method="POST" action="{{ route('barbeiro.autenticar') }}" class="login-form">
    @csrf

    <img
        src="{{ asset('img/sistema/logoFadeOS.png') }}"
        alt="FadeOS"
        class="logo"
    >

    <h2>Bem-Vindo ao FadeOS</h2>

    <input
        type="email"
        name="email"
        placeholder="Email"
        required
    >

    <input
        type="password"
        name="password"
        placeholder="Senha"
        required
    >

    <div class="forgot-password">
        <a href="#">
            Esqueci minha senha
        </a>
    </div>

    <button type="submit">
        Entrar
    </button>
</form>

@endsection