@extends('layouts.app')

@section('title', 'Serviços')

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
                        Serviços
                    </h2>

                    <p class="text-secondary mb-0">
                        {{ $servicos->count() }} serviços cadastrados
                    </p>

                </div>

                <a
                    href="{{ route('barbeiro.servicos-create') }}"
                    class="btn btn-dark rounded-pill px-3 px-md-4"
                >
                    <i class="bi bi-plus-lg"></i>

                    <span class="d-none d-md-inline ms-2">
                        Novo Serviço
                    </span>
                </a>

            </div>

            <hr>

            @forelse($servicos as $servico)

                <div class="cliente-card">

                    <div class="cliente-avatar">
                        <i class="bi bi-scissors"></i>
                    </div>

                    <div class="cliente-info">

                        <div class="cliente-nome">
                            {{ $servico->nome }}
                        </div>

                        <div class="cliente-telefone">
                            <i class="bi bi-cash-stack me-2"></i>

                            R$ {{ number_format($servico->preco, 2, ',', '.') }}
                        </div>

                        <div class="cliente-telefone mt-2">
                            <i class="bi bi-clock me-2"></i>

                            {{ $servico->duracao }} minutos
                        </div>

                    </div>

                    <div class="d-flex flex-column flex-md-row gap-2 mt-3 mt-md-0">

                        {{-- EDITAR --}}
                        <a
                            href="{{ route('barbeiro.servicos-edit', $servico->id) }}"
                            class="btn btn-outline-primary no-border-btn"
                        >
                            <i class="bi bi-pencil"></i>
                        </a>

                        {{-- EXCLUIR --}}
                        <button
                            type="button"
                            class="btn btn-outline-danger no-border-btn"
                            onclick="confirmarExcluir(
                                {{ $servico->id }},
                                '{{ addslashes($servico->nome) }}'
                            )"
                        >
                            <i class="bi bi-trash"></i>
                        </button>

                        <form
                            id="formExcluir-{{ $servico->id }}"
                            action="{{ route('barbeiro.servicos-destroy', $servico->id) }}"
                            method="POST"
                            class="d-none"
                        >
                            @csrf
                            @method('DELETE')
                        </form>

                    </div>

                </div>

            @empty

                <div class="alert alert-dark">
                    Nenhum serviço cadastrado.
                </div>

            @endforelse

        </div>

        @include('barbeiro.partials.footer')

    </div>

</div>

@endsection

@section('scripts')
<script>

function confirmarExcluir(id, nome)
{
    Swal.fire({
        title: 'Excluir Serviço',
        html: `
            <div>

                <p class="mt-3 mb-1 text-secondary">
                    Serviço
                </p>

                <h4>${nome}</h4>

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