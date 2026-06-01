

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<style>
  body { font-family:'Segoe UI',sans-serif; background:#f0f2ee; margin:0; padding:32px 16px; }
  .card { background:#fff; border-radius:12px; max-width:520px; margin:0 auto; overflow:hidden; }
  .header { background:linear-gradient(135deg,#0f3d21,#1a6b3a); padding:28px 32px; color:#fff; }
  .header h1 { font-size:1.3rem; margin:0 0 4px; }
  .competencia { font-size:1.1rem; font-weight:800; color:#f5c400; margin-top:6px; }
  .body { padding:28px 32px; }
  .row { display:flex; justify-content:space-between; padding:9px 0; border-bottom:1px solid #f0f0e8; font-size:.87rem; }
  .row span:first-child { color:#888; }
  .row span:last-child  { font-weight:600; }
  .liquido { display:flex; justify-content:space-between; align-items:center; padding:16px; background:rgba(26,107,58,.07); border-radius:10px; margin-top:16px; }
  .liquido span:first-child { font-weight:700; font-size:.95rem; color:#0f3d21; }
  .liquido span:last-child  { font-size:1.4rem; font-weight:800; color:#1a6b3a; }
  .footer { padding:16px 32px; background:#f7f5ee; font-size:.75rem; color:#aaa; text-align:center; }
</style>
</head>
<body>
<div class="card">
  <div class="header">
    <h1>⚡ MEPI</h1>
    <p>Holerite disponível</p>
    <div class="competencia">{{ $folha->competencia_formatada }}</div>
  </div>
  <div class="body">
    <p style="font-size:.95rem;color:#333;margin-bottom:20px;">
        Olá, <strong>{{ $folha->funcionario->nome }}</strong>!<br>
        <span style="font-size:.87rem;color:#666;">Seu holerite de {{ $folha->competencia_formatada }} está disponível no sistema.</span>
    </p>

    <div class="row"><span>Salário Bruto</span><span>R$ {{ number_format($folha->salario_bruto,2,',','.') }}</span></div>
    <div class="row"><span>(-) INSS</span><span style="color:#dc2626;">R$ {{ number_format($folha->desconto_inss,2,',','.') }}</span></div>
    <div class="row"><span>(-) IRRF</span><span style="color:#dc2626;">R$ {{ number_format($folha->desconto_irrf,2,',','.') }}</span></div>
    @if($folha->adicional_ferias > 0)
    <div class="row"><span>(+) Adicional Férias</span><span style="color:#1a6b3a;">R$ {{ number_format($folha->adicional_ferias,2,',','.') }}</span></div>
    @endif

    <div class="liquido">
      <span>Salário Líquido</span>
      <span>R$ {{ number_format($folha->salario_liquido,2,',','.') }}</span>
    </div>

    <p style="margin-top:20px;font-size:.82rem;color:#888;text-align:center;">
        Acesse o sistema para ver o holerite completo.
    </p>
  </div>
  <div class="footer">MEPI © {{ date('Y') }} — Este é um e-mail automático.</div>
</div>
</body>
</html>
