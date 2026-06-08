<?php
// ============================================================
// ARQUIVO 4: app/Http/Controllers/Api/FeriasApiController.php
// ============================================================
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ferias;
use Illuminate\Http\Request;

class FeriasApiController extends Controller
{
    public function index(Request $request)
    {
        $ferias = Ferias::with('funcionario')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json($ferias);
    }

    public function show(Ferias $ferias)
    {
        return response()->json($ferias->load('funcionario'));
    }

    public function atualizarStatus(Request $request, Ferias $ferias)
    {
        $request->validate([
            'status'     => ['required', 'in:aprovado,negado'],
            'observacao' => ['nullable', 'string'],
        ]);

        $ferias->update([
            'status'     => $request->status,
            'observacao' => $request->observacao,
        ]);

        return response()->json([
            'message' => 'Status atualizado.',
            'ferias'  => $ferias,
        ]);
    }
}

