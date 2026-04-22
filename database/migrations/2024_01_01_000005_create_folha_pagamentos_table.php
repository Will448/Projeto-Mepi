<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Simulação de folha CLT simplificada:
     *
     *  INSS 2024 (tabela progressiva):
     *   Até R$ 1.412,00        →  7,5%
     *   R$ 1.412,01 – 2.666,68 → 9,0%
     *   R$ 2.666,69 – 4.000,03 → 12,0%
     *   R$ 4.000,04 – 7.786,02 → 14,0%
     *
     *  IRRF (base = salário bruto – INSS):
     *   Até R$ 2.112,00        → isento
     *   R$ 2.112,01 – 2.826,65 → 7,5%  – R$ 158,40
     *   R$ 2.826,66 – 3.751,05 → 15,0% – R$ 370,40
     *   R$ 3.751,06 – 4.664,68 → 22,5% – R$ 651,73
     *   Acima de R$ 4.664,68   → 27,5% – R$ 884,96
     *
     *  Fórmula final:
     *   salario_liquido = salario_bruto - desconto_inss - desconto_irrf + adicional_ferias
     */
    public function up(): void
    {
        Schema::create('folha_pagamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('funcionario_id')->constrained('funcionarios')->onDelete('cascade');

            $table->string('competencia', 7); // "2026-04"
            $table->decimal('salario_bruto', 10, 2);

            // Descontos calculados pelo FolhaService
            $table->decimal('desconto_inss', 10, 2)->default(0);
            $table->decimal('desconto_irrf', 10, 2)->default(0);

            // Adicionais
            $table->decimal('adicional_ferias', 10, 2)->default(0); // 1/3 constitucional se o mês tiver férias
            $table->decimal('outros_descontos', 10, 2)->default(0); // faltas, etc
            $table->decimal('outros_adicionais', 10, 2)->default(0);

            // Resultado final (calculado pelo service, armazenado para histórico)
            $table->decimal('salario_liquido', 10, 2);

            $table->text('observacao')->nullable();
            $table->timestamps();

            // Evita duplicar folha do mesmo funcionário no mesmo mês
            $table->unique(['funcionario_id', 'competencia']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('folha_pagamentos');
    }
};
