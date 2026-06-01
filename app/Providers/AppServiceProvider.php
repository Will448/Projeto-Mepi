<?php

// app/Providers/AppServiceProvider.php
// Substitua o método boot() pelo trecho abaixo
// (mantendo o resto do arquivo igual)

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Funcionario;
use App\Models\Ferias;
use App\Models\Equipamento;
use App\Observers\FuncionarioObserver;
use App\Observers\FeriasObserver;
use App\Observers\EquipamentoObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Funcionario::observe(FuncionarioObserver::class);
        Ferias::observe(FeriasObserver::class);
        Equipamento::observe(EquipamentoObserver::class);
    }
}
