@extends('admin.layouts.app')


@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="mb-0">
        Barbearias
    </h2>

    <a href="{{ route('admin.barbearias.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        Nova Barbearia
    </a>

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
                            Responsáveis
                        </th>

                        <th>
                            Usuários
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="text-end">
                            Ações
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
                            @forelse($barbearia->admins as $admin)

                                {{ $admin->name }} <br>

                            @empty

                                -

                            @endforelse
                        </td>


                        <td>
                            {{ $barbearia->users->count() }}
                        </td>


                        <td>

                            @if($barbearia->ativo)

                                <span class="badge bg-success">
                                    Ativa
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    Inativa
                                </span>

                            @endif

                        </td>


                        <td class="text-end">

                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>


                            <a href="#" class="btn btn-sm btn-outline-secondary">
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