
<form method="POST" action="{{ route('barbeiro.autenticar') }}">
    @csrf

    <input
        type="email"
        name="email"
        placeholder="Email"
    >

    <input
        type="password"
        name="password"
        placeholder="Senha"
    >

    <button type="submit">
        Entrar
    </button>
</form>