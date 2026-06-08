<?php
// ============================================================
// ARQUIVO 3: app/Http/Controllers/Api/FuncionarioApiController.php
// ============================================================
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Funcionario;
use Illuminate\Http\Request;

class FuncionarioApiController extends Controller
{
    public function index(Request $request)
    {
        $funcionarios = Funcionario::with('cargo')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->busca,  fn($q) =>
                $q->where('nome',  'like', "%{$request->busca}%")
                  ->orWhere('cpf', 'like', "%{$request->busca}%")
            )
            ->orderBy('nome')
            ->paginate($request->per_page ?? 15);

        return response()->json($funcionarios);
    }

    public function show(Funcionario $funcionario)
    {
        return response()->json(
            $funcionario->load('cargo','ferias','folhas','entregas.equipamento')
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'           => ['required', 'string', 'max:150'],
            'cpf'            => ['required', 'string', 'size:14', 'unique:funcionarios,cpf'],
            'email'          => ['required', 'email', 'unique:funcionarios,email'],
            'data_admissao'  => ['required', 'date'],
            'salario'        => ['required', 'numeric', 'min:0'],
            'cargo_id'       => ['required', 'exists:cargos,id'],
            'status'         => ['in:ativo,inativo,afastado'],
        ]);

        $funcionario = Funcionario::create($data);

        return response()->json($funcionario->load('cargo'), 201);
    }

    public function update(Request $request, Funcionario $funcionario)
    {
        $data = $request->validate([
            'nome'          => ['sometimes', 'string', 'max:150'],
            'salario'       => ['sometimes', 'numeric', 'min:0'],
            'cargo_id'      => ['sometimes', 'exists:cargos,id'],
            'status'        => ['sometimes', 'in:ativo,inativo,afastado'],
        ]);

        $funcionario->update($data);

        return response()->json($funcionario->load('cargo'));
    }

    public function destroy(Funcionario $funcionario)
    {
        $funcionario->update(['status' => 'inativo']);
        return response()->json(['message' => 'Funcionário inativado com sucesso.']);
    }
}

