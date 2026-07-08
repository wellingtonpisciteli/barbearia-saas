<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barbearia;
use App\Models\User;
use App\Models\Agendamento;

class DashboardController extends Controller
{
    public function index()
    {
        $barbearias = Barbearia::count();

        $usuarios = User::count();

        $agendamentosHoje = Agendamento::whereDate('inicio', today())
            ->where('status', 'confirmado')
            ->count();
        
        $agendamentosMes = Agendamento::whereMonth('inicio', now()->month)
             ->whereIn('status', [
                'confirmado',
                'finalizado',
            ])
            ->whereYear('inicio', now()->year)
            ->count();

        return view('admin.dashboard', compact(
            'barbearias',
            'usuarios',
            'agendamentosHoje',
            'agendamentosMes'
        ));
    }
}
