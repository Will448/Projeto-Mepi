<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: 'Segoe UI', sans-serif; background:#f0f2ee; margin:0; padding:32px 16px; }
  .card { background:#fff; border-radius:12px; max-width:520px; margin:0 auto; overflow:hidden; }
  .header { background:linear-gradient(135deg,#0f3d21,#1a6b3a); padding:28px 32px; color:#fff; }
  .header h1 { font-size:1.3rem; margin:0 0 4px; }
  .header p  { font-size:.85rem; color:rgba(255,255,255,.7); margin:0; }
  .body { padding:28px 32px; }
  .status-badge {
      display:inline-block; padding:6px 18px; border-radius:20px;
      font-weight:700; font-size:.9rem; margin-bottom:20px;
  }
  .aprovado { background:rgba(26,107,58,.1); color:#1a6b3a; }
  .negado   { background:rgba(239,68,68,.1); color:#dc2626; }
  .row { display:flex; justify-content:space-between; padding:9px 0; border-bottom:1px solid #f0f0e8; font-size:.87rem; }
  .row span:first-child { color:#888; }
  .row span:last-child  { font-weight:600; }
  .footer { padding:16px 32px; background:#f7f5ee; font-size:.75rem; color:#aaa; text-align:center; }
  .obs { margin-top:18px; padding:14px; background:#f7f5ee; border-radius:8px; font-size:.85rem; color:#555; }
</style>
</head>
<body>
<div class="card">
  <div class="header">
    <h1>⚡ MEPI</h1>
    <p>Monitoramento Inteligente de Pessoas e Equipamentos</p>
  </div>
  <div class="body">
    <p style="font-size:.95rem;color:#333;margin-bottom:16px;">
        Olá, <strong>{{ $ferias->funcionario->nome }}</strong>!
    </p>
    <p style="font-size:.87rem;color:#555;margin-bottom:16px;">
        Sua solicitação de férias foi analisada pelo RH:
    </p>

    <span class="status-badge {{ $ferias->status }}">
        {{ $ferias->status === 'aprovado' ? '✅ Férias Aprovadas' : '❌ Férias Negadas' }}
    </span>

    <div class="row"><span>Período solicitado</span><span>{{ $ferias->data_inicio->format('d/m/Y') }} → {{ $ferias->data_fim->format('d/m/Y') }}</span></div>
    <div class="row"><span>Dias</span><span>{{ $ferias->dias_gozados }} dias</span></div>
    @if($ferias->abono_pecuniario)
    <div class="row"><span>Abono pecuniário</span><span>{{ $ferias->dias_abono }} dias</span></div>
    @endif

    @if($ferias->observacao)
    <div class="obs"><strong>Observação do RH:</strong><br>{{ $ferias->observacao }}</div>
    @endif

    <p style="margin-top:20px;font-size:.82rem;color:#888;">
        Em caso de dúvidas, entre em contato com o setor de RH.
    </p>
  </div>
  <div class="footer">MEPI © {{ date('Y') }} — Este é um e-mail automático.</div>
</div>
</body>
</html>

