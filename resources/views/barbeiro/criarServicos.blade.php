@extends('layouts.app')

@section('title', 'Novo Serviço')

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
                        Criar Serviço
                    </h2>

                </div>

                <a
                    href="{{ route('barbeiro.servicos') }}"
                    class="btn btn-danger rounded-pill px-3 px-md-4"
                >
                    <i class="bi bi-arrow-left-circle"></i>

                    <span class="d-none d-md-inline ms-2">
                        Voltar
                    </span>
                </a>

            </div>

            <hr>

            <div class="form-card">

                <form
                    id="formCriar"
                    action="{{ route('barbeiro.servicos-store') }}"
                    method="POST"
                >

                    @csrf

                    <div class="row g-4">

                        <div class="col-md-6">

                            <label class="form-label">
                                Nome do Serviço
                            </label>

                            <input
                                type="text"
                                name="nome"
                                class="form-control @error('nome') is-invalid @enderror"
                                value="{{ old('nome') }}"
                                placeholder="Ex: Corte Masculino"
                            >

                            @error('nome')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-3">

                            <label class="form-label">
                                Valor (R$)
                            </label>

                            <input
                                type="text"
                                name="valor"
                                class="form-control @error('valor') is-invalid @enderror"
                                value="{{ old('valor') }}"
                                placeholder="10,00"
                            />

                            @error('valor')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-3">

                            <label class="form-label">
                                Duração (min)
                            </label>

                            <input
                                type="number"
                                min="5"
                                step="5"
                                name="duracao"
                                class="form-control @error('duracao') is-invalid @enderror"
                                value="{{ old('duracao') }}"
                                placeholder="30"
                            >

                            @error('duracao')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                    <div class="form-actions mt-4">

                        <button
                            type="button"
                            class="btn btn-gold"
                            onclick="confirmarCriar()"
                        >
                            <i class="bi bi-check-circle me-2"></i>

                            Salvar Serviço
                        </button>

                    </div>

                </form>

            </div>

        </div>

        @include('barbeiro.partials.footer')

    </div>

</div>

@endsection

@section('scripts')
<script>

function confirmarCriar()
{
    Swal.fire({
        title: 'Adicionar Serviço',
        html: `
            <div>
                <p class="mt-3 mb-1 text-secondary">
                    Deseja cadastrar este serviço?
                </p>
            </div>
        `,
        icon: 'question',
        background: '#1e1e1e',
        color: '#f5f5f5',
        showCancelButton: true,
        confirmButtonText: 'Adicionar',
        cancelButtonText: 'Voltar',
        confirmButtonColor: '#0d6efd',
        cancelButtonColor: '#495057',
        reverseButtons: true
    }).then((result) => {

        if (result.isConfirmed) {

            document
                .getElementById('formCriar')
                .submit();

        }

    });
}

document.querySelector('input[name="valor"]').addEventListener('input', function (e) {
    let value = e.target.value;

    value = value.replace(/\D/g, '');
    value = (value / 100).toFixed(2) + '';
    value = value.replace('.', ',');

    e.target.value = value;
});

</script>
@endsection