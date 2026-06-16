<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use App\Models\Cliente;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AgendamentoController extends Controller
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

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

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