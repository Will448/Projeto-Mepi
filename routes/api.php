<?php
// ============================================================
// ARQUIVO 1: routes/api.php — rotas da API REST
// ============================================================
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\FuncionarioApiController;
use App\Http\Controllers\Api\FeriasApiController;
use App\Http\Controllers\Api\EquipamentoApiController;

// Login — retorna token
Route::post('/login', [AuthApiController::class, 'login']);

// Rotas protegidas por token (Sanctum)
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/me',      [AuthApiController::class, 'me']);

    // Funcionários
    Route::apiResource('funcionarios', FuncionarioApiController::class);

    // Férias
    Route::get('ferias',               [FeriasApiController::class, 'index']);
    Route::get('ferias/{ferias}',      [FeriasApiController::class, 'show']);
    Route::put('ferias/{ferias}/status',[FeriasApiController::class, 'atualizarStatus']);

    // Equipamentos
    Route::apiResource('equipamentos', EquipamentoApiController::class);
});

