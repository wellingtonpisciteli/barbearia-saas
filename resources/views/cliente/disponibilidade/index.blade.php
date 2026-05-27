<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet"
>

<div class="container mt-4">

    {{-- CLIENTE TEM AGENDAMENTO --}}
    @if($agendamentoCliente && $agendamentoCliente->status == 'confirmado')

        <div class="alert alert-success">
            <h3>Olá, seja bem vindo {{ $cliente->nome }} </h3>
            <h4>Você já possui um agendamento</h4>

            <p>
                Data:
                {{ $agendamentoCliente->inicio->format('d/m/Y H:i') }}
            </p>

            <p>
                Barbeiro:
                {{ $agendamentoCliente->barbeiro->name }}
            </p>

            <p>
                Status:
                {{ $agendamentoCliente->status }}
            </p>


            <form
                action="{{ route('cliente.cancelar', $agendamentoCliente->id) }}"
                method="POST">

                @csrf

                <button
                    class="btn btn-danger">

                    Cancelar agendamento

                </button>

            </form>

        </div>

    @else

        {{-- MOSTRA BARBEIROS NORMALMENTE --}}
        <h1 class="mb-4">
            Selecione o barbeiro
        </h1>

        @foreach($barbeiros as $barbeiro)

            <div class="card mb-3">

                <div class="card-body">

                    <p>
                        <strong>Nome:</strong>
                        {{ $barbeiro->name }}
                    </p>

                    <form
                    method="GET"
                    action="{{ route('cliente.agenda', [
                        'slug'=>$barbearia->slug,
                        'user'=>$barbeiro->id
                    ]) }}">

                        <h5>
                            Escolha o serviço
                        </h5>

                        <select
                            name="servico_id"
                            class="form-select"
                            required>

                            @foreach($servicos as $s)

                                <option value="{{ $s->id }}">
                                    {{ $s->nome }}
                                    ({{ $s->duracao }} min)
                                </option>

                            @endforeach

                        </select>

                        <button
                            class="btn btn-dark mt-3">

                            Ver agenda

                        </button>

                    </form>

                </div>

            </div>

        @endforeach

    @endif

</div>