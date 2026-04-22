<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Regra CLT:
     *  - Funcionário adquire direito a 30 dias de férias a cada 12 meses trabalhados (período aquisitivo).
     *  - O gozo deve ocorrer dentro dos 12 meses seguintes (período concessivo).
     *  - Pode ser fracionado em até 3 períodos (1 deles >= 14 dias).
     *
     *  Campos:
     *   periodo_aquisitivo_inicio / fim → o ano de trabalho que gerou o direito
     *   data_inicio / data_fim          → quando o funcionário vai tirar as férias
     *   dias_gozados                    → calculado pelo controller ao salvar
     *   abono_pecuniario                → vender até 10 dos 30 dias (true/false)
     *   dias_abono                      → quantos dias vendeu (0–10)
     *   status                          → pendente | aprovado | negado
     */
    public function up(): void
    {
        Schema::create('ferias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('funcionario_id')->constrained('funcionarios')->onDelete('cascade');

            // Período aquisitivo (qual "ano de trabalho" gerou esse direito)
            $table->date('periodo_aquisitivo_inicio');
            $table->date('periodo_aquisitivo_fim');

            // Período de gozo solicitado
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->unsignedTinyInteger('dias_gozados')->default(0); // preenchido ao salvar

            // Abono pecuniário (venda de dias)
            $table->boolean('abono_pecuniario')->default(false);
            $table->unsignedTinyInteger('dias_abono')->default(0); // max 10

            $table->enum('status', ['pendente', 'aprovado', 'negado'])->default('pendente');
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ferias');
    }
};
