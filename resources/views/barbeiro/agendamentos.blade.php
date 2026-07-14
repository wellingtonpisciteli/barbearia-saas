@extends('layouts.app')

@section('title', 'Agenda')

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
                        Olá, {{ Auth::user()->name }}
                    </h2>

                    <p class="text-secondary mb-0">
                        Agenda de Hoje
                    </p>
                </div>

            </div>

            <hr>

            @forelse($agendamentos as $agendamento)

                <div class="agenda-card">

                    <div class="agenda-hour">
                        {{ $agendamento->inicio->format('H:i') }}
                    </div>

                    <div class="agenda-info">

                        <div class="agenda-cliente">
                            {{ $agendamento->cliente->nome }}
                        </div>

                        <div class="agenda-servico">
                            {{ $agendamento->servico->nome }}
                        </div>

                        <div class="agenda-telefone">

                            <i class="bi bi-telephone"></i>

                            {{ $agendamento->cliente->telefone }}

                        </div>

                    </div>

                    <div class="agenda-actions">

                        <form
                            id="formFinalizar-{{ $agendamento->id }}"
                            action="{{ route('barbeiro.agendamento.cancelar', $agendamento->id) }}"
                            method="POST"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="button"
                                class="btn btn-outline-danger"
                                onclick="confirmarFinalizar(
                                    {{ $agendamento->id }},
                                    '{{ addslashes($agendamento->cliente->nome) }}',
                                    '{{ $agendamento->cliente->telefone }}'
                                )"
                            >
                                Finalizar
                            </button>

                        </form>

                    </div>

                </div>

            @empty

                <div class="alert alert-dark">
                    Nenhum agendamento hoje.
                </div>

            @endforelse

            <hr style="margin-top: 30px">

            <div class="row g-3">

                <div class="col-md-3">

                    <div class="stats-card">

                        <div class="stats-icon">
                            <i class="bi bi-calendar-day"></i>
                        </div>

                        <div>

                            <small>Agendamentos de hoje</small>

                            <h2>{{ $agendamentosHoje }}</h2>

                        </div>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="stats-card">

                        <div class="stats-icon">
                            <i class="bi bi-calendar-week"></i>
                        </div>

                        <div>

                            <small>Agendamentos do mês</small>

                            <h2>{{ $agendamentosMes }}</h2>

                        </div>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="stats-card">

                        <div class="stats-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>

                        <div>

                            <small>Total de agendamentos</small>

                            <h2>{{ $agendamentosTotal }}</h2>

                        </div>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="stats-card">

                        <div class="stats-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>

                        <div>

                            <small>Total de cancelados</small>

                            <h2>{{ $agendamentosCancelados }}</h2>

                        </div>

                    </div>

                </div>

            </div>

        </div>
        
        @include('barbeiro.partials.footer')

    </div>

</div>

@endsection

@section('scripts')
<script>
function confirmarFinalizar(id, nome, telefone)
{
    Swal.fire({
        title: 'Finalizar Agendamento',
        html: `
            <div>
                <p class="mt-3 mb-1 text-secondary">
                    Cliente
                </p>
                <h4>${nome}</h4>
                <p class="mt-3 mb-1 text-secondary">
                    Telefone
                </p>
                <h4>${telefone}</h4>
            </div>
        `,
        icon: 'question',
        background: '#1e1e1e',
        color: '#f5f5f5',
        showCancelButton: true,
        confirmButtonText: 'Finalizar',
        cancelButtonText: 'Voltar',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#495057',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document
                .getElementById(`formFinalizar-${id}`)
                .submit();
        }
    });
}
</script>
@endsection