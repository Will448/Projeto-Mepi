<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fluxo:
     *  1. Funcionário solicita reserva de um equipamento disponível (status = pendente)
     *  2. RH/Admin aprova → equipamento continua disponível até a data_inicio
     *  3. Na data_inicio o RH registra a entrega real (cria EntregaEquipamento)
     *  4. RH/Admin pode negar → equipamento fica disponível para outros
     *
     *  O campo reserva_convertida indica se a reserva já virou entrega real.
     */
public function up(): void
{
    // Se a tabela já existe, só garante que está completa
    if (!Schema::hasTable('reservas_equipamentos')) {
        Schema::create('reservas_equipamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('funcionario_id')->constrained('funcionarios')->onDelete('cascade');
            $table->foreignId('equipamento_id')->constrained('equipamentos')->onDelete('cascade');
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->text('justificativa')->nullable();
            $table->enum('status', ['pendente', 'aprovado', 'negado'])->default('pendente');
            $table->text('observacao_rh')->nullable();
            $table->boolean('reserva_convertida')->default(false);
            $table->timestamps();
        });
    }
}

    public function down(): void
    {
        Schema::dropIfExists('reservas_equipamentos');
    }
};
