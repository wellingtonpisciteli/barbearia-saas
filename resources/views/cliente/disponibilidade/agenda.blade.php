@extends('layouts.app')

@section('content')

<div class="agenda-page">
    <div class="container py-4">
        <div class="agenda-header mb-4">   
            <h1 class="agenda-title mb-1">
                {{ $barbearia->nome }}
            </h1>

            <h2 class="agenda-subtitle">
                {{ $data->translatedFormat('l') }}
            </h2>

            <hr>
        </div>
        
        <div class="schedule-card mb-4">
            <div class="schedule-info">
                <div class="info-item">
                    <small>Barbeiro</small>
                    <strong>{{ $user->name }}</strong>
                </div>

                <div class="info-item">
                    <small>Data</small>
                    <strong>{{ $data->format('d/m/Y') }}</strong>
                </div>
            </div>
        </div>

        @if(isset($servico))
            <div class="servico-card mb-4">
                <div>
                    <small class="text-secondary d-block mb-1">
                        Serviço selecionado
                    </small>

                    <strong>
                        {{ $servico->nome }}
                    </strong>
                </div>

                <span class="servico-duracao">
                    {{ $servico->duracao }} min
                </span>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <a
                href="{{ route('cliente.agenda', [
                    'slug' => $barbearia->slug,
                    'user' => $user->id,
                    'date' => $data->copy()->subDay()->format('Y-m-d'),
                    'servico_id' => request('servico_id')
                ]) }}"
                class="btn btn-nav"
            >
                ← Anterior
            </a>

            <div class="text-center">
                <strong>{{ $data->translatedFormat('d \d\e F') }}</strong>
            </div>

            <a
                href="{{ route('cliente.agenda', [
                    'slug' => $barbearia->slug,
                    'user' => $user->id,
                    'date' => $data->copy()->addDay()->format('Y-m-d'),
                    'servico_id' => request('servico_id')
                ]) }}"
                class="btn btn-nav"
            >
                Próximo →
            </a>
        </div>
        
        <p class="text-secondary mb-3">
            Horários disponíveis para agendamento.
        </p>

        {{-- HORÁRIOS --}}
        <div class="horarios-grid">
            
            @forelse($horarios as $h)
                <button
                    type="button"
                    class="btn-time"
                    data-bs-toggle="modal"
                    data-bs-target="#agendarModal"
                    onclick="setHorario('{{ $h['inicio'] }}','{{ $h['fim'] }}')"
                    {{ $h['ocupado'] ? 'disabled' : '' }}
                >
                    {{ $h['inicio'] }}
                </button>

            @empty
                <div class="empty-horarios">
                    Nenhum horário disponível
                </div>
            @endforelse
        </div>
    </div>

    {{-- MODAL --}}
    <div class="modal fade" id="agendarModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-dark">
                <form id="formAgendamento" action="{{ route('cliente.agendar') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmar Agendamento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="slug" value="{{ $barbearia->slug }}">
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <input type="hidden" name="servico_id" value="{{ request('servico_id') }}">
                        <input type="hidden" name="data" value="{{ $data->format('Y-m-d') }}">

                        <input type="hidden" name="inicio" id="horarioSelecionado" value="{{ old('inicio') }}">
                        <input type="hidden" name="fimServico" id="fimSelecionado" value="{{ old('fimServico') }}">
                        
                        <div class="mb-3">
                            <label class="form-label">Horário selecionado</label>
                            <input type="text" id="horarioPreview" class="form-control" readonly value="{{ old('inicio') && old('fimServico') ? old('inicio').' - '.old('fimServico') : '' }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nome</label>
                            <input
                                type="text"
                                name="nome_cliente"
                                class="form-control @error('nome_cliente') is-invalid @enderror"
                                value="{{ old('nome_cliente') }}"
                                required
                            >
                            @error('nome_cliente')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror 
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Telefone</label>
                            <input
                            type="text"
                            name="telefone_cliente"
                            class="form-control @error('telefone_cliente') is-invalid @enderror"
                            value="{{ old('telefone_cliente') }}"
                            required
                        >
                            @error('telefone_cliente')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror                     
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-success w-100"
                            onclick="confirmarAgendamento()"
                        >
                            Confirmar agendamento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const agendarModal = new bootstrap.Modal(document.getElementById('agendarModal'));

    function setHorario(inicio, fim)
    {
        document.getElementById('horarioSelecionado').value = inicio;
        document.getElementById('fimSelecionado').value = fim;
        document.getElementById('horarioPreview').value = `${inicio} - ${fim}`;

        agendarModal.show();
    }

    // Reabre o modal se houver erros de validação
    @if ($errors->any())
        agendarModal.show();
    @endif

    function confirmarAgendamento()
    {
        const horario = document.getElementById('horarioPreview').value;

        Swal.fire({
            title: 'Confirmar Agendamento',
            html: `
                <div>
                    <p class="mt-3 mb-1 text-secondary">
                        Barbeiro selecionado
                    </p>
                    <h4>{{ $user->name }}</h4>
                    <p class="mt-3 mb-1 text-secondary">
                        Horário selecionado
                    </p>
                    <h4>${horario}</h4>
                </div>
            `,
            icon: 'question',
            background: '#1e1e1e',
            color: '#f5f5f5',
            showCancelButton: true,
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Voltar',
            confirmButtonColor: '#198754',
            cancelButtonColor: '#495057',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formAgendamento').submit();
            }
        });
    }

</script>
@endsection