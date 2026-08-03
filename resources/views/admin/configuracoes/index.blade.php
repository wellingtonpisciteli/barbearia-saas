@extends('admin.layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="mb-0">
        Configurações
    </h2>

</div>

<div class="row g-4">

    {{-- Usuários --}}
    <div class="col-12">

        <div class="card shadow-sm border-0">

            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-1">
                        Usuários do Sistema
                    </h5>

                    <small class="text-muted">
                        Gerencie os administradores do FadeOS.
                    </small>

                </div>

                <a href="{{ route('admin.configuracoes.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus me-1"></i>
                    Novo Usuário
                </a>

            </div>

            <div class="card-body">

                
                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>

                            <tr>

                                <th>Nome</th>

                                <th>E-mail</th>

                                <th>Nível</th>

                                <th class="text-end">
                                    Editar
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($admins as $admin)

                                <tr>

                                    <td>
                                        <strong>{{ $admin->name }}</strong>
                                    </td>

                                    <td>
                                        {{ $admin->email }}
                                    </td>

                                    <td>

                                        <span class="badge {{ $admin->role == 'super_admin' ? 'bg-primary' : 'bg-secondary' }}">
                                            {{ $admin->role == 'superAdmin' ? 'Super Admin' : 'Admin' }}
                                        </span>

                                    </td>

                                    <td class="text-end">

                                        <a href="{{ route('admin.configuracoes.edit', $admin->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="4" class="text-center text-muted py-4">
                                        Nenhum usuário encontrado.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection