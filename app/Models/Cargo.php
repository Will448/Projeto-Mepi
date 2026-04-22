<?php

namespace App\Models;

class Cargo extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable = ['nome', 'descricao', 'salario_base'];

    public function funcionarios()
    {
        return $this->hasMany(Funcionario::class);
    }
}
