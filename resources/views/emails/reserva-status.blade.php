
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<style>
  body { font-family:'Segoe UI',sans-serif; background:#f0f2ee; margin:0; padding:32px 16px; }
  .card { background:#fff; border-radius:12px; max-width:520px; margin:0 auto; overflow:hidden; }
  .header { background:linear-gradient(135deg,#0f3d21,#1a6b3a); padding:28px 32px; color:#fff; }
  .header h1 { font-size:1.3rem; margin:0 0 4px; }
  .header p  { font-size:.85rem; color:rgba(255,255,255,.7); margin:0; }
  .body { padding:28px 32px; }
  .status-badge { display:inline-block; padding:6px 18px; border-radius:20px; font-weight:700; font-size:.9rem; margin-bottom:20px; }
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
        Olá, <strong>{{ $reserva->funcionario->nome }}</strong>!
    </p>
    <p style="font-size:.87rem;color:#555;margin-bottom:16px;">
        Sua solicitação de reserva de equipamento foi analisada:
    </p>

    <span class="status-badge {{ $reserva->status }}">
        {{ $reserva->status === 'aprovado' ? '✅ Reserva Aprovada' : '❌ Reserva Negada' }}
    </span>

    <div class="row"><span>Equipamento</span><span>{{ $reserva->equipamento->nome }}</span></div>
    <div class="row"><span>Data de uso</span><span>{{ $reserva->data_inicio->format('d/m/Y') }}</span></div>
    @if($reserva->data_fim)
    <div class="row"><span>Previsão devolução</span><span>{{ $reserva->data_fim->format('d/m/Y') }}</span></div>
    @endif

    @if($reserva->observacao_rh)
    <div class="obs"><strong>Observação do RH:</strong><br>{{ $reserva->observacao_rh }}</div>
    @endif
  </div>
  <div class="footer">MEPI © {{ date('Y') }} — Este é um e-mail automático.</div>
</div>
</body>
</html>
