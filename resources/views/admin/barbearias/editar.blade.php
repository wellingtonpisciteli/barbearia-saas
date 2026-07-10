@extends('admin.layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="mb-0">
        Editar Barbearia
    </h2>

    <a href="{{ route('admin.barbearias') }}" class="btn btn-secondary">
        Voltar
    </a>

</div>


<div class="card shadow-sm border-0">

    <div class="card-body">

        <form
            id="formEditar"
            action="{{ route('admin.barbearias.update', $barbearia->id) }}"
            method="POST"
        >

            @csrf
            @method('PUT')


            <h5 class="mb-3">
                Dados da Barbearia
            </h5>


            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Nome da Barbearia
                    </label>

                    <input
                        type="text"
                        name="nome"
                        class="form-control"
                        value="{{ old('nome', $barbearia->nome) }}"
                    >

                    @error('nome')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Slug
                    </label>

                    <input
                        type="text"
                        name="slug"
                        class="form-control"
                        value="{{ old('slug', $barbearia->slug) }}"
                    >

                    @error('slug')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Telefone
                    </label>

                    <input
                        type="text"
                        name="telefone"
                        class="form-control"
                        value="{{ old('telefone', $barbearia->telefone) }}"
                    >

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Endereço
                    </label>

                    <input
                        type="text"
                        name="endereco"
                        class="form-control"
                        value="{{ old('endereco', $barbearia->endereco) }}"
                    >

                </div>

            </div>


            <hr>


            <h5 class="mb-3">
                Administrador da Barbearia
            </h5>


            <div class="row">

                @foreach($barbearia->admins as $admin)

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nome</label>

                        <input
                            type="text"
                            name="admins[{{ $admin->id }}][name]"
                            class="form-control"
                            value="{{ old("admins.{$admin->id}.name", $admin->name) }}"
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>

                        <input
                            type="email"
                            name="admins[{{ $admin->id }}][email]"
                            class="form-control"
                            value="{{ old("admins.{$admin->id}.email", $admin->email) }}"
                        >
                    </div>

                @endforeach

            </div>


            <div class="d-flex justify-content-between align-items-center mt-4">

                <div class="form-check form-switch">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="ativo"
                        name="ativo"
                        value="1"
                        {{ old('ativo', $barbearia->ativo) ? 'checked' : '' }}
                    >

                    <label class="form-check-label" for="ativo">
                        Ativo
                    </label>

                </div>

                <button
                    class="btn btn-primary"
                    type="button"
                    onclick="confirmarEditar()"
                >
                    <i class="bi bi-check-lg"></i>
                    Salvar Alterações
                </button>

            </div>

        </form>

    </div>

</div>

@endsection

@section('scripts')
<script>

function confirmarEditar()
{
    Swal.fire({
        title: 'Salvar Alterações',
        html: `
            <div>
                <p class="mt-3 mb-1 text-secondary">
                    Deseja atualizar esta barbearia?
                </p>
            </div>
        `,
        icon: 'question',
        background: '#1e1e1e',
        color: '#f5f5f5',
        showCancelButton: true,
        confirmButtonText: 'Salvar',
        cancelButtonText: 'Voltar',
        confirmButtonColor: '#0d6efd',
        cancelButtonColor: '#495057',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document
                .getElementById('formEditar')
                .submit();
        }
    });
}

</script>
@endsection