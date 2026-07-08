<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use App\Models\Barbearia;
use App\Models\Cliente;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Disponibilidade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Servico;

class BarbeiroController extends Controller
{
    private function verificarBarbeariaAtiva()
    {
        $user = Auth::user();

        $barbearia = Barbearia::findOrFail($user->barbearia_id);

        if (!$barbearia->ativo) {
            return view(
                'barbeiro.barbearia-desativada',
                compact('barbearia')
            );
        }

        return null;
    }

    public function agendamentos()
    {
        if ($bloqueio = $this->verificarBarbeariaAtiva()) {
            return $bloqueio;
        }

        $agendamentos = Agendamento::with([
                'cliente',
                'servico'
            ])
            ->where('barbeiro_id', Auth::id())
            ->where('status', 'confirmado')
            ->whereDate('inicio', Carbon::today())
            ->orderBy('inicio')
            ->get();

        foreach ($agendamentos as $agendamento) {
            $this->verificarAgendamento($agendamento);
        }

        // recarrega dados atualizados
        $agendamentos = Agendamento::with([
                'cliente',
                'servico'
            ])
            ->where('barbeiro_id', Auth::id())
            ->where('status', 'confirmado')
            ->whereDate('inicio', Carbon::today())
            ->orderBy('inicio')
            ->get();

        return view(
            'barbeiro.agendamentos',
            compact('agendamentos')
        );
    }

    public function clientes()
    {
        if ($bloqueio = $this->verificarBarbeariaAtiva()) {
            return $bloqueio;
        }

        $clientes = Cliente::where(
            'barbearia_id',
            Auth::user()->barbearia_id
        )
        ->orderBy('nome')
        ->get();

        return view(
            'barbeiro.clientes',
            compact('clientes')
        );
    }

    public function barbeiros()
    {
        if ($bloqueio = $this->verificarBarbeariaAtiva()) {
            return $bloqueio;
        }

        $barbeiros = User::where(
            'barbearia_id',
            Auth::user()->barbearia_id
        )
        ->orderBy('name')
        ->get();

        return view(
            'barbeiro.barbeiros',
            compact('barbeiros')
        );
    }

    public function createBarbeiro()
    {
        if ($bloqueio = $this->verificarBarbeariaAtiva()) {
            return $bloqueio;
        }

        return view(
            'barbeiro.criarBarbeiro'
        );
    }

    public function storeBarbeiro(Request $request)
    {
        $barbearia_id = Auth::user()->barbearia_id;

        $request->validate(
            [
                'name' => [
                    'required',
                    'min:4',
                    'max:30',
                    'regex:/^[A-Za-zÀ-ÿ\s]+$/'
                ],

                'telefone' => [
                    'required',
                    'min:9',
                    'max:13',
                    'regex:/^[0-9]+$/',
                    Rule::unique('users', 'telefone')
                        ->where(fn ($query) =>
                            $query->where('barbearia_id', $barbearia_id)
                        ),
                ],

                'email' => [
                    'required',
                    'email',
                    'unique:users,email',
                ],

                'role' => [
                    'required',
                    'in:admin,colaborador',
                ],

                'inicio' => [
                    'required',
                ],

                'fim' => [
                    'required',
                ],

                'dias_semana' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'dias_semana.*' => [
                    'integer',
                    'between:1,7',
                ],

                'intervalo_inicio' => [
                    'nullable',
                    'required',
                    'required_with:intervalo_fim',
                    'date_format:H:i',
                ],

                'intervalo_fim' => [
                    'nullable',
                    'required',
                    'required_with:intervalo_inicio',
                    'date_format:H:i',
                ],

                'password' => [
                    'required',
                    'min:6',
                    'confirmed',
                ],
            ],
            [
                'name.required' => 'Digite um nome válido.',
                'name.min' => 'O nome deve ter no mínimo 4 caracteres.',
                'name.max' => 'O nome deve ter no máximo 30 caracteres.',
                'name.regex' => 'O nome não pode conter números ou caracteres especiais.',

                'telefone.required' => 'Digite um telefone válido.',
                'telefone.min' => 'O telefone deve ter no mínimo 9 caracteres.',
                'telefone.max' => 'O telefone deve ter no máximo 13 caracteres.',
                'telefone.regex' => 'O telefone deve conter apenas números.',
                'telefone.unique' => 'Este telefone já está em uso.',

                'email.required' => 'Digite um e-mail.',
                'email.email' => 'Digite um e-mail válido.',
                'email.unique' => 'Este e-mail já está em uso.',

                'role.required' => 'Selecione o tipo de usuário.',
                'role.in' => 'Tipo de usuário inválido.',

                'inicio.required' => 'Informe o início do expediente.',
                'fim.required' => 'Informe o fim do expediente.',
                'dias_semana.required' => 'Selecione pelo menos um dia de trabalho.',
                'dias_semana.min' => 'Selecione pelo menos um dia de trabalho.',
                'intervalo_inicio.required_with' => 'Informe o início do intervalo.',
                'intervalo_inicio.required' => 'Informe o início do intervalo.',
                'intervalo_fim.required_with' => 'Informe o fim do intervalo.',
                'intervalo_fim.required' => 'Informe o fim do intervalo.',

                'password.required' => 'Digite uma senha.',
                'password.min' => 'A senha deve ter no mínimo 6 caracteres.',
                'password.confirmed' => 'As senhas não coincidem.',
            ]
        );

        if (
            $request->filled('intervalo_inicio') &&
            $request->filled('intervalo_fim')
        ) {
            if (
                $request->intervalo_inicio >= $request->intervalo_fim ||
                $request->intervalo_inicio <= $request->inicio ||
                $request->intervalo_fim >= $request->fim
            ) {
                return back()
                    ->withErrors([
                        'intervalo_inicio' => 'O intervalo deve estar dentro do expediente.',
                    ])
                    ->withInput();
            }
        }

        if (
            Auth::user()->role !== User::ROLE_ADMIN &&
            $request->role === User::ROLE_ADMIN
        ) {
            abort(403);
        }

        $role = $request->role;


        $barbeiro = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'password' => bcrypt($request->password),
            'barbearia_id' => $barbearia_id,
            'role' => $role,
        ]);

        foreach ($request->dias_semana as $dia) {
            Disponibilidade::create([
                'barbeiro_id' => $barbeiro->id,
                'dia_semana' => $dia,
                'inicio' => $request->inicio,
                'intervalo_inicio' => $request->intervalo_inicio,
                'intervalo_fim' => $request->intervalo_fim,
                'fim' => $request->fim,
                'ativo' => true,
                'barbearia_id' => $barbearia_id,
            ]);
        }

        return redirect()->route('barbeiro.barbeiros');
    }

    public function editarBarbeiro(int $id)
    {
        if ($bloqueio = $this->verificarBarbeariaAtiva()) {
            return $bloqueio;
        }

        $barbeiro = User::findOrFail($id);

        $disponibilidade = Disponibilidade::where('barbeiro_id', $id)->first();

        $diasSelecionados = Disponibilidade::where(
            'barbeiro_id',
            $id
        )->pluck('dia_semana')
        ->toArray();

        return view(
            'barbeiro.editarBarbeiro',
            compact(
                'barbeiro',
                'disponibilidade',
                'diasSelecionados'
            )
        );
    }


    public function updateBarbeiro(Request $request, int $id)
    {
        $barbeiro = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'role' => 'nullable|in:colaborador,admin',

            'inicio' => 'nullable',
            'intervalo_inicio' => 'nullable',
            'intervalo_fim' => 'nullable',
            'fim' => 'nullable',

            'password' => 'nullable|string|min:6|confirmed',

            'dias_semana' => 'required|array|min:1',
            'dias_semana.*' => 'integer|between:1,7',
        ]);

        $inicio = $request->inicio ? strtotime($request->inicio) : null;
        $fim = $request->fim ? strtotime($request->fim) : null;
        $intInicio = $request->intervalo_inicio ? strtotime($request->intervalo_inicio) : null;
        $intFim = $request->intervalo_fim ? strtotime($request->intervalo_fim) : null;

        if ($inicio && $fim && $intInicio && $intFim) {

            if (
                $intInicio >= $intFim ||
                $intInicio <= $inicio ||
                $intFim >= $fim
            ) {
                return back()
                    ->withErrors([
                        'intervalo_inicio' => 'O intervalo deve estar dentro do expediente.',
                    ])
                    ->withInput();
            }
        }

        /*
        |-----------------------------------------
        | NORMALIZA HORÁRIOS
        |-----------------------------------------
        */
        foreach (['inicio', 'intervalo_inicio', 'intervalo_fim', 'fim'] as $campo) {
            $data[$campo] = !empty($data[$campo])
                ? date('H:i', strtotime($data[$campo]))
                : null;
        }

        /*
        |-----------------------------------------
        | USER DATA
        |-----------------------------------------
        */
        $userData = collect($data)->only([
            'name',
            'email',
            'telefone',
            'role',
        ])->toArray();

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        // atualiza usuário
        $barbeiro->update($userData);

        $novos = (array) $data['dias_semana'];

        $barbeiro->disponibilidades()
            ->whereNotIn('dia_semana', $novos)
            ->delete();


        foreach ($novos as $dia) {
            $barbeiro->disponibilidades()->updateOrCreate(
                [
                    'barbeiro_id' => $barbeiro->id,
                    'barbearia_id' => $barbeiro->barbearia_id,
                    'dia_semana' => (int) $dia,
                ],
                [
                    'inicio' => $data['inicio'],
                    'intervalo_inicio' => $data['intervalo_inicio'],
                    'intervalo_fim' => $data['intervalo_fim'],
                    'fim' => $data['fim'],
                ]
            );
        }

        return redirect()->route('barbeiro.barbeiros');
    }

    public function barbearia()
    {
        if ($bloqueio = $this->verificarBarbeariaAtiva()) {
            return $bloqueio;
        }

        $barbearia = Auth::user()->barbearia;

        return view(
            'barbeiro.configuracoes',
            compact('barbearia')
        );
    }

    public function barbeariaUpdate(Request $request)
    {
        $request->validate([
            'nome'       => 'required|string|max:255',
            'telefone'   => 'required|string|max:20',
            'instagram'  => 'nullable|string|max:255',
            'endereco'   => 'nullable|string|max:255',
            'cidade'   => 'nullable|string|max:50',
            'logo'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $barbearia = Auth::user()->barbearia;

        $barbearia->nome = $request->nome;
        $barbearia->slug = Str::slug($request->nome);
        $barbearia->telefone = preg_replace('/\D/', '', $request->telefone);
        $barbearia->instagram = $request->instagram;
        $barbearia->cidade = $request->cidade;
        $barbearia->endereco = $request->endereco;

        // LOGO
        if ($request->hasFile('logo')) {

            // remove antiga se existir
            if ($barbearia->logo) {
                Storage::disk('public')->delete($barbearia->logo);
            }

            $path = $request->file('logo')
                ->store('barbearias/logos', 'public');

            $barbearia->logo = $path;
        }

        $barbearia->save();

        return redirect()
            ->route('barbeiro.configuracoes')
            ->with('success', 'Configurações atualizadas com sucesso!');
    }

    public function servicos()
    {
        if ($bloqueio = $this->verificarBarbeariaAtiva()) {
            return $bloqueio;
        }

        $servicos = Servico::where('barbearia_id', Auth::user()->barbearia_id)
        ->orderBy('nome')
        ->get();

        return view('barbeiro.servicos', compact('servicos'));
    }

    public function servicosCreate()
    {
        if ($bloqueio = $this->verificarBarbeariaAtiva()) {
            return $bloqueio;
        }

        return view('barbeiro.criarServicos');
    }

    public function servicosStore(Request $request)
    {
        $request->validate([
            'nome' => [
                'required',
                'string',
                'max:255',
                'regex:/[[:alpha:]]/u',
            ],            
            'valor' => ['required', 'regex:/^\d{1,3}(\.\d{3})*(,\d{2})?$|^\d+(,\d{2})?$/'],            
            'duracao'   => 'required|integer|min:5|max:120',
        ]);

        $preco = str_replace(',', '.', $request->valor);

        Servico::create([
            'barbearia_id' => Auth::user()->barbearia_id,
            'nome'         => $request->nome,
            'preco'        => $preco,
            'duracao'      => $request->duracao,
        ]);

        return redirect()
            ->route('barbeiro.servicos')
            ->with('success', 'Serviço cadastrado com sucesso!');
    }

    public function servicosEditar(int $id)
    {
        if ($bloqueio = $this->verificarBarbeariaAtiva()) {
            return $bloqueio;
        }

        $servico = Servico::where('barbearia_id', Auth::user()->barbearia_id)
            ->findOrFail($id);

        return view('barbeiro.editarServicos', compact('servico'));
    }

    public function servicosUpdate(Request $request, int $id)
    {
        $request->validate([
            'nome' => [
                'required',
                'string',
                'max:255',
                'regex:/[[:alpha:]]/u',
            ],
            'valor'   => 'required',
            'duracao' => 'required|integer|min:5|max:120',
        ]);

        $servico = Servico::where('barbearia_id', Auth::user()->barbearia_id)
            ->findOrFail($id);

        $servico->update([
            'nome'      => $request->nome,
            'preco'     => str_replace(',', '.', $request->valor),
            'duracao'   => $request->duracao,
        ]);

        return redirect()
            ->route('barbeiro.servicos')
            ->with('success', 'Serviço atualizado com sucesso!');
    }

    public function cancelar(int $id)
    {
        $agendamento = Agendamento::where(
                'barbeiro_id',
                Auth::id()
            )
            ->findOrFail($id);

        $agendamento->update([
            'status' => 'finalizado',
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Agendamento finalizado.'
            );
    }

    public function destroyCliente(Cliente $cliente)
    {
        $cliente->delete();

        return back()->with(
            'success',
            'Cliente excluído com sucesso.'
        );
    }

    public function destroyBarbeiro(User $barbeiro)
    {
        $barbeiro->delete();

        return back()->with(
            'success',
            'Barbeiro excluído com sucesso.'
        );
    }

    public function destroyServico(Servico $servico)
    {
        $servico->delete();

        return back()->with(
            'success',
            'Cliente excluído com sucesso.'
        );
    }

    public function verificarAgendamento(Agendamento $agendamento)
    {
        $agendamentoFim = \Carbon\Carbon::parse(
            $agendamento->data . ' ' . $agendamento->fim
        );

        if(now()->greaterThanOrEqualTo($agendamentoFim)){

            $agendamento->update(['status' => 'finalizado']);

            return true;
        }

        return false;
    }
}