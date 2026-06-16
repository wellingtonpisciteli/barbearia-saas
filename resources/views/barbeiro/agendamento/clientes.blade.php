@extends('layouts.app')

@section('title', 'Clientes')

@section('styles')
<link
    rel="stylesheet"
    href="{{ asset('css/barbeiro.css') }}"
>
@endsection

@section('content')

<div class="dashboard-layout">

    @include('barbeiro.partials.menu')

    <div class="dashboard-content">

        <div class="container py-4">

            <div class="agenda-header mb-4">
                <div>
                    <h2 class="fw-bold mb-1">
                        Lista de clientes
                    </h2>

                    <p class="text-secondary mb-0">
                        {{ $clientes->count() }} clientes cadastrados
                    </p>
                </div>
            </div>

            <hr>

            @forelse($clientes as $cliente)

                <div class="cliente-card">

                    <div class="cliente-avatar">
                        {{ strtoupper(substr($cliente->nome, 0, 1)) }}
                    </div>

                    <div class="cliente-info">

                        <div class="cliente-nome">
                            {{ $cliente->nome }}
                        </div>

                        <div class="cliente-telefone">
                            <i class="bi bi-telephone"></i>
                            {{ $cliente->telefone }}
                        </div>

                    </div>

                    <form
                        id="formExcluir-{{ $cliente->id }}"
                        action="{{ route('barbeiro.clientes.destroy', $cliente->id) }}"
                        method="POST"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="button"
                            class="btn btn-outline-danger"
                            onclick="confirmarExcluir(
                                {{ $cliente->id }},
                                '{{ addslashes($cliente->nome) }}',
                                '{{ $cliente->telefone }}'
                            )"
                        >
                            Excluir
                        </button>
                    </form>

                </div>

            @empty

                <div class="alert alert-dark">
                    Nenhum cliente encontrado.
                </div>

            @endforelse

        </div>

        @include('barbeiro.partials.footer')

    </div>

</div>

@endsection

@section('scripts')
<script>
function confirmarExcluir(id, nome, telefone)
{
    Swal.fire({
        title: 'Excluir Cliente',
        html: `
            <div>
                <p class="mt-3 mb-1 text-secondary">
                    Cliente
                </p>
                <h4>${nome}</h4>
                <p class="mt-3 mb-1 text-secondary">
                    Telefone
                </p>
                <h4>${telefone}</h4>
            </div>
        `,
        icon: 'question',
        background: '#1e1e1e',
        color: '#f5f5f5',
        showCancelButton: true,
        confirmButtonText: 'Excluir',
        cancelButtonText: 'Voltar',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#495057',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document
                .getElementById(`formExcluir-${id}`)
                .submit();
        }
    });
}
</script>
@endsection