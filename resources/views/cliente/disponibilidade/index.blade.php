@extends('layouts.app')

@section('title', 'Agendamento')

@section('content')

<div class="container py-4">

    {{-- TOPO --}}
    <div class="topbar mb-4">

        <div class="text-center">

            <img
                src="{{ asset('img/logo.png') }}"
                alt="Logo"
                class="logo mb-5"
            >

            <h2 class="fw-bold mb-1">
                @if(!$cliente)
                    Seja bem vindo ao FadeOS
                @else
                    Seja bem vindo, {{ $cliente->nome }}
                @endif
            </h2>

            <p class="text-secondary mb-0">
                Escolha seu barbeiro e agende seu horário
            </p>

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

                    <h4 class="fw-bold mt-1">
                        Olá, {{ $cliente->nome }}
                    </h4>

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
                action="{{ route('cliente.cancelar', $agendamentoCliente->id) }}"
                method="POST"
                class="mt-4">

                @csrf

                <button class="btn btn-danger-custom w-100">

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

                                Serviço

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

