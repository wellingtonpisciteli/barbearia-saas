<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barbearia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\Agendamento;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Disponibilidade;
use App\Models\Servico;

class BarbeariaController extends Controller
{
    public function index()
    {
        $barbearias = Barbearia::with([
            'admins',
            'users'
        ])->get();
        

        return view('admin.barbearias.index', compact(
            'barbearias'
        ));
    }

    public function create()
    {
        return view('admin.barbearias.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([

            // Barbearia
            'nome' => [
                'required',
                'string',
                'max:255'
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
                'unique:barbearias,slug'
            ],

            'telefone' => [
                'nullable',
                'string'
            ],

            'endereco' => [
                'nullable',
                'string'
            ],

            'cidade' => [
                'nullable',
                'string'
            ],

            'instagram' => [
                'nullable',
                'string'
            ],

            'logo' => [
                'nullable',
                'image',
                'max:2048'
            ],


            // Admin da barbearia
            'admin_nome' => [
                'required',
                'string',
                'max:255'
            ],

            'admin_email' => [
                'required',
                'email',
                'unique:users,email'
            ],

            'admin_password' => [
                'required',
                'min:6'
            ],

        ]);


        DB::transaction(function () use ($dados, $request) {


            $logo = null;


            if ($request->hasFile('logo')) {

                $logo = $request->file('logo')
                    ->store('barbearias', 'public');

            }


            $barbearia = Barbearia::create([

                'nome' => $dados['nome'],

                'slug' => $dados['slug'],

                'telefone' => $dados['telefone'] ?? null,

                'endereco' => $dados['endereco'] ?? null,

                'cidade' => $dados['cidade'] ?? null,

                'instagram' => $dados['instagram'] ?? null,

                'logo' => $logo,

                'ativo' => true,

            ]);


            User::create([

                'name' => $dados['admin_nome'],

                'email' => $dados['admin_email'],

                'password' => Hash::make($dados['admin_password']),

                'role' => User::ROLE_ADMIN,

                'barbearia_id' => $barbearia->id,

            ]);


        });


        return redirect()
            ->route('admin.barbearias')
            ->with('success', 'Barbearia criada com sucesso!');
    }

    public function edit(int $id)
    {
        $barbearia = Barbearia::findOrFail($id);

        return view(
            'admin.barbearias.editar',
            compact('barbearia')
        );
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'nome' => [
                'required',
                'string',
                'max:255'
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('barbearias', 'slug')->ignore($id)
            ],

            'telefone' => [
                'nullable',
                'string',
                'max:20'
            ],

            'endereco' => [
                'nullable',
                'string',
                'max:255'
            ],

            'ativo' => [
                'nullable',
                'boolean'
            ],

            'admins' => [
                'required',
                'array'
            ],

            'admins.*.name' => [
                'required',
                'string',
                'max:255'
            ],

            'admins.*.email' => [
                'required',
                'email',
                'max:255'
            ],
        ]);

        $barbearia = Barbearia::findOrFail($id);

        $barbearia->update([
            'nome'      => $request->nome,
            'slug'      => Str::slug($request->slug),
            'telefone'  => $request->telefone,
            'endereco'  => $request->endereco,
            'ativo'     => $request->boolean('ativo'),
        ]);

        foreach ($request->admins as $adminId => $dados) {

            $admin = User::where('id', $adminId)
                ->where('barbearia_id', $barbearia->id)
                ->where('role', User::ROLE_ADMIN)
                ->first();

            if (!$admin) {
                continue;
            }

            $admin->update([
                'name'  => $dados['name'],
                'email' => $dados['email'],
            ]);
        }

        return redirect()
            ->route('admin.barbearias')
            ->with(
                'success',
                'Barbearia atualizada com sucesso.'
            );
    }

    public function destroyBarbearia(Barbearia $barbearia)
    {
        DB::transaction(function () use ($barbearia) {

            Agendamento::where('barbearia_id', $barbearia->id)->delete();

            Disponibilidade::where('barbearia_id', $barbearia->id)->delete();

            Cliente::where('barbearia_id', $barbearia->id)->delete();

            Servico::where('barbearia_id', $barbearia->id)->delete();

            User::where('barbearia_id', $barbearia->id)->delete();

            $barbearia->delete();
        });

        return back()->with(
            'success',
            'Barbearia excluída com sucesso.'
        );
    }
}
