<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('acao');          // created, updated, deleted, login, logout
            $table->string('modelo');        // Funcionario, Ferias, Equipamento...
            $table->unsignedBigInteger('modelo_id')->nullable();
            $table->json('dados_antes')->nullable();  // snapshot antes da alteração
            $table->json('dados_depois')->nullable(); // snapshot depois
            $table->string('ip', 45)->nullable();
            $table->text('descricao')->nullable();    // texto legível da ação
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditorias');
    }
};
