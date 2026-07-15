@extends('admin.layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="mb-0">
        Gerenciar Assinatura
    </h2>

    <a href="{{ route('admin.financeiro') }}" class="btn btn-secondary">
        Voltar
    </a>

</div>

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form
            id="formEditar"
            action="{{ route('admin.financeiro.update', $barbearia->id) }}"
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
                        class="form-control"
                        value="{{ $barbearia->nome }}"
                        readonly
                    >

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Responsável
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ optional($barbearia->admins->first())->name }}"
                        readonly
                    >

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Cidade
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $barbearia->cidade }}"
                        readonly
                    >

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Gateway
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ ucfirst($barbearia->assinatura->gateway) }}"
                        readonly
                    >

                </div>

            </div>

            <hr>

            <h5 class="mb-3">
                Assinatura
            </h5>

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="form-label">
                        Valor
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        name="valor"
                        class="form-control"
                        value="{{ old('valor', $barbearia->assinatura->valor) }}"
                    >

                </div>

                <div class="col-md-4 mb-3">

                    <label class="form-label">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-select"
                    >

                        <option value="ativa"
                            {{ $barbearia->assinatura->status == 'ativa' ? 'selected' : '' }}>
                            Ativa
                        </option>

                        <option value="pendente"
                            {{ $barbearia->assinatura->status == 'pendente' ? 'selected' : '' }}>
                            Pendente
                        </option>

                        <option value="inadimplente"
                            {{ $barbearia->assinatura->status == 'inadimplente' ? 'selected' : '' }}>
                            Inadimplente
                        </option>

                        <option value="cancelada"
                            {{ $barbearia->assinatura->status == 'cancelada' ? 'selected' : '' }}>
                            Cancelada
                        </option>

                    </select>

                </div>

                <div class="col-md-4 mb-3">

                    <label class="form-label">
                        Próxima Cobrança
                    </label>

                    <input
                        type="date"
                        name="proxima_cobranca"
                        class="form-control"
                        value="{{ old('proxima_cobranca', optional($barbearia->assinatura->proxima_cobranca)->format('Y-m-d')) }}"
                    >

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Início da Assinatura
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ optional($barbearia->assinatura->inicio)->format('d/m/Y') }}"
                        readonly
                    >

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Encerramento
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ optional($barbearia->assinatura->fim)->format('d/m/Y') ?? '-' }}"
                        readonly
                    >

                </div>

            </div>

            <hr>

            <div class="form-check form-switch mb-4">

                <input
                    class="form-check-input"
                    type="checkbox"
                    id="ativo"
                    name="ativo"
                    value="1"
                    {{ $barbearia->assinatura->barbearia->ativo ? 'checked' : '' }}
                >

                <label
                    class="form-check-label"
                    for="ativo"
                >
                    Barbearia ativa
                </label>

            </div>

            <div class="d-flex justify-content-end">

                <button
                    type="button"
                    class="btn btn-primary"
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

        text: 'Deseja atualizar esta assinatura?',

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

        if(result.isConfirmed)
        {
            document
                .getElementById('formEditar')
                .submit();
        }

    });
}

</script>

@endsection