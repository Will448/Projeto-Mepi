<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    // helpers de role
    public function isAdmin(): bool       { return $this->role === 'admin'; }
    public function isRh(): bool          { return $this->role === 'rh'; }
    public function isFuncionario(): bool { return $this->role === 'funcionario'; }

    public function funcionario()
    {
        return $this->hasOne(Funcionario::class);
    }
}