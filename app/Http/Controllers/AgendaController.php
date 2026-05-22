<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Disponibilidade;
use App\Models\User;
use App\Models\Barbearia;
use Illuminate\Support\Carbon;
use App\Models\Agendamento;

class AgendaController extends Controller
{
    public function index(string $slug)
    {
        $barbearia =
            Barbearia::where(
                'slug',
                $slug
            )->firstOrFail();

        $barbeiros =
            User::where(
                'barbearia_id',
                $barbearia->id
            )->get();

        return view(
            'cliente.disponibilidade.index',
            compact(
                'barbeiros',
                'barbearia'
            )
        );
    }

    public function show(string $slug, User $user, $date = null)
    {
        $data = $date
            ? Carbon::parse($date)
            : Carbon::today();

        $barbearia =
            Barbearia::where(
                'slug',
                $slug
            )->firstOrFail();

        abort_if(
            $user->barbearia_id !== $barbearia->id,
            404
        );

        $disponibilidades =
            Disponibilidade::where(
                'barbeiro_id',
                $user->id
            )
            ->where(
                'barbearia_id',
                $barbearia->id
            )
            ->where('dia_semana', $data->dayOfWeekIso)
            ->where(
                'ativo',
                true
            )
            ->get();

            $horarios = [];

        foreach ($disponibilidades as $disp)
        {
            $inicio = Carbon::parse($data->format('Y-m-d') . ' ' . $disp->inicio);
            $fim = Carbon::parse($data->format('Y-m-d') . ' ' . $disp->fim);

            while ($inicio < $fim)
            {
                $horarios[] =
                    $inicio->format('H:i');

                $inicio->addMinutes(
                    $disp->intervalo
                );
            }
        }

        $agendados =
            Agendamento::where(
                'barbeiro_id',
                $user->id
            )
            ->whereDate('inicio', $data->format('Y-m-d'))
            ->pluck('inicio')
            ->map(function ($item) {
                return Carbon::parse($item)->format('H:i');
            })
            ->toArray();

        return view(
            'cliente.disponibilidade.agenda',
            compact('disponibilidades', 'horarios', 'user', 'barbearia', 'agendados')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome_cliente' => 'required',
            'telefone_cliente' => 'required',
        ]);
        
        $barbearia =
            Barbearia::where('slug', $request->slug)
            ->firstOrFail();

        $inicio = Carbon::parse($request->horario);
        $fim = (clone $inicio)->addMinutes(30);

        // 🔒 bloqueio seguro
        $existe =
            Agendamento::where('barbeiro_id', $request->user_id)
            ->whereBetween('inicio', [
                $inicio->copy()->startOfMinute(),
                $inicio->copy()->endOfMinute(),
            ])
            ->exists();

        if ($existe) {
            return back()->with('error', 'Esse horário já foi reservado');
        }

        Agendamento::create([
            'barbearia_id' => $barbearia->id,
            'barbeiro_id' => $request->user_id,
            'nome_cliente' => $request->nome_cliente,
            'telefone_cliente' => $request->telefone_cliente,
            'inicio' => $inicio,
            'fim' => $fim,
        ]);

        return back()->with('success', 'Agendamento realizado com sucesso!');
    }
}
