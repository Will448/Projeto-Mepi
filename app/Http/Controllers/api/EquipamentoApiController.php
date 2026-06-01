<?php
// ============================================================
// ARQUIVO 5: app/Http/Controllers/Api/EquipamentoApiController.php
// ============================================================
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Equipamento;
use Illuminate\Http\Request;

class EquipamentoApiController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            Equipamento::when($request->status, fn($q) => $q->where('status', $request->status))
                ->orderBy('nome')
                ->paginate(15)
        );
    }

    public function show(Equipamento $equipamento)
    {
        return response()->json($equipamento->load('entregas.funcionario'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'         => ['required', 'string'],
            'numero_serie' => ['required', 'string', 'unique:equipamentos,numero_serie'],
            'tipo'         => ['required', 'string'],
            'validade'     => ['nullable', 'date'],
            'status'       => ['in:disponivel,entregue,manutencao'],
        ]);

        return response()->json(Equipamento::create($data), 201);
    }

    public function update(Request $request, Equipamento $equipamento)
    {
        $data = $request->validate([
            'nome'    => ['sometimes', 'string'],
            'tipo'    => ['sometimes', 'string'],
            'status'  => ['sometimes', 'in:disponivel,entregue,manutencao'],
            'validade'=> ['nullable', 'date'],
        ]);

        $equipamento->update($data);

        return response()->json($equipamento);
    }

    public function destroy(Equipamento $equipamento)
    {
        if ($equipamento->entregas()->whereNull('data_devolucao')->exists()) {
            return response()->json([
                'message' => 'Equipamento em uso, não pode ser excluído.'
            ], 422);
        }

        $equipamento->delete();
        return response()->json(['message' => 'Equipamento excluído.']);
    }
}
