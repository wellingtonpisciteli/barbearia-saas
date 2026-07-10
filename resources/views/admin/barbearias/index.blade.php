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

                            <button
                                type="button"
                                class="btn btn-sm btn-outline-danger"                                    
                                onclick="confirmarExcluir(
                                    {{ $barbearia->id }},
                                    '{{ addslashes($barbearia->nome) }}'
                                )"
                                >
                                    <i class="bi bi-trash"></i>
                            </button>

                            <form action="{{ route('admin.barbearias.destroyBarbearia', $barbearia->id) }}"
                                id="formExcluir-{{ $barbearia->id }}"
                                method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                            </form>


                            <a href="{{ route('admin.barbearias.edit', $barbearia->id) }}" class="btn btn-sm btn-outline-primary">
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

@section('scripts')
<script>
function confirmarExcluir(id, nome)
{
    Swal.fire({
        title: 'Excluir Barbearia',
        html: `
            <div>
                <p class="mt-3 mb-1 text-secondary">
                    Barbearia
                </p>
                <h4>${nome}</h4>
            </div>
        `,
        icon: 'question',
        background: '#1e1e1e',
        color: '#f5f5f5',
        showCancelButton: true,
        confirmButtonText: 'Excluir',
        cancelButtonText: 'Voltar',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#495057',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document
                .getElementById(`formExcluir-${id}`)
                .submit();
        }
    });
}
</script>
@endsection