@extends('admin.layouts.app')


@section('content')


<h2 class="mb-4">

    Dashboard

</h2>


<div class="row g-4">


    <div class="col-md-3">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <h6 class="text-muted mb-2">
                    <i class="bi bi-shop me-1"></i>
                    Barbearias
                </h6>

                <h2 class="fw-bold mb-0">
                    {{ $barbearias }}
                </h2>

            </div>

        </div>

    </div>


    <div class="col-md-3">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6 class="text-muted mb-2">
                    <i class="bi bi-people me-1"></i>
                    Usuários
                </h6>

                <h2 class="fw-bold mb-0">
                    {{ $usuarios }}
                </h2>

            </div>

        </div>

    </div>


    <div class="col-md-3">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6 class="text-muted mb-2">
                    <i class="bi-calendar-check me-1"></i>
                    Agendamentos de hoje
                </h6>

                <h2 class="fw-bold mb-0">
                    {{ $agendamentosHoje }}
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6 class="text-muted mb-2">
                    <i class="bi-calendar-check me-1"></i>
                    Agendamentos do mês
                </h6>

                <h2 class="fw-bold mb-0">
                    {{ $agendamentosMes }}
                </h2>

            </div>

        </div>

    </div>


    <div class="col-md-3">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6 class="text-muted mb-2">
                    <i class="bi-cash-stack me-1"></i>
                    Receita
                </h6>

                <h2 class="fw-bold mb-0">
                    R$ 0,00
                </h2>

            </div>

        </div>

    </div>


</div>


@endsection