<!-- MOBILE -->

<div class="mobile-header d-lg-none">

    <button
        class="btn btn-outline-light"
        data-bs-toggle="offcanvas"
        data-bs-target="#menuBarbeiro"
    >
        ☰
    </button>

    <div class="agenda-date">
        {{ now()->format('d/m/Y') }}
    </div>

</div>

<div
    class="offcanvas offcanvas-start text-white"
    tabindex="-1"
    id="menuBarbeiro"
>

    <div class="offcanvas-header">

        <h5>Administração</h5>

        <button
            type="button"
            class="btn-close btn-close-white"
            data-bs-dismiss="offcanvas"
        ></button>

    </div>

    <div class="offcanvas-body d-flex flex-column">

        <a href="{{ route('barbeiro.agendamento.index') }}" class="sidebar-link">
            Agenda
        </a>

        <a href="{{ route('barbeiro.clientes') }}" class="sidebar-link">
            Clientes
        </a>

        <a href="{{ route('barbeiro.barbeiros') }}" class="sidebar-link">
            Barbeiros
        </a>

        <a href="{{ route('barbeiro.configuracoes') }}" class="sidebar-link">
            Barbearia
        </a>

        <a href="{{ route('barbeiro.servicos') }}" class="sidebar-link">
            Serviços
        </a>

        <form
            action="{{ route('barbeiro.logout') }}"
            method="POST"
            class="mobile-logout"
        >
            @csrf

            <button
                type="submit"
                class="btn btn-danger w-100"
            >
                Sair
            </button>

        </form>

    </div>

</div>

<!-- DESKTOP -->

<aside class="sidebar d-none d-lg-flex">

    <div>

        <h3 class="sidebar-title">
            Administração
        </h3>
        <hr>

        <a href="{{ route('barbeiro.agendamento.index') }}" class="sidebar-link">
            Agenda
        </a>

        <a href="{{ route('barbeiro.clientes') }}" class="sidebar-link">
            Clientes
        </a>

        <a href="{{ route('barbeiro.barbeiros') }}" class="sidebar-link">
            Barbeiros
        </a>

        <a href="{{ route('barbeiro.configuracoes') }}" class="sidebar-link">
            Barbearia
        </a>

        <a href="{{ route('barbeiro.servicos') }}" class="sidebar-link">
            Serviços
        </a>

    </div>

    <form
        action="{{ route('barbeiro.logout') }}"
        method="POST"
        class="sidebar-logout"
    >
        @csrf

        <button
            type="submit"
            class="btn btn-outline-danger w-100"
        >
            Sair
        </button>

    </form>

</aside>