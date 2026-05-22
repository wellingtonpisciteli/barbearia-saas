<h1>Selecione o Barbeiro</h1>

@foreach($barbeiros as $barbeiro)

    <div style="margin-bottom:15px;">

        <p>
            Nome:
            {{ $barbeiro->name }}
        </p>

        <a
            href="{{ route(
                'cliente.agenda',
                [
                    'slug' => $barbearia->slug,
                    'user' => $barbeiro->id
                ]
            ) }}"
        >

            Ver agenda

        </a>

    </div>

    <hr>

@endforeach
