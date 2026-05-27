<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet"
>

<h1 class="mb-4">
    Agenda de {{ $user->name }}
</h1>

<div class="mb-4">

    <h2 class="fs-5">
        {{ $data->translatedFormat('l • d/m') }}
    </h2>

    <hr>

    @if($cliente && $status == 'confirmado')
        <div class="alert alert-success">
            Olá, {{ $cliente->nome }} 👋
        </div>
    @endif

    <div class="row g-2">

        @forelse($horarios as $h)

        <div class="col-4">

            <button
                type="button"
                class="btn w-100 {{ $h['ocupado'] ? 'btn-secondary' : 'btn-dark' }}"
                data-bs-toggle="modal"
                data-bs-target="#agendarModal"
                onclick="setHorario('{{ $h['inicio'] }}','{{ $h['fim'] }}')"
                {{ $h['ocupado'] ? 'disabled' : '' }}
            >
                {{ $h['inicio'] }}
            </button>

        </div>

        @empty

            <p>Nenhum horário disponível</p>

        @endforelse

    </div>

</div>


{{-- MODAL --}}
<div class="modal fade" id="agendarModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form action="{{ route('cliente.agendar') }}" method="POST">
                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        Confirmar Agendamento
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <input type="hidden" name="slug" value="{{ $barbearia->slug }}">
                    <input type="hidden" name="user_id" value="{{ $user->id }}">

                    <input type="hidden" name="inicio" id="horarioSelecionado">
                    <input type="hidden" name="fimServico" id="fimSelecionado">

                    {{-- CLIENTE NÃO EXISTE AINDA --}}
                    @if(!$cliente || $status == 'cancelado')
                        <div class="mb-3">
                            <label class="form-label">Nome</label>
                            <input type="text" name="nome_cliente" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Telefone</label>
                            <input type="text" name="telefone_cliente" class="form-control" required>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Agendando como:
                            <strong>{{ $cliente->nome }}</strong>
                        </div>
                    @endif

                </div>

                <div class="modal-footer">

                    <button type="submit" class="btn btn-success w-100">
                        Confirmar agendamento
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

function setHorario(inicio, fim)
{
    document.getElementById('horarioSelecionado').value = inicio;
    document.getElementById('fimSelecionado').value = fim;
}

</script>