@extends('layout.app')
@section('title', 'Meu Perfil')
@section('page-title', 'Meu Perfil')

@section('content')

{{-- Cabeçalho --}}
<div style="background:linear-gradient(135deg,var(--verde-escuro),var(--verde));border-radius:16px;padding:28px 32px;margin-bottom:24px;color:#fff;display:flex;align-items:center;gap:20px;">
    <div style="width:60px;height:60px;border-radius:50%;background:rgba(255,255,255,.15);border:2px solid rgba(255,255,255,.3);display:flex;align-items:center;justify-content:center;font-size:1.6rem;font-weight:800;flex-shrink:0;">
        {{ strtoupper(substr($funcionario->nome, 0, 1)) }}
    </div>
    <div>
        <h4 style="font-family:'Syne',sans-serif;font-weight:800;margin:0 0 4px;">{{ $funcionario->nome }}</h4>
        <p style="margin:0;font-size:.88rem;color:rgba(255,255,255,.7);">
            {{ $funcionario->cargo->nome }} &nbsp;·&nbsp; Admitido em {{ $funcionario->data_admissao->format('d/m/Y') }}
        </p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card-mepi">
            <div class="card-mepi-header">
                <h6><i class="bi bi-person me-2"></i>Meus Dados</h6>
            </div>
            <div class="card-mepi-body">
                @foreach([
                    ['Cargo',       $funcionario->cargo->nome],
                    ['E-mail',      $funcionario->email],
                    ['Telefone',    $funcionario->telefone ?? '—'],
                    ['Admissão',    $funcionario->data_admissao->format('d/m/Y')],
                    ['Tempo',       $funcionario->meses_trabalhados . ' meses na empresa'],
                ] as [$l, $v])
                <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid #f0f0e8;">
                    <span style="font-size:.82rem;color:#888;">{{ $l }}</span>
                    <span style="font-size:.85rem;font-weight:600;">{{ $v }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card-mepi">
            <div class="card-mepi-header">
                <h6><i class="bi bi-shield-check me-2"></i>Meus EPIs</h6>
            </div>
            <div class="card-mepi-body p-0">
                <table class="table table-mepi mb-0">
                    <thead><tr><th>EPI</th><th>Entregue</th><th>Situação</th></tr></thead>
                    <tbody>
                        @forelse($funcionario->entregas as $e)
                        <tr>
                            <td style="font-size:.83rem;font-weight:600;">{{ $e->equipamento->nome }}</td>
                            <td style="font-size:.8rem;">{{ $e->data_entrega->format('d/m/Y') }}</td>
                            <td>
                                @if($e->data_devolucao)
                                    <span style="color:#aaa;font-size:.78rem;">Devolvido {{ $e->data_devolucao->format('d/m/Y') }}</span>
                                @else
                                    <span class="badge-ativo">Em uso</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3" style="font-size:.82rem;">Nenhum EPI em seu nome.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
