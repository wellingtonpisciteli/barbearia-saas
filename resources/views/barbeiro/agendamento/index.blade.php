@extends('layouts.app')

@section('title', 'Home')

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
                            action="{{ route('barbeiro.agendamento.cancelar', $agendamento->id) }}"
                            method="POST"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-success"
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

        </div>
        
        @include('barbeiro.partials.footer')

    </div>

</div>

@endsection