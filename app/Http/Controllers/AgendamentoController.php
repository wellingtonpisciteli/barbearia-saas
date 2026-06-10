<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
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
            ->where(
                'barbeiro_id',
                Auth::id()
            )
            ->where(
                'status',
                'confirmado'
            )
            ->whereDate(
                'inicio',
                Carbon::today()
            )
            ->orderBy('inicio')
            ->get();

        return view(
            'barbeiro.agendamento.index',
            compact('agendamentos')
        );
    }

    public function cancelar(int $id)
    {
        $agendamento = Agendamento::where(
                'barbeiro_id',
                Auth::id()
            )
            ->findOrFail($id);

        $agendamento->delete();

        return redirect()
            ->back()
            ->with(
                'success',
                'Agendamento cancelado'
            );
    }
}