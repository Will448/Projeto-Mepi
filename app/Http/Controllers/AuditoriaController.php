<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use Illuminate\Http\Request;

class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Auditoria::with('user')
            ->when($request->acao,   fn($q) => $q->where('acao', $request->acao))
            ->when($request->modelo, fn($q) => $q->where('modelo', $request->modelo))
            ->when($request->user_id,fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->data,   fn($q) => $q->whereDate('created_at', $request->data))
            ->orderByDesc('created_at');

        $auditorias = $query->paginate(20)->withQueryString();

        $modelos = Auditoria::selectRaw('DISTINCT modelo')->orderBy('modelo')->pluck('modelo');
        $acoes   = ['created','updated','deleted','login','logout'];

        return view('auditoria.index', compact('auditorias','modelos','acoes'));
    }

    public function show(Auditoria $auditoria)
    {
        $auditoria->load('user');
        return view('auditoria.show', compact('auditoria'));
    }
}
