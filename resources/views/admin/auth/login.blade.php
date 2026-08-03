@extends('admin.layouts.auth')

@section('title', 'Login Administrativo')

@section('content')

<style>

    body {
        background: #0f172a;
    }

    .login-card {
        background: #1e293b;
        border-radius: 18px;
    }

    .text-muted {
        color: #94a3b8 !important;
    }

    .form-label {
        color: #e2e8f0;
    }

    .form-control {
        background: #0f172a;
        border-color: #334155;
        color: #f8fafc;
    }

    .form-control:focus {
        background: #0f172a;
        color: #f8fafc;
        border-color: #3b82f6;
        box-shadow: 0 0 0 .25rem rgba(59,130,246,.25);
    }

    .form-control::placeholder {
        color: #64748b;
    }

    .input-group-text {
        background: #334155;
        border-color: #334155;
        color: #94a3b8;
    }

    .btn-primary {
        background: #2563eb;
        border-color: #2563eb;
    }

    .btn-primary:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
    }

    .brand-icon {

        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(59,130,246,.15);

        display:flex;
        align-items:center;
        justify-content:center;

        margin:auto;

    }

</style>


<div class="row justify-content-center align-items-center min-vh-100">

    <div class="col-md-6 col-lg-4">

        <div class="card login-card shadow border-0">

            <div class="card-body p-5">


                <div class="text-center mb-4">


                    <div class="brand-icon mb-3">

                        <i
                            class="bi bi-shield-lock-fill text-info"
                            style="font-size:3rem"
                        ></i>

                    </div>


                    <h2 class="fw-bold mt-3 mb-1 text-white">
                        FadeOS
                    </h2>


                    <p class="text-muted mb-0">
                        Painel Administrativo
                    </p>


                </div>



                @if($errors->any())

                    <div class="alert alert-danger">

                        {{ $errors->first() }}

                    </div>

                @endif



                <form method="POST" action="{{ route('admin.autenticar') }}">

                    @csrf



                    <div class="mb-3">


                        <label class="form-label">
                            E-mail
                        </label>


                        <div class="input-group">


                            <span class="input-group-text">

                                <i class="bi bi-envelope"></i>

                            </span>



                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email') }}"
                                placeholder="Digite seu e-mail"
                                required
                                autofocus
                            >


                        </div>


                    </div>




                    <div class="mb-4">


                        <label class="form-label">
                            Senha
                        </label>


                        <div class="input-group">


                            <span class="input-group-text">

                                <i class="bi bi-lock"></i>

                            </span>



                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Digite sua senha"
                                required
                            >


                        </div>


                    </div>




                    <button
                        class="btn btn-primary w-100"
                        type="submit"
                    >

                        <i class="bi bi-box-arrow-in-right me-2"></i>

                        Entrar

                    </button>



                </form>


            </div>


        </div>



        <div class="text-center mt-4 text-muted small">

            © {{ date('Y') }} FadeOS

        </div>


    </div>


</div>


@endsection