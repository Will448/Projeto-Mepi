<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipamentos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('numero_serie')->unique();
            $table->string('tipo'); // EPI, ferramenta, eletrônico…
            $table->date('validade')->nullable();
            $table->enum('status', ['disponivel', 'entregue', 'manutencao'])->default('disponivel');
            $table->timestamps();
        });

        Schema::create('entrega_equipamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('funcionario_id')->constrained('funcionarios')->onDelete('cascade');
            $table->foreignId('equipamento_id')->constrained('equipamentos')->onDelete('cascade');
            $table->date('data_entrega');
            $table->date('data_devolucao')->nullable(); // null = ainda com o funcionário
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entrega_equipamentos');
        Schema::dropIfExists('equipamentos');
    }
};
