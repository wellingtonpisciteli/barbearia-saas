@extends('layouts.app')

@section('content')

<div class="container mt-5">

    <div class="card shadow-sm border-0 text-center p-4">

        <h3>
            Acesso indisponível
        </h3>

        <p class="text-muted mt-3">
            A barbearia {{ $barbearia->nome }} está desativada.
        </p>

        <form action="{{ route('barbeiro.logout') }}" method="POST">
            @csrf

            <button class="btn btn-secondary">
                Sair
            </button>

        </form>

    </div>

</div>

@endsection