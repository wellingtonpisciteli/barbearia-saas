@extends('layouts.app')

@section('title', 'Barbeiros')

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
                        Lista de barbeiros
                    </h2>

                    <p class="text-secondary mb-0">
                        {{ $barbeiros->count() }} barbeiros cadastrados
                    </p>
                </div>

                @auth
                    @if(auth()->user()->role === \App\Models\User::ROLE_ADMIN)
                        <a href="{{ route('barbeiro.novoBarbeiro') }}" class="btn btn-dark rounded-pill px-3 px-md-4">
                            <i class="bi bi-person-plus-fill"></i>
                            <span class="d-none d-md-inline ms-2">Novo Barbeiro</span>
                        </a>
                    @endif
                @endauth
            </div>

            <hr>

            @forelse($barbeiros as $barbeiro)

                <div class="cliente-card">

                    <div class="cliente-avatar">
                        {{ strtoupper(substr($barbeiro->name, 0, 1)) }}
                    </div>

                    <div class="cliente-info">

                        <div class="cliente-nome">
                            {{ $barbeiro->name }}
                        </div>

                        <div class="cliente-telefone">
                            <i class="bi bi-envelope-fill me-2"></i>
                            {{ $barbeiro->email }}
                        </div>

                        <div class="cliente-telefone mt-2">
                            <i class="bi bi-telephone-fill me-2"></i>
                            {{ $barbeiro->telefone ?? 'Não informado' }}
                        </div>
                    </div>

                    @auth
                        @if(auth()->user()->role === \App\Models\User::ROLE_ADMIN)

                            <div class="d-flex flex-column flex-md-row gap-2 mt-3 mt-md-0">

                                {{-- EDITAR --}}
                                <a
                                    href="{{ route('barbeiro.createEditar', $barbeiro->id) }}"
                                    class="btn btn-outline-primary w-100 w-md-auto no-border-btn"
                                >
                                    <i class="bi bi-pencil me-1"></i>
                                </a>

                                {{-- EXCLUIR --}}
                                <button
                                    type="button"
                                    class="btn btn-outline-danger w-100 w-md-auto no-border-btn"
                                    onclick="confirmarExcluir(
                                        {{ $barbeiro->id }},
                                        '{{ addslashes($barbeiro->name) }}',
                                        '{{ $barbeiro->email }}'
                                    )"
                                >
                                    <i class="bi bi-trash me-1"></i>
                                </button>

                                <form
                                    id="formExcluir-{{ $barbeiro->id }}"
                                    action="{{ route('barbeiro.barbeiros.destroyBarbeiro', $barbeiro->id) }}"
                                    method="POST"
                                    class="d-none"
                                >
                                    @csrf
                                    @method('DELETE')
                                </form>

                            </div>

                        @endif
                    @endauth
                </div>

            @empty

                <div class="alert alert-dark">
                    Nenhum barbeiro encontrado.
                </div>

            @endforelse

        </div>

        @include('barbeiro.partials.footer')

    </div>

</div>

@endsection

@section('scripts')
<script>
function confirmarExcluir(id, nome, email)
{
    Swal.fire({
        title: 'Excluir Barbeiro',
        html: `
            <div>
                <p class="mt-3 mb-1 text-secondary">
                    Barbeiro
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