@extends('admin.layouts.app')


@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="mb-0">
        Nova Barbearia
    </h2>

    <a href="{{ route('admin.barbearias') }}" class="btn btn-secondary">
        Voltar
    </a>

</div>


<div class="card shadow-sm border-0">

    <div class="card-body">


        <form 
            action="{{ route('admin.barbearias.store') }}" 
            method="POST"
        >

            @csrf


            <h5 class="mb-3">
                Dados da Barbearia
            </h5>


            <div class="row">


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Nome da Barbearia
                    </label>

                    <input 
                        type="text"
                        name="nome"
                        class="form-control"
                        placeholder="Ex: Fade Barber"
                        value="{{ old('nome') }}"
                    >

                    @error('nome')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Slug
                    </label>

                    <input 
                        type="text"
                        name="slug"
                        class="form-control"
                        placeholder="fade-barber"
                        value="{{ old('slug') }}"
                    >

                    @error('slug')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Telefone
                    </label>

                    <input 
                        type="text"
                        name="telefone"
                        class="form-control"
                        value="{{ old('telefone') }}"
                    >

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Endereço
                    </label>

                    <input 
                        type="text"
                        name="endereco"
                        class="form-control"
                        value="{{ old('endereco') }}"
                    >

                </div>


            </div>


            <hr>


            <h5 class="mb-3">
                Administrador da Barbearia
            </h5>


            <div class="row">


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Nome
                    </label>

                    <input 
                        type="text"
                        name="admin_nome"
                        class="form-control"
                        value="{{ old('admin_nome') }}"
                    >

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Email
                    </label>

                    <input 
                        type="email"
                        name="admin_email"
                        class="form-control"
                        value="{{ old('admin_email') }}"
                    >

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Senha
                    </label>

                    <input 
                        type="password"
                        name="admin_password"
                        class="form-control"
                    >

                </div>


            </div>


            <button class="btn btn-primary">
                <i class="bi bi-check-lg"></i>
                Salvar Barbearia
            </button>


        </form>


    </div>

</div>


@endsection