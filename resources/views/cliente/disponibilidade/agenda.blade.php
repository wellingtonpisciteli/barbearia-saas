<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<h1 class="mb-4">
    Agenda de {{ $user->name }}
</h1>

@foreach($disponibilidades as $disp)

<div class="mb-4 p-3 border rounded">

    <h2 class="fs-5">
        {{ \Carbon\Carbon::today()->translatedFormat('l • d/m') }}
    </h2>

    <p>
        <strong>Funcionamento:</strong>
        {{ $disp->inicio }} às {{ $disp->fim }}
    </p>

    <hr>

    <div class="row g-2">

        @php
            $inicio = \Carbon\Carbon::parse($disp->inicio);
            $fim = \Carbon\Carbon::parse($disp->fim);
        @endphp

        @while($inicio < $fim)

            @php
                $horaAtual = $inicio->format('H:i');
                $ocupado = in_array($horaAtual, $agendados);
            @endphp

            <div class="col-4">

                <button
                    type="button"
                    class="btn w-100 {{ $ocupado ? 'btn-secondary' : 'btn-dark' }}"
                    data-bs-toggle="modal"
                    data-bs-target="#agendarModal"
                    onclick="setHorario('{{ $horaAtual }}')"
                    {{ $ocupado ? 'disabled' : '' }}
                >
                    {{ $horaAtual }}
                </button>

            </div>

            @php
                $inicio->addMinutes($disp->intervalo);
            @endphp

        @endwhile

    </div>

</div>

@endforeach

{{-- MODAL --}}
<div class="modal fade" id="agendarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('cliente.agendar') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Agendamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="slug" value="{{ $barbearia->slug }}">
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <input type="hidden" name="horario" id="horarioSelecionado">

                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" name="nome_cliente" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Telefone</label>
                        <input type="text" name="telefone_cliente" class="form-control" required>
                    </div>

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
    function setHorario(horario) {
        document.getElementById('horarioSelecionado').value = horario;
    }
</script>