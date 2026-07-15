<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barbearia;
use Illuminate\Support\Facades\DB;

class FinanceiroController extends Controller
{
    public function index()
    {
        $barbearias = Barbearia::with([
            'admins',
            'assinatura'
        ])->get();

        return view('admin.financeiro.index', compact(
            'barbearias'
        ));
    }

    public function edit(int $id)
    {
        $barbearia = Barbearia::with([
            'admins',
            'assinatura'
        ])->findOrFail($id);

        return view(
            'admin.financeiro.editar',
            compact('barbearia')
        );
    }

    public function update(Request $request, int $id)
{
    $dados = $request->validate([

        'valor' => [
            'required',
            'numeric',
            'min:0',
        ],

        'status' => [
            'required',
            'in:ativa,pendente,inadimplente,cancelada',
        ],

        'proxima_cobranca' => [
            'required',
            'date',
        ],

    ]);


    DB::transaction(function () use ($dados, $request, $id) {

        $barbearia = Barbearia::with('assinatura')
            ->findOrFail($id);


        $barbearia->assinatura->update([

            'valor' => $dados['valor'],

            'status' => $dados['status'],

            'proxima_cobranca' => $dados['proxima_cobranca'],

        ]);


        $barbearia->update([

            'ativo' => $request->boolean('ativo'),

        ]);

    });


    return redirect()
        ->route('admin.financeiro')
        ->with('success', 'Assinatura atualizada com sucesso!');
}
}
