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

Route::prefix('admin')->middleware(['auth','role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    Route::resource('funcionarios', FuncionarioController::class);
    Route::resource('cargos',       CargoController::class);
    Route::resource('ferias',       FeriasController::class)->parameters(['ferias' => 'ferias']);
    Route::get('ferias/{ferias}/editar', [FeriasController::class, 'edit'])->name('ferias.edit');
    Route::put('ferias/{ferias}/editar', [FeriasController::class, 'editarDatas'])->name('ferias.editarDatas');
    Route::resource('folha',        FolhaPagamentoController::class);
    Route::post('folha/simular', [FolhaPagamentoController::class, 'simular'])->name('folha.simular');
    Route::resource('equipamentos', EquipamentoController::class);
    Route::resource('entregas',     EntregaEquipamentoController::class);
    Route::resource('usuarios',     UserController::class)->parameters(['usuarios' => 'user']);
});

// ── RH ────────────────────────────────────────────────────
Route::prefix('rh')->middleware(['auth','role:rh,admin'])->name('rh.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'rh'])->name('dashboard');
    Route::resource('funcionarios', FuncionarioController::class);
    Route::resource('cargos',       CargoController::class);
    Route::resource('ferias',       FeriasController::class)->parameters(['ferias' => 'ferias']);
    Route::get('ferias/{ferias}/editar', [FeriasController::class, 'edit'])->name('ferias.edit');
    Route::put('ferias/{ferias}/editar', [FeriasController::class, 'editarDatas'])->name('ferias.editarDatas');
    Route::post('folha/simular', [FolhaPagamentoController::class, 'simular'])->name('folha.simular');
    Route::resource('folha',        FolhaPagamentoController::class);
    Route::resource('equipamentos', EquipamentoController::class);
    Route::resource('entregas',     EntregaEquipamentoController::class);
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
});