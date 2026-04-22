<?php

namespace App\Services;

/**
 * FolhaService
 *
 * Centraliza toda a lógica de cálculo da folha de pagamento.
 * Usado pelo FolhaPagamentoController para não poluir o controller com regras de negócio.
 *
 * Tabelas vigentes 2024/2025:
 *  INSS  → contribuição progressiva por faixa (portaria MPS)
 *  IRRF  → alíquota efetiva com dedução por faixa (instrução normativa RFB)
 */
class FolhaService
{
    // ── TABELA INSS 2024 ────────────────────────────────────────────────
    // Cada entrada: [teto_da_faixa, aliquota]
    private array $tabelaInss = [
        [1412.00,  0.075],
        [2666.68,  0.090],
        [4000.03,  0.120],
        [7786.02,  0.140],
    ];

    // ── TABELA IRRF 2024 ────────────────────────────────────────────────
    // Cada entrada: [teto_da_faixa, aliquota, deducao_fixa]
    private array $tabelaIrrf = [
        [2112.00,  0.000,  0.00],
        [2826.65,  0.075,  158.40],
        [3751.05,  0.150,  370.40],
        [4664.68,  0.225,  651.73],
        [PHP_FLOAT_MAX, 0.275, 884.96],
    ];

    // ────────────────────────────────────────────────────────────────────

    /**
     * Calcula o INSS progressivo por faixa (não é alíquota única).
     */
    public function calcularInss(float $salarioBruto): float
    {
        $inss      = 0.0;
        $baseAnterior = 0.0;

        foreach ($this->tabelaInss as [$teto, $aliquota]) {
            if ($salarioBruto <= $baseAnterior) break;

            $base  = min($salarioBruto, $teto) - $baseAnterior;
            $inss += $base * $aliquota;
            $baseAnterior = $teto;

            if ($salarioBruto <= $teto) break;
        }

        return round($inss, 2);
    }

    /**
     * Calcula o IRRF.
     * Base de cálculo = salário bruto – INSS.
     */
    public function calcularIrrf(float $salarioBruto, float $inss): float
    {
        $base = $salarioBruto - $inss;

        foreach ($this->tabelaIrrf as [$teto, $aliquota, $deducao]) {
            if ($base <= $teto) {
                $irrf = ($base * $aliquota) - $deducao;
                return round(max($irrf, 0), 2);
            }
        }

        return 0.0;
    }

    /**
     * Calcula o adicional de 1/3 constitucional de férias.
     * Usado quando o funcionário tira férias no mês da folha.
     *
     * Fórmula:  (salário / 30) * dias_ferias * (1/3)
     */
    public function calcularAdicionalFerias(float $salario, int $diasFerias): float
    {
        $valorDiario    = $salario / 30;
        $remuneracao    = $valorDiario * $diasFerias;
        $adicional      = $remuneracao / 3;
        return round($adicional, 2);
    }

    /**
     * Monta o array completo da folha a partir dos inputs.
     * Retorna tudo que precisa ser salvo no banco.
     */
    public function calcular(
        float $salarioBruto,
        float $outrosAdicionais = 0,
        float $outrosDescontos  = 0,
        int   $diasFerias       = 0,
    ): array {
        $inss              = $this->calcularInss($salarioBruto);
        $irrf              = $this->calcularIrrf($salarioBruto, $inss);
        $adicionalFerias   = $diasFerias > 0
                             ? $this->calcularAdicionalFerias($salarioBruto, $diasFerias)
                             : 0.0;

        $liquido = $salarioBruto
                   - $inss
                   - $irrf
                   + $adicionalFerias
                   + $outrosAdicionais
                   - $outrosDescontos;

        return [
            'salario_bruto'      => round($salarioBruto, 2),
            'desconto_inss'      => $inss,
            'desconto_irrf'      => $irrf,
            'adicional_ferias'   => $adicionalFerias,
            'outros_adicionais'  => round($outrosAdicionais, 2),
            'outros_descontos'   => round($outrosDescontos, 2),
            'salario_liquido'    => round($liquido, 2),
        ];
    }
}
