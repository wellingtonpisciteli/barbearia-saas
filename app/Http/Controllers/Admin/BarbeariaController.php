<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barbearia;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
}
