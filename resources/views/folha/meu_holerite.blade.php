@extends('layout.app')
@section('title', 'Meu Holerite')
@section('page-title', 'Meu Holerite')

@section('content')

@if($folhas->isEmpty())
<div style="background:#fff;border:1px solid #e5e5dc;border-radius:14px;padding:48px;text-align:center;">
    <i class="bi bi-receipt-cutoff" style="font-size:2.5rem;color:#ccc;display:block;margin-bottom:12px;"></i>
    <p style="color:#aaa;font-size:.9rem;">Nenhuma folha disponível ainda.<br>Aguarde o RH gerar sua folha do mês.</p>
</div>
@else

{{-- Card do último holerite em destaque --}}
@php $ultima = $folhas->first(); @endphp
<div style="background:linear-gradient(135deg,var(--verde-escuro),var(--verde));border-radius:16px;padding:28px 32px;margin-bottom:24px;color:#fff;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:16px;">
        <div>
            <p style="font-size:.72rem;color:rgba(255,255,255,.6);margin:0;text-transform:uppercase;letter-spacing:1px;">Último Holerite</p>
            <p style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:800;margin:4px 0 0;color:var(--amarelo);">
                {{ $ultima->competencia_formatada }}
            </p>
        </div>
        <div style="text-align:right;">
            <p style="font-size:.72rem;color:rgba(255,255,255,.6);margin:0;">Salário Líquido</p>
            <p style="font-family:'Syne',sans-serif;font-size:2rem;font-weight:800;margin:4px 0 0;color:#fff;">
                R$ {{ number_format($ultima->salario_liquido,2,',','.') }}
            </p>
        </div>
    </div>

    <div style="display:flex;gap:24px;margin-top:20px;flex-wrap:wrap;">
        @foreach([
            ['Bruto',   $ultima->salario_bruto,    '#fff'],
            ['INSS',    $ultima->desconto_inss,     '#fca5a5'],
            ['IRRF',    $ultima->desconto_irrf,     '#fca5a5'],
        ] as [$l,$v,$c])
        <div>
            <p style="font-size:.7rem;color:rgba(255,255,255,.5);margin:0;">{{ $l }}</p>
            <p style="font-size:.9rem;font-weight:700;color:{{ $c }};margin:2px 0 0;">
                R$ {{ number_format($v,2,',','.') }}
            </p>
        </div>
        @endforeach
        @if($ultima->adicional_ferias > 0)
        <div>
            <p style="font-size:.7rem;color:rgba(255,255,255,.5);margin:0;">Adicional Férias</p>
            <p style="font-size:.9rem;font-weight:700;color:var(--amarelo);margin:2px 0 0;">
                + R$ {{ number_format($ultima->adicional_ferias,2,',','.') }}
            </p>
        </div>
        @endif
    </div>
</div>

{{-- Histórico --}}
<div class="card-mepi">
    <div class="card-mepi-header">
        <h6><i class="bi bi-clock-history me-2"></i>Histórico de Holerites</h6>
    </div>
    <div class="card-mepi-body p-0">
        <table class="table table-mepi mb-0">
            <thead>
                <tr>
                    <th>Competência</th>
                    <th>Bruto</th>
                    <th>INSS</th>
                    <th>IRRF</th>
                    <th>Férias (+)</th>
                    <th style="color:var(--verde);">Líquido</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($folhas as $f)
                <tr>
                    <td style="font-weight:600;font-size:.87rem;">{{ $f->competencia_formatada }}</td>
                    <td style="font-size:.83rem;">R$ {{ number_format($f->salario_bruto,2,',','.') }}</td>
                    <td style="font-size:.82rem;color:#dc2626;">- R$ {{ number_format($f->desconto_inss,2,',','.') }}</td>
                    <td style="font-size:.82rem;color:#dc2626;">- R$ {{ number_format($f->desconto_irrf,2,',','.') }}</td>
                    <td style="font-size:.82rem;color:var(--verde);">
                        @if($f->adicional_ferias > 0)
                            + R$ {{ number_format($f->adicional_ferias,2,',','.') }}
                        @else
                            <span style="color:#ccc;">—</span>
                        @endif
                    </td>
                    <td style="font-weight:800;font-size:.9rem;color:var(--verde);">
                        R$ {{ number_format($f->salario_liquido,2,',','.') }}
                    </td>
                    <td>
                        <a href="{{ route('funcionario.holerite.show', $f) }}"
                            target="_blank"
                            style="font-size:.78rem;color:var(--verde);text-decoration:none;">
                                <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endif

@endsection
