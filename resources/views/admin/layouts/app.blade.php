<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>FadeOS Admin</title>


    {{-- Bootstrap --}}
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >


    {{-- Bootstrap Icons --}}
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" 
        rel="stylesheet"
    >


    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">    

</head>

<script>

document.addEventListener('click', function(e){

    const sidebar = document.querySelector('.sidebar');

    const button = document.querySelector('.btn-dark');


    if(
        window.innerWidth <= 768 &&
        sidebar.classList.contains('show') &&
        !sidebar.contains(e.target) &&
        !button.contains(e.target)
    ){

        sidebar.classList.remove('show');

    }

});

</script>

<body>


{{-- Sidebar Desktop/Mobile --}}
@include('admin.partials.sidebar')


<div class="content">


    @include('admin.partials.navbar')


    <main class="p-3 p-md-4">

        @yield('content')

    </main>


</div>


<script 
src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@yield('scripts')

</body>

</html>