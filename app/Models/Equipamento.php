<?php

namespace App\Models;


class Equipamento extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable = ['nome', 'numero_serie', 'tipo', 'validade', 'status'];

    protected $casts = ['validade' => 'date'];

    public function entregas() { return $this->hasMany(EntregaEquipamento::class); }

    public function estaDisponivel(): bool { return $this->status === 'disponivel'; }
}