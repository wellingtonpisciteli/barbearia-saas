<nav class="navbar bg-white shadow-sm px-3">


<button 
    class="btn btn-dark d-md-none"
    onclick="document.querySelector('.sidebar').classList.toggle('show')"
>

<i class="bi bi-list"></i>

</button>


<div>

<h5 class="mb-0 d-none d-md-block">
Painel Administrativo
</h5>

</div>


<div class="d-flex align-items-center gap-2">


<span class="d-none d-sm-inline">

<i class="bi bi-person-circle"></i>

{{ auth('admin')->user()->name }}

</span>


<form method="POST" action="{{ route('admin.logout') }}">

@csrf

<button class="btn btn-outline-danger btn-sm">

<i class="bi bi-box-arrow-right"></i>

<span class="d-none d-md-inline">
Sair
</span>

</button>


</form>


</div>


</nav>