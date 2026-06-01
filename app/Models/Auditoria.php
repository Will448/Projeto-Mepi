<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $fillable = [
        'user_id', 'acao', 'modelo', 'modelo_id',
        'dados_antes', 'dados_depois', 'ip', 'descricao',
    ];

    protected $casts = [
        'dados_antes'  => 'array',
        'dados_depois' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper estático — usar em qualquer lugar do sistema
    public static function registrar(
        string $acao,
        string $modelo,
        ?int   $modeloId   = null,
        ?array $dadosAntes = null,
        ?array $dadosDepois= null,
        ?string $descricao = null,
    ): void {
        static::create([
            'user_id'      => auth()->id(),
            'acao'         => $acao,
            'modelo'       => $modelo,
            'modelo_id'    => $modeloId,
            'dados_antes'  => $dadosAntes,
            'dados_depois' => $dadosDepois,
            'ip'           => request()->ip(),
            'descricao'    => $descricao,
        ]);
    }
}
