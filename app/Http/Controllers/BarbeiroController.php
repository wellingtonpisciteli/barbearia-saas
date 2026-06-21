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

class BarbeiroController extends Controller
{
    public function index()
    {
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
            'barbeiro.agendamento.index',
            compact('agendamentos')
        );
    }

    public function clientes()
    {
        $clientes = Cliente::where(
            'barbearia_id',
            Auth::user()->barbearia_id
        )
        ->orderBy('nome')
        ->get();

        return view(
            'barbeiro.agendamento.clientes',
            compact('clientes')
        );
    }

    public function barbeiros()
    {
        $barbeiros = User::where(
            'barbearia_id',
            Auth::user()->barbearia_id
        )
        ->orderBy('name')
        ->get();

        return view(
            'barbeiro.agendamento.barbeiros',
            compact('barbeiros')
        );
    }

    public function create()
    {
        return view(
            'barbeiro.agendamento.criarBarbeiro',
        );
    }

    public function store(Request $request)
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