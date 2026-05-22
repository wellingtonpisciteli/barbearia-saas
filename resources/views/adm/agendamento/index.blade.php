@foreach($agendamentos as $agendamento)

<p>
    Cliente:
    {{ $agendamento->nome_cliente }}
</p>

<p>
    Telefone:
    {{ $agendamento->telefone_cliente }}
</p>

<p>
    Barbeiro:
    {{ $agendamento->barbeiro->name }}
</p>

<p>
    Horário:
    {{ $agendamento->inicio->format('d/m H:i') }}
</p>

<form action="{{ route('agendamento.admCancelar', $agendamento->id) }} "method="POST">
    @csrf
    @method('DELETE')

    <button type="submit">
        Cancelar
    </button>
</form>

<hr>

@endforeach
