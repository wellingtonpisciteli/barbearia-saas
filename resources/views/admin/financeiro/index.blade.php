@extends('admin.layouts.app')


@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="mb-0">
        Barbearias
    </h2>

</div>


<div class="card shadow-sm border-0">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>
                        <th>
                            Nome
                        </th>

                        <th>
                            Valor
                        </th>

                        <th>
                            Assinatura
                        </th>

                        <th>
                            Próx. Cobrança
                        </th>

                        <th>
                            Responsáveis
                        </th>

                        <th class="text-end">
                            Editar
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($barbearias as $barbearia)

                    <tr>

                        <td>
                            <strong>
                                {{ $barbearia->nome }}
                            </strong>
                        </td>

                        <td>
                            @if($barbearia->assinatura)
                                R$ {{ number_format($barbearia->assinatura->valor, 2, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>

                        <td>

                            @if(!$barbearia->assinatura)

                                <span class="badge bg-secondary">
                                    Sem assinatura
                                </span>

                            @elseif($barbearia->assinatura->status == 'ativa')

                                <span class="badge bg-success">
                                    Em dia
                                </span>

                            @elseif($barbearia->assinatura->status == 'pendente')

                                <span class="badge bg-warning text-dark">
                                    Pendente
                                </span>

                            @elseif($barbearia->assinatura->status == 'inadimplente')

                                <span class="badge bg-danger">
                                    Inadimplente
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    Cancelada
                                </span>

                            @endif

                        </td>

                        <td>
                            {{ optional($barbearia->assinatura?->proxima_cobranca)->format('d/m/Y') ?? '-' }}
                        </td>


                        <td>
                            @forelse($barbearia->admins as $admin)

                                {{ $admin->name }} <br>

                            @empty

                                -

                            @endforelse
                        </td>

                        <td class="text-end">

                            <a href="{{ route('admin.financeiro.edit', $barbearia->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>

                        </td>
                        

                    </tr>

                    @endforeach

                </tbody>
            </table>

        </div>

    </div>

</div>


@endsection

