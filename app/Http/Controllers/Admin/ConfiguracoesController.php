<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ConfiguracoesController extends Controller
{
    public function index()
    {
        $admins = Admin::orderBy('name')->get();

        return view('admin.configuracoes.index', compact('admins'));
    }

    public function edit(int $id)
    {
        $admin = Admin::findOrFail($id);

        return view('admin.configuracoes.editar', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        $dados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $admin->id,
            'password' => 'nullable|min:6',
            'role' => 'required|in:admin,superAdmin',
        ]);

        if (
            $admin->role === 'superAdmin' &&
            $dados['role'] !== 'superAdmin' &&
            Admin::where('role', 'superAdmin')->count() === 1
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'role' => 'É necessário manter pelo menos um Super Admin no sistema.'
                ]);
        }

        if (!empty($dados['password'])) {
            $dados['password'] = Hash::make($dados['password']);
        } else {
            unset($dados['password']);
        }

        $admin->update($dados);

        return redirect()
            ->route('admin.configuracoes')
            ->with('success', 'Usuário atualizado com sucesso.');
    }

    public function create()
    {
        return view('admin.configuracoes.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email',
            'password' => 'required|confirmed|min:6',
            'role' => 'required|in:admin,superAdmin',
        ]);

        $dados['password'] = Hash::make($dados['password']);

        Admin::create($dados);

        return redirect()
            ->route('admin.configuracoes')
            ->with('success', 'Usuário cadastrado com sucesso.');
    }
}
