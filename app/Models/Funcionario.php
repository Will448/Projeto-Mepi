<?php

namespace App\Models;

class Funcionario extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable = [
        'nome', 'cpf', 'email', 'telefone', 'data_nascimento',
        'data_admissao', 'salario', 'status', 'cargo_id', 'user_id',
    ];

    protected $casts = [
        'data_admissao'   => 'date',
        'data_nascimento' => 'date',
    ];

    public function cargo()    { return $this->belongsTo(Cargo::class); }
    public function user()     { return $this->belongsTo(User::class); }
    public function ferias()   { return $this->hasMany(Ferias::class); }
    public function folhas()   { return $this->hasMany(FolhaPagamento::class); }
    public function entregas() { return $this->hasMany(EntregaEquipamento::class); }

    // Calcula tempo de empresa em meses (útil para exibir no perfil)
    public function getMesesTrabalhadosAttribute(): int
    {
        return $this->data_admissao->diffInMonths(now());
    }
}
