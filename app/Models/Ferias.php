<?php

namespace App\Models;

class Ferias extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable = [
        'funcionario_id', 'periodo_aquisitivo_inicio', 'periodo_aquisitivo_fim',
        'data_inicio', 'data_fim', 'dias_gozados',
        'abono_pecuniario', 'dias_abono', 'status', 'observacao',
    ];

    protected $casts = [
        'data_inicio'                => 'date',
        'data_fim'                   => 'date',
        'periodo_aquisitivo_inicio'  => 'date',
        'periodo_aquisitivo_fim'     => 'date',
        'abono_pecuniario'           => 'boolean',
    ];

    public function funcionario() { return $this->belongsTo(Funcionario::class); }

    public function isPendente(): bool  { return $this->status === 'pendente'; }
    public function isAprovado(): bool  { return $this->status === 'aprovado'; }
    public function isNegado(): bool    { return $this->status === 'negado'; }
}
