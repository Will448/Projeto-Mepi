<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cpf', 14)->unique();
            $table->string('email')->unique();
            $table->string('telefone')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->date('data_admissao');         // usado para calcular férias
            $table->decimal('salario', 10, 2);
            $table->enum('status', ['ativo', 'inativo', 'afastado'])->default('ativo');
            $table->foreignId('cargo_id')->constrained('cargos')->onDelete('restrict');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
    }
};
