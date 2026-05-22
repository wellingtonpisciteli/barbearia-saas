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
        $barbeariaId =
            Auth::user()->barbearia_id;

        $agendamentos =
            Agendamento::with('barbeiro')
            ->where(
                'barbearia_id',
                $barbeariaId
            )
            ->whereDate(
                'inicio',
                Carbon::today()
            )
            ->get();

        return view(
            'adm.agendamento.index',
            compact('agendamentos')
        );
    }

    public function admCancelar(int $id)
    {
        $agendamento =
            Agendamento::where(
                'barbearia_id',
                Auth::user()->barbearia_id
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