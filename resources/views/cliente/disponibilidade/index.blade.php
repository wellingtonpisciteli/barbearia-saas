<h1>Selecione o Barbeiro</h1>

@foreach($barbeiros as $barbeiro)

    <div style="margin-bottom:15px;">

        <p>Nome: {{ $barbeiro->name }}</p>

        <form method="GET" action="{{ route('cliente.agenda', ['slug' => $barbearia->slug, 'user' => $barbeiro->id]) }}">

            <h3>Escolha o serviço</h3>

            <select name="servico_id" required>
                @foreach($servicos as $s)
                    <option value="{{ $s->id }}">
                        {{ $s->nome }} ({{ $s->duracao }} min)
                    </option>
                @endforeach
            </select>

            <br><br>

            <button type="submit">
                Ver agenda
            </button>

        </form>

    </div>

    <hr>

@endforeach