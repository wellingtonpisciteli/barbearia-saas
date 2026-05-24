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

class AgendaController extends Controller
{
    public function index(string $slug)
    {
        $barbearia = Barbearia::where('slug', $slug)->firstOrFail();

        $barbeiros = User::where('barbearia_id', $barbearia->id)->get();

        $servicos = Servico::where('barbearia_id', $barbearia->id)->where('ativo', true)->get();

        return view('cliente.disponibilidade.index',
            compact(
                'barbeiros',
                'barbearia',
                'servicos'
            )
        );
    }

    public function show(Request $request, string $slug, User $user, $date = null)
    {
        $servico_id = $request->servico_id;
        $servico = Servico::findOrFail($servico_id);
        $duracao = $servico->duracao;

        $token = request()->cookie('cliente_token');
        $cliente = Cliente::where('token', $token)->first();

        $data = $date ? Carbon::parse($date) : Carbon::today();

        $barbearia = Barbearia::where('slug', $slug)->firstOrFail();

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
            ->get()
            // Transforma string em objeto de data (Carbon)
            ->map(fn($a)=>[
                'inicio'=>Carbon::parse($a->inicio),
                'fim'=>Carbon::parse($a->fim)
            ]);

        $horarios = [];

        foreach($disponibilidades as $disp){
            $inicio = Carbon::parse($data->format('Y-m-d').' '.$disp->inicio);
            $fim = Carbon::parse($data->format('Y-m-d').' '.$disp->fim);

            while(true){
                $inicioSlot = $inicio->copy();
                $fimSlot = $inicio->copy()->addMinutes($duracao);

                if($fimSlot > $fim){
                    break;
                }

                // InícioA < fimB E fimA > inícioB | Retorno Sim e Sim para ocupado
                $ocupado = $agendados->contains(fn($a) => $inicioSlot < $a['fim'] && $fimSlot > $a['inicio']);

                $horarios[] = [
                    'inicio'=>$inicioSlot->format('H:i'),
                    'fim'=>$fimSlot->format('H:i'),
                    'ocupado'=>$ocupado
                ];

                $inicio->addMinutes($duracao);
            }
        }

        return view('cliente.disponibilidade.agenda',
            compact(
                'user', 'barbearia', 'horarios', 'data', 'cliente')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome_cliente' => 'required',
            'telefone_cliente' => 'required',
        ]);

        $barbearia = Barbearia::where('slug', $request->slug)->firstOrFail();

        $token = $request->cookie('cliente_token');
        $cliente = Cliente::where('token', $token)->first();

        if (!$cliente) {
            $cliente = Cliente::create([
                'barbearia_id' => $barbearia->id,
                'nome' => $request->nome_cliente,
                'telefone' => $request->telefone_cliente,
                'token' => Str::uuid(),
                ]);

            Cookie::queue('cliente_token', $cliente->token, 60 * 24 * 30);
        }

        $inicio = Carbon::parse($request->inicio);
        $fim = Carbon::parse($request->fimServico);

        // bloqueio seguro
        $existe = Agendamento::where('barbeiro_id', $request->barbeiro_id)
            ->where('inicio', '<', $fim)
            ->where('fim', '>', $inicio)
            ->exists();

        if ($existe) {
            return back()->with('error', 'Esse horário já foi reservado');
        }

        Agendamento::create([
            'barbearia_id' => $barbearia->id,
            'barbeiro_id' => $request->user_id,
            'inicio' => $inicio,
            'fim' => $fim,
            'cliente_id' => $cliente->id,
        ]);

        return back()->with('success', 'Agendamento realizado com sucesso!');
    }
}
