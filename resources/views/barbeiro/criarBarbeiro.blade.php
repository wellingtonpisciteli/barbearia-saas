@extends('layouts.app')

@section('title', 'Novo Barbeiro')

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
                        Criar Barbeiro
                    </h2>
                </div>

                <a href="{{ route('barbeiro.barbeiros') }}" class="btn btn-danger rounded-pill px-3 px-md-4">
                    <i class="bi bi-arrow-left-circle"></i>
                    <span class="d-none d-md-inline ms-2">Voltar</span>
                </a>
            </div>

            <hr>

            <div class="form-card">

                <form id="formCriar" 
                    action="{{ route('barbeiro.criarBarbeiro') }}" 
                    method="POST">

                    @csrf

                    <div class="row g-4">

                        <div class="col-md-6">
                            <label class="form-label">
                                Nome
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="Digite o nome do barbeiro"
                            >

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                E-mail
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="Digite o e-mail"
                            >

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Telefone
                            </label>

                            <input
                                type="text"
                                name="telefone"
                                class="form-control @error('telefone') is-invalid @enderror"
                                value="{{ old('telefone') }}"
                                placeholder="(00) 00000-0000"
                            >

                            @error('telefone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Tipo de usuário
                            </label>

                            <select
                                name="role"
                                class="form-control form-select @error('role') is-invalid @enderror"
                            >
                                <option value="">Selecione</option>

                                <option
                                    value="colaborador"
                                    @selected(old('role') === 'colaborador')
                                >
                                    Colaborador
                                </option>

                                <option
                                    value="admin"
                                    @selected(old('role') === 'admin')
                                >
                                    Administrador
                                </option>
                            </select>

                            @error('role')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Início do expediente
                            </label>

                            <input
                                type="time"
                                step="60"
                                name="inicio"
                                class="form-control @error('inicio') is-invalid @enderror"
                                value="{{ old('inicio') }}"
                            >

                            @error('inicio')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Início do intervalo
                            </label>

                            <input
                                type="time"
                                name="intervalo_inicio"
                                class="form-control @error('intervalo_inicio') is-invalid @enderror"
                                value="{{ old('intervalo_inicio') }}"
                            >

                            @error('intervalo_inicio')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Fim do intervalo
                            </label>

                            <input
                                type="time"
                                name="intervalo_fim"
                                class="form-control @error('intervalo_fim') is-invalid @enderror"
                                value="{{ old('intervalo_fim') }}"
                            >

                            @error('intervalo_fim')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Fim do expediente
                            </label>

                            <input
                                type="time"
                                name="fim"
                                class="form-control @error('fim') is-invalid @enderror"
                                value="{{ old('fim') }}"
                            >

                            @error('fim')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Senha
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Digite uma senha"
                            >

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Confirmar Senha
                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Confirme a senha"
                            >

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>

                    <hr style="margin-top: 20px">
                    
                    <!-- Dias de trabalho -->
                    <div class="col-12" style="margin-top: -15px">
                        <label class="form-label">
                            Dias de trabalho
                        </label>

                        <div class="row">
                            @foreach ([
                                1 => 'Segunda',
                                2 => 'Terça',
                                3 => 'Quarta',
                                4 => 'Quinta',
                                5 => 'Sexta',
                                6 => 'Sábado',
                                7 => 'Domingo'
                            ] as $valor => $dia)

                                <div class="col-md-3 col-6 mb-2">
                                    <div class="form-check">

                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="dias_semana[]"
                                            value="{{ $valor }}"
                                            id="dia{{ $valor }}"
                                            @checked(
                                                in_array(
                                                    $valor,
                                                    old('dias_semana', [])
                                                )
                                            )
                                        >

                                        <label
                                            class="form-check-label"
                                            for="dia{{ $valor }}"
                                        >
                                            {{ $dia }}
                                        </label>

                                    </div>
                                </div>

                            @endforeach
                        </div>

                        @error('dias_semana')
                            <div class="text-danger mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button
                            type="button"
                            class="btn btn-gold"
                            onclick="confirmarCriar()"
                        >
                            <i class="bi bi-check-circle me-2"></i>
                            Salvar Barbeiro
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
        title: 'Adicionar Barbeiro',
        html: `
            <div>
                <p class="mt-3 mb-1 text-secondary">
                    Criar Novo Barbeiro
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
                .getElementById(`formCriar`)
                .submit();
        }
    });
}
</script>
@endsection

