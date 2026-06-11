<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Disponibilidade;
use App\Models\User;
use App\Models\Barbearia;
use Illuminate\Support\Carbon;
use App\Models\Agendamento;
use App\Models\Servico;
use App\Models\Cliente;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    public function index(string $slug)
    {
        $barbearia = Barbearia::where('slug', $slug)->firstOrFail();

        $barbeiros = User::where(
            'barbearia_id',
            $barbearia->id
        )->get();

        $servicos = Servico::where('barbearia_id', $barbearia->id)
            ->where('ativo', true)
            ->get();

        $cookieName = 'cliente_token_' . $barbearia->slug;

        $token = request()->cookie($cookieName);

        $cliente = Cliente::where('token', $token)
            ->where('barbearia_id', $barbearia->id)
            ->first();

        if($cliente){

            $agendamentos = Agendamento::where('cliente_id', $cliente->id)
                ->where('barbearia_id', $barbearia->id)
                ->where('status', 'confirmado')
                ->get();

            foreach($agendamentos as $agendamento){
                $this->verificarAgendamento($agendamento);
            }
        }

        $agendamentoCliente = null;

        if($cliente){

            $agendamentoCliente = Agendamento::where('cliente_id', $cliente->id)
                ->where('barbearia_id', $barbearia->id)
                ->where('status', 'confirmado')
                ->first();
        }

        return view(
            'cliente.disponibilidade.index',
            compact(
                'barbeiros',
                'barbearia',
                'servicos',
                'agendamentoCliente',
                'cliente'
            )
        );
    }

    public function show(Request $request, string $slug, User $user, $date = null)
    {
        $servico_id = $request->servico_id;
        $servico = Servico::findOrFail($servico_id);

        $duracao = $servico->duracao;

        $data = $date ? Carbon::parse($date) : Carbon::today();

        $barbearia = Barbearia::where('slug', $slug)->firstOrFail();

        $cookieName = 'cliente_token_' . $barbearia->slug;

        $token = $request->cookie($cookieName);

        $cliente = Cliente::where('token', $token)
            ->where('barbearia_id', $barbearia->id)
            ->first();

        $agendamentoConfirmado = null;
        $status = '';

        if ($cliente) {

            $agendamentoConfirmado = Agendamento::query()
                ->where('cliente_id', $cliente->id)
                ->where('barbearia_id', $barbearia->id)
                ->where('status', 'confirmado')
                ->latest()
                ->first();

            $status = $agendamentoConfirmado?->status ?? '';
        }

        // Bloqueia barbeiro caso ele não pertença à barbearia da URL
        abort_if($user->barbearia_id !== $barbearia->id, 404);

        // Busca os horários de trabalho ativos do barbeiro para a data selecionada
        $disponibilidades = Disponibilidade::where('barbeiro_id', $user->id)
            ->where('barbearia_id', $barbearia->id)
            ->where('dia_semana', $data->dayOfWeekIso)
            ->where('ativo', true)
            ->get();

        // Busca os horários ocupados do barbeiro
        $agendados = Agendamento::where('barbeiro_id', $user->id)
            ->whereDate('inicio', $data)
            ->where('status', 'confirmado')
            ->get()
            ->map(fn($a) => [
                'inicio' => Carbon::parse($a->inicio),
                'fim' => Carbon::parse($a->fim)
            ]);

        $horarios = [];

        foreach ($disponibilidades as $disp) {

            $inicio = Carbon::parse(
                $data->format('Y-m-d') . ' ' . $disp->inicio
            );

            $fim = Carbon::parse(
                $data->format('Y-m-d') . ' ' . $disp->fim
            );

            while (true) {

                $inicioSlot = $inicio->copy();
                $fimSlot = $inicio->copy()->addMinutes($duracao);

                if ($fimSlot > $fim) {
                    break;
                }

                $ocupado = $agendados->contains(
                    fn($a) =>
                        $inicioSlot < $a['fim']
                        && $fimSlot > $a['inicio']
                );

                // Bloqueia horários passados
                if ($inicioSlot->lte(now())) {
                    $ocupado = true;
                }

                $horarios[] = [
                    'inicio' => $inicioSlot->format('H:i'),
                    'fim' => $fimSlot->format('H:i'),
                    'ocupado' => $ocupado
                ];

                $inicio->addMinutes($duracao);
            }
        }

        if ($agendamentoConfirmado) {
            return redirect()->route(
                'cliente.disponibilidade',
                $slug
            );
        }

        return view(
            'cliente.disponibilidade.agenda',
            compact(
                'user',
                'barbearia',
                'horarios',
                'data',
                'cliente',
                'status',
                'servico'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'nome_cliente' => [
                    'required',             
                    'min:4',
                    'max:30',
                    'regex:/^[A-Za-zÀ-ÿ\s]+$/' 
                ],
                'telefone_cliente' => [
                    'required',             
                    'min:9',
                    'max:13',
                    'regex:/^[0-9]+$/'     
                ],
                'servico_id' => 'required|exists:servicos,id',
            ],
            [
                'nome_cliente.required' => 'Digite um nome válido.',
                'nome_cliente.min' => 'O nome deve ter no mínimo 4 caracteres.',
                'nome_cliente.max' => 'O nome deve ter no máximo 30 caracteres.',
                'nome_cliente.regex' => 'O nome não pode conter números ou caracteres especiais.',

                'telefone_cliente.required' => 'Digite um telefone válido.',
                'telefone_cliente.min' => 'O telefone deve ter no mínimo 9 caracteres.',
                'telefone_cliente.max' => 'O telefone deve ter no máximo 13 caracteres.',
                'telefone_cliente.regex' => 'O telefone deve conter apenas números.',

                'servico_id.required' => 'Selecione um serviço.',
                'servico_id.exists' => 'Serviço inválido.',
            ]
        );

        $barbearia = Barbearia::where('slug', $request->slug)->firstOrFail();

        $cookieName = 'cliente_token_' . $barbearia->slug;

        $token = $request->cookie($cookieName);

        $cliente = Cliente::where('token', $token)
            ->where('barbearia_id', $barbearia->id)
            ->first();

        if (!$cliente) {

            $cliente = Cliente::create([
                'barbearia_id' => $barbearia->id,
                'nome' => $request->nome_cliente,
                'telefone' => $request->telefone_cliente,
                'token' => (string) Str::uuid(),
            ]);

            Cookie::queue(
                $cookieName,
                $cliente->token,
                60 * 24 * 30
            );
        }

        $inicio = Carbon::parse($request->data . ' ' . $request->inicio);
        $fim = Carbon::parse($request->data . ' ' . $request->fimServico);

        if($inicio->lte(now())){
            return back()->with(
                'error',
                'Este horário não está mais disponível.'
            );
        }

        // bloqueio seguro
        $existe = Agendamento::where('barbeiro_id', $request->user_id)
            ->where('inicio', '<', $fim)
            ->where('fim', '>', $inicio)
            ->where('status', 'confirmado')
            ->exists();

        if($existe){
            return back()->with('error', 'Esse horário já foi reservado');
        }

        Agendamento::create([
            'barbearia_id' => $barbearia->id,
            'barbeiro_id' => $request->user_id,
            'servico_id' => $request->servico_id,
            'inicio' => $inicio,
            'fim' => $fim,
            'cliente_id' => $cliente->id,
        ]);

        return redirect()->route('cliente.disponibilidade', $request->slug);
    }

    public function cancelar(Request $request, int $id)
    {
        $agendamento = Agendamento::with('barbearia')
            ->findOrFail($id);

        $cookieName = 'cliente_token_' .
            $agendamento->barbearia->slug;

        $token = $request->cookie($cookieName);

        $cliente = Cliente::where('token', $token)
            ->where('barbearia_id', $agendamento->barbearia_id)
            ->first();

        if (!$cliente || $agendamento->cliente_id != $cliente->id) {
            abort(403);
        }

        $agendamento->update([
            'status' => 'cancelado'
        ]);

        return back()->with(
            'success',
            'Agendamento cancelado'
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
