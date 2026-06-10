@foreach($agendamentos as $agendamento)

<p>
    Cliente:
    {{ $agendamento->cliente->nome }}
</p>

<p>
    Serviço:
    {{ $agendamento->servico->nome }}
</p>

<p>
    Telefone:
    {{ $agendamento->cliente->telefone }}
</p>

<p>
    Horário:
    {{ $agendamento->inicio->format('d/m H:i') }}
</p>

<form
    action="{{ route('barbeiro.agendamento.cancelar', $agendamento->id) }}"
    method="POST"
>
    @csrf
    @method('DELETE')

    <button type="submit">
        Finalizar
    </button>
</form>

<hr>

@endforeach

<form action="{{ route('barbeiro.logout') }}" method="POST">
    @csrf

    <button type="submit">
        Sair
    </button>
</form>