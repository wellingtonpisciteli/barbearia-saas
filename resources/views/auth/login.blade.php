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

    <div class="password-field">
        <input
            type="password"
            name="password"
            id="password"
            placeholder="Senha"
            required
        >

        <button
            type="button"
            id="togglePassword"
            class="toggle-password"
            aria-label="Mostrar senha"
        >
            <i class="bi bi-eye" style="color: rgb(80, 80, 80)"></i>
        </button>
    </div>

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

@section('scripts')
<script>
    const password = document.getElementById('password');
    const toggle = document.getElementById('togglePassword');
    const icon = toggle.querySelector('i');

    toggle.addEventListener('click', () => {
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });
</script>
@endsection
