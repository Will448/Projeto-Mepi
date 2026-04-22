<?php

namespace App\Models;

class EntregaEquipamento extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable = [
        'funcionario_id', 'equipamento_id',
        'data_entrega', 'data_devolucao', 'observacao',
    ];

    protected $casts = [
        'data_entrega'    => 'date',
        'data_devolucao'  => 'date',
    ];

    public function funcionario()  { return $this->belongsTo(Funcionario::class); }
    public function equipamento()  { return $this->belongsTo(Equipamento::class); }

    public function foidevolvido(): bool { return !is_null($this->data_devolucao); }
}
