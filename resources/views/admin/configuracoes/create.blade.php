@extends('admin.layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="mb-0">
        Novo Usuário
    </h2>

    <a href="{{ route('admin.configuracoes') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Voltar
    </a>

</div>

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form
            id="formCadastrar"
            action="{{ route('admin.configuracoes.store') }}"
            method="POST"
        >

            @csrf

            <div class="row g-3">

                <div class="col-md-6">

                    <label class="form-label">
                        Nome
                    </label>

                    <input
                        type="text"
                        class="form-control @error('name') is-invalid @enderror"
                        name="name"
                        value="{{ old('name') }}"
                        required
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
                        class="form-control @error('email') is-invalid @enderror"
                        name="email"
                        value="{{ old('email') }}"
                        required
                    >

                    @error('email')
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
                        class="form-control @error('password') is-invalid @enderror"
                        name="password"
                        required
                    >

                    @error('password')
                        <div class="invalid-feedback d-block">
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
                        class="form-control"
                        name="password_confirmation"
                        required
                    >

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        Nível de acesso
                    </label>

                    <select
                        name="role"
                        class="form-select @error('role') is-invalid @enderror"
                        required
                    >

                        <option value="admin"
                            {{ old('role') == 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>

                        <option value="superAdmin"
                            {{ old('role') == 'superAdmin' ? 'selected' : '' }}>
                            Super Admin
                        </option>

                    </select>

                    @error('role')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

            <hr class="my-4">

            <div class="text-end">

                <a href="{{ route('admin.configuracoes') }}" class="btn btn-outline-secondary">
                    Cancelar
                </a>

                <button
                    type="button"
                    class="btn btn-primary"
                    onclick="confirmarCadastro()"
                >
                    <i class="bi bi-check-lg me-1"></i>

                    Cadastrar Usuário
                </button>

            </div>

        </form>

    </div>

</div>

@endsection

@section('scripts')

<script>

function confirmarCadastro()
{
    Swal.fire({

        title: 'Cadastrar Usuário',

        text: 'Deseja cadastrar este usuário?',

        icon: 'question',

        background: '#1e1e1e',

        color: '#f5f5f5',

        showCancelButton: true,

        confirmButtonText: 'Cadastrar',

        cancelButtonText: 'Voltar',

        confirmButtonColor: '#0d6efd',

        cancelButtonColor: '#495057',

        reverseButtons: true

    }).then((result) => {

        if(result.isConfirmed)
        {
            document
                .getElementById('formCadastrar')
                .submit();
        }

    });
}

</script>

@endsection