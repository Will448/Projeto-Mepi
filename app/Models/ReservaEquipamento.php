<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservaEquipamento extends Model
{
    protected $fillable = [
        'funcionario_id',
        'equipamento_id',
        'data_inicio',
        'data_fim',
        'justificativa',
        'status',
        'observacao_rh',
        'reserva_convertida',
    ];

    protected $casts = [
        'data_inicio'         => 'date',
        'data_fim'            => 'date',
        'reserva_convertida'  => 'boolean',
    ];

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }

    public function equipamento()
    {
        return $this->belongsTo(Equipamento::class);
    }

    public function isPendente(): bool { return $this->status === 'pendente'; }
    public function isAprovado(): bool { return $this->status === 'aprovado'; }
    public function isNegado():   bool { return $this->status === 'negado'; }
}
