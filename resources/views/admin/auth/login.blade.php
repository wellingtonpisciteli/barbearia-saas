<!DOCTYPE html>
<html>
<head>
    <title>Login Admin - FadeOS</title>
</head>

<body>

<h1>FadeOS Admin</h1>


<form method="POST" action="{{ route('admin.autenticar') }}">

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


@if($errors->any())

    {{ $errors->first() }}

@endif


</body>
</html>