<?php

namespace App\Services;

use App\Models\Funcionario;
use App\Models\Ferias;
use Carbon\Carbon;

/**
 * FeriasService
 *
 * Lógica CLT para cálculo de períodos aquisitivos e saldo de dias.
 *
 * Regras:
 *  - A cada 12 meses trabalhados → 30 dias de direito (período aquisitivo).
 *  - O gozo deve ocorrer nos 12 meses seguintes (período concessivo).
 *  - Pode ser fracionado: máximo 3 períodos, um deles >= 14 dias.
 *  - O funcionário pode vender até 10 dias (abono pecuniário).
 *  - Dias efetivamente gozados = dias solicitados - dias de abono.
 */
class FeriasService
{
    /**
     * Retorna todos os períodos aquisitivos do funcionário desde a admissão até hoje,
     * com o saldo de dias disponíveis em cada um.
     *
     * Exemplo de retorno:
     * [
     *   [
     *     'periodo_aquisitivo_inicio' => '2023-01-10',
     *     'periodo_aquisitivo_fim'    => '2024-01-09',
     *     'periodo_concessivo_fim'    => '2025-01-09',
     *     'dias_direito'              => 30,
     *     'dias_gozados'              => 15,
     *     'dias_abonados'             => 5,
     *     'saldo_disponivel'          => 10,
     *     'vencido'                   => false,
     *   ],
     *   ...
     * ]
     */
    public function periodosAquisitivos(Funcionario $funcionario): array
    {
        $admissao = Carbon::parse($funcionario->data_admissao);
        $hoje     = Carbon::today();
        $periodos = [];

        $inicio = $admissao->copy();

        while ($inicio->copy()->addYear()->subDay()->lte($hoje)) {
            $fim              = $inicio->copy()->addYear()->subDay();
            $fimConcessivo    = $fim->copy()->addYear();

            // Busca férias aprovadas deste período aquisitivo
            $feriasGozadas = Ferias::where('funcionario_id', $funcionario->id)
                ->where('status', 'aprovado')
                ->where('periodo_aquisitivo_inicio', $inicio->toDateString())
                ->get();

            $diasGozados  = $feriasGozadas->sum('dias_gozados');
            $diasAbonados = $feriasGozadas->sum('dias_abono');
            $saldo        = 30 - $diasGozados - $diasAbonados;

            $periodos[] = [
                'periodo_aquisitivo_inicio' => $inicio->toDateString(),
                'periodo_aquisitivo_fim'    => $fim->toDateString(),
                'periodo_concessivo_fim'    => $fimConcessivo->toDateString(),
                'dias_direito'              => 30,
                'dias_gozados'              => $diasGozados,
                'dias_abonados'             => $diasAbonados,
                'saldo_disponivel'          => max($saldo, 0),
                'vencido'                   => $hoje->gt($fimConcessivo),
            ];

            $inicio->addYear();
        }

        return $periodos;
    }

    /**
     * Valida uma solicitação de férias antes de salvar.
     * Retorna array de erros (vazio = tudo ok).
     */
    public function validarSolicitacao(
        Funcionario $funcionario,
        string $periodoAquisitivoInicio,
        string $dataInicio,
        string $dataFim,
        bool   $abono,
        int    $diasAbono,
    ): array {
        $erros = [];

        $inicio   = Carbon::parse($dataInicio);
        $fim      = Carbon::parse($dataFim);
        $diasGoza = $inicio->diffInDays($fim) + 1;

        if ($diasGoza < 5) {
            $erros[] = 'O período mínimo de férias é de 5 dias.';
        }

        // Verifica saldo no período aquisitivo
        $periodos = collect($this->periodosAquisitivos($funcionario));
        $periodo  = $periodos->firstWhere('periodo_aquisitivo_inicio', $periodoAquisitivoInicio);

        if (!$periodo) {
            $erros[] = 'Período aquisitivo inválido.';
            return $erros;
        }

        if ($periodo['saldo_disponivel'] < $diasGoza) {
            $erros[] = "Saldo insuficiente. Disponível: {$periodo['saldo_disponivel']} dias.";
        }

        if ($abono && $diasAbono > 10) {
            $erros[] = 'O abono pecuniário não pode ultrapassar 10 dias.';
        }

        if ($abono && ($diasGoza - $diasAbono) < 14) {
            $erros[] = 'Ao vender dias, o período de gozo restante deve ser de no mínimo 14 dias.';
        }

        // Verifica se não ultrapassa 3 fracionamentos no mesmo período aquisitivo
        $fracoes = Ferias::where('funcionario_id', $funcionario->id)
            ->where('status', '!=', 'negado')
            ->where('periodo_aquisitivo_inicio', $periodoAquisitivoInicio)
            ->count();

        if ($fracoes >= 3) {
            $erros[] = 'Limite de 3 fracionamentos por período aquisitivo atingido.';
        }

        return $erros;
    }

    /**
     * Prepara os dados para salvar no banco após validação.
     */
    public function prepararDados(
        Funcionario $funcionario,
        string $periodoAquisitivoInicio,
        string $dataInicio,
        string $dataFim,
        bool   $abono,
        int    $diasAbono,
    ): array {
        $inicio   = Carbon::parse($dataInicio);
        $fim      = Carbon::parse($dataFim);
        $diasGoza = $inicio->diffInDays($fim) + 1;

        // Recalcula fim do período aquisitivo
        $paInicio = Carbon::parse($periodoAquisitivoInicio);
        $paFim    = $paInicio->copy()->addYear()->subDay();

        return [
            'funcionario_id'             => $funcionario->id,
            'periodo_aquisitivo_inicio'  => $paInicio->toDateString(),
            'periodo_aquisitivo_fim'     => $paFim->toDateString(),
            'data_inicio'                => $dataInicio,
            'data_fim'                   => $dataFim,
            'dias_gozados'               => $diasGoza,
            'abono_pecuniario'           => $abono,
            'dias_abono'                 => $abono ? $diasAbono : 0,
            'status'                     => 'pendente',
        ];
    }
}
