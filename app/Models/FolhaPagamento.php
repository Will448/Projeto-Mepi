<?php

namespace App\Models;

class FolhaPagamento extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable = [
        'funcionario_id', 'competencia',
        'salario_bruto', 'desconto_inss', 'desconto_irrf',
        'adicional_ferias', 'outros_adicionais', 'outros_descontos',
        'salario_liquido', 'observacao',
    ];

    protected $casts = [
        'salario_bruto'     => 'float',
        'desconto_inss'     => 'float',
        'desconto_irrf'     => 'float',
        'adicional_ferias'  => 'float',
        'outros_adicionais' => 'float',
        'outros_descontos'  => 'float',
        'salario_liquido'   => 'float',
    ];

    public function funcionario() { return $this->belongsTo(Funcionario::class); }

    // Formata competencia "2026-04" → "Abril/2026"
    public function getCompetenciaFormatadaAttribute(): string
    {
        [$ano, $mes] = explode('-', $this->competencia);
        $meses = [
            '01'=>'Janeiro','02'=>'Fevereiro','03'=>'Março','04'=>'Abril',
            '05'=>'Maio','06'=>'Junho','07'=>'Julho','08'=>'Agosto',
            '09'=>'Setembro','10'=>'Outubro','11'=>'Novembro','12'=>'Dezembro',
        ];
        return ($meses[$mes] ?? $mes) . '/' . $ano;
    }
}