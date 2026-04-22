<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use App\Models\Cargo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class FuncionarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Funcionario::with('cargo')
            ->when($request->busca, fn($q) =>
                $q->where('nome', 'like', "%{$request->busca}%")
                  ->orWhere('cpf', 'like', "%{$request->busca}%")
                  ->orWhere('email', 'like', "%{$request->busca}%")
            )
            ->when($request->status, fn($q) =>
                $q->where('status', $request->status)
            )
            ->when($request->cargo_id, fn($q) =>
                $q->where('cargo_id', $request->cargo_id)
            )
            ->orderBy('nome');

        $funcionarios = $query->paginate(10)->withQueryString();
        $cargos       = Cargo::orderBy('nome')->get();

        return view('funcionarios.index', compact('funcionarios', 'cargos'));
    }

    public function create()
    {
        $cargos = Cargo::orderBy('nome')->get();
        return view('funcionarios.create', compact('cargos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'            => ['required', 'string', 'max:150'],
            'cpf'             => ['required', 'string', 'size:14', 'unique:funcionarios,cpf'],
            'email'           => ['required', 'email', 'unique:funcionarios,email'],
            'telefone'        => ['nullable', 'string', 'max:20'],
            'data_nascimento' => ['nullable', 'date', 'before:today'],
            'data_admissao'   => ['required', 'date', 'before_or_equal:today'],
            'salario'         => ['required', 'numeric', 'min:0'],
            'cargo_id'        => ['required', 'exists:cargos,id'],
            'status'          => ['required', 'in:ativo,inativo,afastado'],
            // Campos opcionais para criar login junto
            'criar_login'     => ['nullable', 'boolean'],
            'login_email'     => ['nullable', 'required_if:criar_login,1', 'email', 'unique:users,email'],
            'login_senha'     => ['nullable', 'required_if:criar_login,1', 'min:6'],
        ]);

        // Cria usuário de acesso se solicitado
        $userId = null;
        if ($request->boolean('criar_login')) {
            $user   = User::create([
                'name'     => $request->nome,
                'email'    => $request->login_email,
                'password' => Hash::make($request->login_senha),
                'role'     => 'funcionario',
            ]);
            $userId = $user->id;
        }

        Funcionario::create([
            'nome'            => $request->nome,
            'cpf'             => $request->cpf,
            'email'           => $request->email,
            'telefone'        => $request->telefone,
            'data_nascimento' => $request->data_nascimento,
            'data_admissao'   => $request->data_admissao,
            'salario'         => $request->salario,
            'cargo_id'        => $request->cargo_id,
            'status'          => $request->status,
            'user_id'         => $userId,
        ]);

        return redirect()
            ->route($this->prefix() . 'funcionarios.index')
            ->with('success', 'Funcionário cadastrado com sucesso!');
    }

    public function show(Funcionario $funcionario)
    {
        $funcionario->load('cargo', 'ferias', 'folhas', 'entregas.equipamento');
        return view('funcionarios.show', compact('funcionario'));
    }

    public function edit(Funcionario $funcionario)
    {
        $cargos = Cargo::orderBy('nome')->get();
        return view('funcionarios.edit', compact('funcionario', 'cargos'));
    }

    public function update(Request $request, Funcionario $funcionario)
    {
        $request->validate([
            'nome'            => ['required', 'string', 'max:150'],
            'cpf'             => ['required', 'string', 'size:14', Rule::unique('funcionarios','cpf')->ignore($funcionario->id)],
            'email'           => ['required', 'email', Rule::unique('funcionarios','email')->ignore($funcionario->id)],
            'telefone'        => ['nullable', 'string', 'max:20'],
            'data_nascimento' => ['nullable', 'date', 'before:today'],
            'data_admissao'   => ['required', 'date', 'before_or_equal:today'],
            'salario'         => ['required', 'numeric', 'min:0'],
            'cargo_id'        => ['required', 'exists:cargos,id'],
            'status'          => ['required', 'in:ativo,inativo,afastado'],
        ]);

        $funcionario->update($request->only(
            'nome','cpf','email','telefone','data_nascimento',
            'data_admissao','salario','cargo_id','status'
        ));

        return redirect()
            ->route($this->prefix() . 'funcionarios.index')
            ->with('success', 'Funcionário atualizado com sucesso!');
    }

    public function destroy(Funcionario $funcionario)
    {
        $funcionario->update(['status' => 'inativo']);

        return redirect()
            ->route($this->prefix() . 'funcionarios.index')
            ->with('success', 'Funcionário inativado com sucesso.');
    }

    // Visão do próprio funcionário logado
    public function perfil()
    {
        $funcionario = Funcionario::with('cargo')
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('funcionarios.perfil', compact('funcionario'));
    }

    private function prefix(): string
    {
        return str_starts_with(request()->route()->getName(), 'admin.') ? 'admin.' : 'rh.';
    }
}
