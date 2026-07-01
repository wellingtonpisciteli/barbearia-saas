@extends('layouts.app')

@section('title', 'Configurações')

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
                        Barbearia
                    </h2>

                    <p class="text-secondary mb-0">
                        Personalize sua barbearia.
                    </p>
                </div>

            </div>

            <hr>

            <form
                id="formUpdate"
                action="{{ route('barbeiro.configuracoes-atualizar') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf

                <div class="agenda-card mb-4">

                    <div class="agenda-info w-100">

                        <h5 class="fw-bold mb-3">
                            Logo da Barbearia
                        </h5>

                        <div class="d-flex align-items-center gap-4 flex-wrap">

                            <img
                                id="logoPreview"
                                src="{{ $barbearia->logo_url ?? asset('img/barbearia/logo.png') }}"
                                class="rounded-circle border object-fit-cover"
                                width="120"
                                height="120"
                                alt="Logo da barbearia"
                            />

                            <div>

                                <input
                                    type="file"
                                    id="logoInput"
                                    name="logo"
                                    class="form-control"
                                    accept=".png,.jpg,.jpeg,.webp"
                                >

                                <small class="text-secondary">
                                    PNG, JPG ou WEBP.
                                </small>

                            </div>

                        </div>

                    </div>
                </div>

                <div class="agenda-card">

                    <div class="agenda-info w-100">

                        <h5 class="fw-bold mb-3">
                            Informações da Barbearia
                        </h5>

                        <div class="mb-3">

                            <label class="form-label">
                                Nome da Barbearia
                            </label>

                            <input
                                type="text"
                                name="nome"
                                class="form-control"
                                placeholder="Digite o nome da barbearia"
                                value="{{ old('nome', $barbearia->nome ?? '') }}"
                            >

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Telefone da Barbearia
                            </label>

                            <input
                                type="text"
                                name="telefone"
                                id="telefone"
                                class="form-control"
                                placeholder="(00) 00000-0000"
                                value="{{ old('telefone', $barbearia->telefone_formatado ?? '') }}"                            >

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Instagram da Barbearia
                            </label>

                            <input
                                type="text"
                                name="instagram"
                                class="form-control"
                                placeholder="Digite o @ ou usuário do Instagram"
                                value="{{ old('instagram', $barbearia->instagram ?? '') }}"
                            >

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Endereço da Barbearia
                            </label>

                            <input
                                type="text"
                                name="endereco"
                                class="form-control"
                                placeholder="Digite o endereço da barbearia"
                                value="{{ old('endereco', $barbearia->endereco ?? '') }}"
                            >

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Cidade e Estado da Barbearia
                            </label>

                            <input
                                type="text"
                                name="cidade"
                                class="form-control"
                                placeholder="Digite a cidade da barbearia"
                                value="{{ old('nome', $barbearia->cidade ?? '') }}"
                            >

                        </div>


                    </div>

                </div>

                <div class="text-end mt-4">

                    <button
                        type="button"
                        class="btn btn-gold px-4"
                        onclick="confirmarUpdate()"
                    >
                        Salvar Alterações
                    </button>

                </div>

            </form>

        </div>

        @include('barbeiro.partials.footer')

    </div>

</div>

@endsection

@section('scripts')
<script>

document.getElementById('telefone').addEventListener('input', function (e) {

    let value = e.target.value.replace(/\D/g, '');

    value = value.replace(/^(\d{2})(\d)/, '($1) $2');
    value = value.replace(/(\d{5})(\d)/, '$1-$2');
    value = value.substring(0, 15);

    e.target.value = value;
});

function confirmarUpdate()
{
    Swal.fire({
        title: 'Atualizar Dados',
        html: `
            <div>
                <p class="mt-3 mb-1 text-secondary">
                    Salvar Alterações
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
                .getElementById(`formUpdate`)
                .submit();
        }
    });
}

document.querySelector('input[name="logo"]').addEventListener('change', function (e) {

    const file = e.target.files[0];

    if (!file) return;

    const maxSizeMB = 2;

    if (file.size > maxSizeMB * 1024 * 1024) {
        alert('A imagem deve ter no máximo 2MB');
        e.target.value = '';
    }

});

document.getElementById('logoInput').addEventListener('change', function (e) {

    const file = e.target.files[0];

    if (!file) return;

    const reader = new FileReader();

    reader.onload = function (event) {
        document.getElementById('logoPreview').src = event.target.result;
    };

    reader.readAsDataURL(file);

});

</script>
@endsection