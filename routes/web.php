<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\FeriasController;
use App\Http\Controllers\FolhaPagamentoController;
use App\Http\Controllers\EquipamentoController;
use App\Http\Controllers\EntregaEquipamentoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReservaEquipamentoController;

// ── Página inicial (landing) ──────────────────────────────
Route::get('/', function () {

     $apiKey = env('GNEWS_TOKEN');

    // 👥 RH (filtro melhor em inglês)
      $rh = Http::get('https://gnews.io/api/v4/search', [
        'q' => 'human resources OR HR OR recruitment OR hiring OR job',
        'lang' => 'en',
        'max' => 3,
        'token' => $apiKey
    ])->json();

    


    return view('welcome', compact('rh'));

})->name('home');

// ── Autenticação ──────────────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout',[AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Admin ──
Route::prefix('admin')->middleware(['auth','role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    Route::resource('funcionarios', FuncionarioController::class);
    Route::resource('cargos',       CargoController::class);
    Route::resource('ferias',       FeriasController::class)->parameters(['ferias' => 'ferias']);
    Route::get('ferias/{ferias}/editar', [FeriasController::class, 'edit'])->name('ferias.edit');
    Route::put('ferias/{ferias}/editar', [FeriasController::class, 'editarDatas'])->name('ferias.editarDatas');
    Route::post('folha/simular', [FolhaPagamentoController::class, 'simular'])->name('folha.simular'); // ← ANTES
    Route::resource('folha',        FolhaPagamentoController::class);
    Route::resource('equipamentos', EquipamentoController::class);
    Route::resource('entregas',     EntregaEquipamentoController::class);
    Route::resource('usuarios',     UserController::class)->parameters(['usuarios' => 'user']);
    Route::get('reservas',                      [ReservaEquipamentoController::class, 'index'])->name('reservas.index');
    Route::get('reservas/{reserva}',            [ReservaEquipamentoController::class, 'show'])->name('reservas.show');
    Route::put('reservas/{reserva}',            [ReservaEquipamentoController::class, 'update'])->name('reservas.update');
    Route::post('reservas/{reserva}/converter', [ReservaEquipamentoController::class, 'converter'])->name('reservas.converter');
});

// ── RH ──
Route::prefix('rh')->middleware(['auth','role:rh,admin'])->name('rh.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'rh'])->name('dashboard');
    Route::resource('funcionarios', FuncionarioController::class);
    Route::resource('cargos',       CargoController::class);
    Route::resource('ferias',       FeriasController::class)->parameters(['ferias' => 'ferias']);
    Route::get('ferias/{ferias}/editar', [FeriasController::class, 'edit'])->name('ferias.edit');
    Route::put('ferias/{ferias}/editar', [FeriasController::class, 'editarDatas'])->name('ferias.editarDatas');
    Route::post('folha/simular', [FolhaPagamentoController::class, 'simular'])->name('folha.simular'); // ← ANTES
    Route::resource('folha',        FolhaPagamentoController::class);
    Route::resource('equipamentos', EquipamentoController::class);
    Route::resource('entregas',     EntregaEquipamentoController::class);
    Route::get('reservas',                      [ReservaEquipamentoController::class, 'index'])->name('reservas.index');
    Route::get('reservas/{reserva}',            [ReservaEquipamentoController::class, 'show'])->name('reservas.show');
    Route::put('reservas/{reserva}',            [ReservaEquipamentoController::class, 'update'])->name('reservas.update');
    Route::post('reservas/{reserva}/converter', [ReservaEquipamentoController::class, 'converter'])->name('reservas.converter');
});

// ── Funcionário ───────────────────────────────────────────
Route::prefix('funcionario')->middleware(['auth','role:funcionario'])->name('funcionario.')->group(function () {
    Route::get('/dashboard',   [DashboardController::class, 'funcionario'])->name('dashboard');
    Route::get('/perfil',      [FuncionarioController::class, 'perfil'])->name('perfil');
    Route::get('/ferias',      [FeriasController::class, 'minhasFerias'])->name('ferias');
    Route::post('/ferias',     [FeriasController::class, 'solicitar'])->name('ferias.solicitar');
    Route::get('/holerite',    [FolhaPagamentoController::class, 'meuHolerite'])->name('holerite');
    Route::get('/holerite/{folha}', [FolhaPagamentoController::class, 'show'])->name('holerite.show');
    Route::get('/equipamentos',[EntregaEquipamentoController::class, 'meusEquipamentos'])->name('equipamentos');
     Route::get('/reservas',                        [ReservaEquipamentoController::class, 'minhasReservas'])->name('reservas');
    Route::post('/reservas',                       [ReservaEquipamentoController::class, 'solicitar'])->name('reservas.solicitar');
    Route::delete('/reservas/{reserva}/cancelar',  [ReservaEquipamentoController::class, 'cancelar'])->name('reservas.cancelar');

});