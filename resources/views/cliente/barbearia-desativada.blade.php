@extends('layouts.app')

@section('content')

<div class="container text-center mt-5">

    <div class="card shadow-sm border-0 p-4">

        <h3>
            {{ $barbearia->nome }}
        </h3>

        <p class="text-muted mt-3">
            Esta barbearia está temporariamente indisponível
            para novos agendamentos.
        </p>

    </div>

</div>

@endsection