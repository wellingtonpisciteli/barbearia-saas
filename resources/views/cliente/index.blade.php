@extends('layouts.app')

@section('title', 'Agendamento')

@section('styles')
<link
    rel="stylesheet"
    href="{{ asset('css/cliente.css') }}"
>
@endsection

@section('content')

<div class="container py-4">

    {{-- TOPO --}}
    <div class="topbar mb-4">

        <div class="text-center">

            <img
                src="{{ $barbearia->logo_url ?? asset('img/barbearia/logo.png') }}"
                alt="Logo"
                class="logo"
            >

            <h2 class="fw-bold mb-1">
                @if(!$cliente)
                    Seja bem vindo ao FadeOS
                @else
                    Seja bem vindo, {{ $cliente->nome }}
                @endif
            </h2>

            @if($agendamentoCliente && $agendamentoCliente->status == 'confirmado')
                <p class="text-secondary mb-0">
                    Verifique os dados do seu agendamento
                </p>
            @else
                <p class="text-secondary mb-0">
                    Escolha seu barbeiro e agende seu horário
                </p>
            @endif

        </div>

    </div>

    {{-- CLIENTE POSSUI AGENDAMENTO --}}
    @if($agendamentoCliente && $agendamentoCliente->status == 'confirmado')

        <div class="schedule-card success-card">

            <div class="d-flex justify-content-between align-items-start mb-3">

                <div>

                    <small class="text-gold">
                        AGENDAMENTO CONFIRMADO
                    </small>

                </div>

            </div>

            <div class="schedule-info">

                <div class="info-item">

                    <small>
                        Data
                    </small>

                    <strong>
                        {{ $agendamentoCliente->inicio->format('d/m/Y') }}
                    </strong>

                </div>

                <div class="info-item">

                    <small>
                        Horário
                    </small>

                    <strong>
                        {{ $agendamentoCliente->inicio->format('H:i') }}
                    </strong>

                </div>


                <div class="info-item">

                    <small>
                        Barbeiro
                    </small>

                    <strong>
                        {{ $agendamentoCliente->barbeiro->name }}
                    </strong>

                </div>

                <div class="info-item">

                    <small>
                        Serviço
                    </small>

                    <strong>
                        {{ $agendamentoCliente->servico->nome ?? 'Serviço' }}                    
                    </strong>

                </div>

            </div>

            <form
                id="formCancelarAgendamento"
                action="{{ route('cliente.cancelar', $agendamentoCliente->id) }}"
                method="POST"
                class="mt-4">

                @csrf

                <button
                    type="button"
                    class="btn btn-danger-custom w-100"
                    onclick="confirmarCancelamento()"
                >
                    Cancelar agendamento
                </button>

            </form>

        </div>

    @else

        {{-- LISTAGEM BARBEIROS --}}
        <div class="row g-3">

            @foreach($barbeiros as $barbeiro)

                <div class="col-lg-4 col-md-6">

                    <div class="barber-card">

                        <div class="d-flex align-items-center mb-3">

                            <div class="avatar me-3">

                                {{ strtoupper(substr($barbeiro->name, 0, 1)) }}

                            </div>

                            <div>

                                <h5 class="fw-bold mb-0">
                                    {{ $barbeiro->name }}
                                </h5>

                                <small class="text-secondary">
                                    Barbeiro profissional
                                </small>

                            </div>

                        </div>

                        <form
                            method="GET"
                            action="{{ route('cliente.agenda', [
                                'slug' => $barbearia->slug,
                                'user' => $barbeiro->id
                            ]) }}">

                            <label class="form-label small text-secondary mb-2">
                                Escolher serviço
                            </label>

                            <select
                                name="servico_id"
                                class="form-select custom-select"
                                required>

                                @foreach($servicos as $s)

                                    <option value="{{ $s->id }}">

                                        {{ $s->nome }}
                                        •
                                        {{ $s->duracao }} min

                                    </option>

                                @endforeach

                            </select>

                            <button
                                class="btn btn-gold w-100 mt-3">

                                Ver agenda

                            </button>

                        </form>

                    </div>

                </div>

            @endforeach

        </div>

    @endif

</div>

@include('components.footer')

@endsection

@if($agendamentoCliente && $agendamentoCliente->status == 'confirmado')
@section('scripts')
<script>
    function confirmarCancelamento()
    {
        Swal.fire({
            title: 'Cancelar Agendamento',
            html: `
                <div>
                    <p class="mt-3 mb-1 text-secondary">
                        Dados do agendamento
                    </p>

                    <h5>
                        {{ $agendamentoCliente->inicio->format('d/m/Y') }}
                        às
                        {{ $agendamentoCliente->inicio->format('H:i') }}
                    </h5>

                    <p class="mt-3 mb-1 text-secondary">
                        Esta ação não poderá ser desfeita.
                    </p>
                </div>
            `,
            icon: 'warning',
            background: '#121212',
            color: '#ffffff',
            showCancelButton: true,
            confirmButtonText: 'Sim, cancelar',
            cancelButtonText: 'Voltar',
            confirmButtonColor: '#B91C1C',
            cancelButtonColor: '#343a40',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document
                    .getElementById('formCancelarAgendamento')
                    .submit();
            }
        });
    }
    </script>
@endsection
@endif

