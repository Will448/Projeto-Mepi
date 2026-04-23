@extends('layout.app')
@section('title', 'Gerar Folha')
@section('page-title', 'Gerar Folha de Pagamento')

@section('content')

<div class="mb-4">
    <a href="{{ route(auth()->user()->role.'.folha.index') }}" style="color:#888;text-decoration:none;font-size:.85rem;">
        <i class="bi bi-arrow-left me-1"></i>Voltar para lista
    </a>
</div>

<div class="row g-4" style="max-width:1000px;">

    {{-- ── FORMULÁRIO ────────────────────────────────────────── --}}
    <div class="col-lg-6">
        <div class="card-mepi">
            <div class="card-mepi-header">
                <h6><i class="bi bi-calculator me-2"></i>Dados da Folha</h6>
            </div>
            <div class="card-mepi-body">

                @if(session('error'))
                <div class="alert-mepi-error mb-3">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
                @endif

                <form method="POST" action="{{ route(auth()->user()->role.'.folha.store') }}" id="formFolha">
                    @csrf

                    {{-- Funcionário --}}
                    <div class="mb-3">
                        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Funcionário *</label>
                        <select name="funcionario_id" id="selFuncionario"
                                class="form-select @error('funcionario_id') is-invalid @enderror"
                                style="border-radius:8px;border-color:#ddd;font-size:.85rem;">
                            <option value="">Selecione...</option>
                            @foreach($funcionarios as $f)
                            <option value="{{ $f->id }}" data-salario="{{ $f->salario }}"
                                {{ old('funcionario_id') == $f->id ? 'selected':'' }}>
                                {{ $f->nome }} — R$ {{ number_format($f->salario,2,',','.') }}
                            </option>
                            @endforeach
                        </select>
                        @error('funcionario_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Competência --}}
                    <div class="mb-3">
                        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Competência *</label>
                        <input type="month" name="competencia" id="selCompetencia"
                               value="{{ old('competencia', $competencia) }}"
                               class="form-control @error('competencia') is-invalid @enderror"
                               style="border-radius:8px;border-color:#ddd;font-size:.85rem;">
                        @error('competencia') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small style="color:#888;font-size:.73rem;">
                            <i class="bi bi-info-circle me-1"></i>Férias aprovadas neste mês serão incluídas automaticamente.
                        </small>
                    </div>

                    {{-- ── ADICIONAIS DINÂMICOS ── --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label style="font-size:.85rem;font-weight:600;color:#444;margin:0;">
                                <i class="bi bi-plus-circle me-1" style="color:var(--verde);"></i>Adicionais
                            </label>
                            <button type="button" onclick="adicionarLinha('adicionais')"
                                    style="background:rgba(26,107,58,.1);color:var(--verde);border:none;border-radius:7px;padding:4px 12px;font-size:.78rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:5px;">
                                <i class="bi bi-plus-lg"></i> Adicionar
                            </button>
                        </div>
                        <div id="lista-adicionais"></div>
                        <p id="adicionais-vazio" style="font-size:.78rem;color:#ccc;margin:6px 0 0;display:none;">
                            Nenhum adicional. Clique em "+ Adicionar" para incluir.
                        </p>
                    </div>

                    {{-- ── DESCONTOS DINÂMICOS ── --}}
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label style="font-size:.85rem;font-weight:600;color:#444;margin:0;">
                                <i class="bi bi-dash-circle me-1" style="color:#dc2626;"></i>Descontos
                            </label>
                            <button type="button" onclick="adicionarLinha('descontos')"
                                    style="background:rgba(239,68,68,.08);color:#dc2626;border:none;border-radius:7px;padding:4px 12px;font-size:.78rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:5px;">
                                <i class="bi bi-plus-lg"></i> Adicionar
                            </button>
                        </div>
                        <div id="lista-descontos"></div>
                        <p id="descontos-vazio" style="font-size:.78rem;color:#ccc;margin:6px 0 0;display:none;">
                            Nenhum desconto. Clique em "+ Adicionar" para incluir.
                        </p>
                    </div>

                    {{-- Observação geral --}}
                    <div class="mb-4">
                        <label class="form-label" style="font-size:.83rem;font-weight:600;color:#444;">Observação Geral</label>
                        <textarea name="observacao" rows="2"
                                  class="form-control"
                                  placeholder="Anotações gerais desta folha..."
                                  style="border-radius:8px;border-color:#ddd;font-size:.85rem;resize:vertical;">{{ old('observacao') }}</textarea>
                    </div>

                    <button type="submit" class="btn-mepi w-100" style="justify-content:center;" id="btnSalvar" disabled>
                        <i class="bi bi-check-lg"></i> Confirmar e Salvar Folha
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ── PAINEL DE SIMULAÇÃO ────────────────────────────────── --}}
    <div class="col-lg-6">
        <div class="card-mepi" id="painelSimulacao" style="opacity:.4;transition:opacity .3s;">
            <div class="card-mepi-header">
                <h6><i class="bi bi-bar-chart-line me-2"></i>Simulação em Tempo Real</h6>
                <span id="badgeSimulacao" style="font-size:.72rem;color:#aaa;">Preencha os dados</span>
            </div>
            <div class="card-mepi-body">

                {{-- Skeleton --}}
                <div id="skeleton">
                    @for($i = 0; $i < 6; $i++)
                    <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f0f0e8;">
                        <div style="width:40%;height:12px;background:#f0f0e8;border-radius:4px;"></div>
                        <div style="width:25%;height:12px;background:#f0f0e8;border-radius:4px;"></div>
                    </div>
                    @endfor
                </div>

                {{-- Resultado --}}
                <div id="resultado" style="display:none;">

                    <div style="padding:12px;background:var(--off-white);border-radius:10px;margin-bottom:14px;">
                        <p style="font-size:.72rem;color:#888;margin:0;">Funcionário</p>
                        <p style="font-weight:700;font-size:.95rem;margin:2px 0 0;" id="rNome">—</p>
                        <p style="font-size:.78rem;color:#888;margin:0;" id="rCargo">—</p>
                    </div>

                    {{-- Linha fixa --}}
                    <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid #f0f0e8;">
                        <span style="font-size:.83rem;color:#666;">Salário Bruto</span>
                        <span id="rBruto" style="font-size:.85rem;font-weight:600;color:#333;">R$ —</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid #f0f0e8;">
                        <span style="font-size:.83rem;color:#666;"><span style="color:#dc2626;font-size:.72rem;margin-right:3px;">(-)</span>INSS</span>
                        <span id="rInss" style="font-size:.85rem;font-weight:600;color:#dc2626;">R$ —</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid #f0f0e8;">
                        <span style="font-size:.83rem;color:#666;"><span style="color:#dc2626;font-size:.72rem;margin-right:3px;">(-)</span>IRRF</span>
                        <span id="rIrrf" style="font-size:.85rem;font-weight:600;color:#dc2626;">R$ —</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid #f0f0e8;">
                        <span style="font-size:.83rem;color:#666;"><span style="color:var(--verde);font-size:.72rem;margin-right:3px;">(+)</span>Adicional Férias 1/3</span>
                        <span id="rFerias" style="font-size:.85rem;font-weight:600;color:var(--verde);">R$ —</span>
                    </div>
                    <div id="feriasInfo" style="display:none;font-size:.73rem;color:#aaa;padding:3px 0 5px 14px;">
                        <i class="bi bi-calendar-check me-1"></i><span id="txtFeriasInfo"></span>
                    </div>

                    {{-- Adicionais detalhados (preenchido pelo JS) --}}
                    <div id="rAdicionaisLista"></div>
                    <div id="rAdicionaisTotal" style="display:none;justify-content:space-between;padding:6px 0;border-bottom:1px solid #f0f0e8;">
                        <span style="font-size:.78rem;color:#888;font-style:italic;">Subtotal adicionais</span>
                        <span id="rAdicionaisTotalVal" style="font-size:.8rem;font-weight:700;color:var(--verde);"></span>
                    </div>

                    {{-- Descontos detalhados (preenchido pelo JS) --}}
                    <div id="rDescontosLista"></div>
                    <div id="rDescontosTotal" style="display:none;justify-content:space-between;padding:6px 0;border-bottom:1px solid #f0f0e8;">
                        <span style="font-size:.78rem;color:#888;font-style:italic;">Subtotal descontos</span>
                        <span id="rDescontosTotalVal" style="font-size:.8rem;font-weight:700;color:#dc2626;"></span>
                    </div>

                    {{-- Líquido --}}
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:14px;padding:16px;background:rgba(26,107,58,.07);border-radius:10px;border:1px solid rgba(26,107,58,.15);">
                        <span style="font-weight:700;color:var(--verde-escuro);font-size:.95rem;">Salário Líquido</span>
                        <span id="rLiquido" style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:var(--verde);">R$ —</span>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
let idxAdicional = 0;
let idxDesconto  = 0;

// ── Adicionar linha dinâmica ─────────────────────────────────
function adicionarLinha(tipo) {
    const isAd  = tipo === 'adicionais';
    const lista = document.getElementById('lista-' + tipo);
    const idx   = isAd ? idxAdicional++ : idxDesconto++;
    const pref  = isAd ? 'adicionais' : 'descontos';
    const cor   = isAd ? 'var(--verde)' : '#dc2626';
    const bg    = isAd ? 'rgba(26,107,58,.05)' : 'rgba(239,68,68,.04)';
    const border= isAd ? 'rgba(26,107,58,.15)' : 'rgba(239,68,68,.15)';
    const sinal = isAd ? '+' : '−';
    const ph    = isAd
        ? 'Ex: Hora extra, Bônus, Comissão, 13º...'
        : 'Ex: Falta, Vale transporte, Adiantamento...';

    const div = document.createElement('div');
    div.className  = 'linha-item';
    div.dataset.tipo = tipo;
    div.style.cssText = `background:${bg};border:1px solid ${border};border-radius:10px;padding:12px 12px 10px;margin-bottom:8px;position:relative;`;

    div.innerHTML = `
        <button type="button" onclick="removerLinha(this)"
                title="Remover"
                style="position:absolute;top:7px;right:8px;background:none;border:none;color:#ccc;cursor:pointer;font-size:.85rem;padding:2px 5px;line-height:1;border-radius:4px;"
                onmouseover="this.style.color='#dc2626'" onmouseout="this.style.color='#ccc'">
            <i class="bi bi-x-lg"></i>
        </button>

        <div class="row g-2">
            <div class="col-12">
                <label style="font-size:.73rem;font-weight:600;color:#666;display:block;margin-bottom:3px;">
                    Referência / Descrição *
                </label>
                <input type="text"
                       name="${pref}[${idx}][descricao]"
                       class="form-control form-control-sm linha-descricao"
                       placeholder="${ph}"
                       style="border-radius:7px;border-color:#ddd;font-size:.83rem;"
                       oninput="agendarSimulacao()">
            </div>

            <div class="col-5">
                <label style="font-size:.73rem;font-weight:600;color:#666;display:block;margin-bottom:3px;">
                    Valor (R$) *
                </label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text"
                          style="background:#f7f5ee;border-color:#ddd;font-size:.8rem;color:${cor};font-weight:700;padding:0 8px;">
                        ${sinal}
                    </span>
                    <input type="number"
                           name="${pref}[${idx}][valor]"
                           class="form-control linha-valor"
                           placeholder="0,00"
                           min="0" step="0.01"
                           style="border-radius:0 7px 7px 0;border-color:#ddd;font-size:.83rem;"
                           oninput="agendarSimulacao()">
                </div>
            </div>

            <div class="col-7">
                <label style="font-size:.73rem;font-weight:600;color:#666;display:block;margin-bottom:3px;">
                    Observação (opcional)
                </label>
                <input type="text"
                       name="${pref}[${idx}][obs]"
                       class="form-control form-control-sm"
                       placeholder="Detalhes, período, etc."
                       style="border-radius:7px;border-color:#ddd;font-size:.83rem;">
            </div>
        </div>
    `;

    lista.appendChild(div);
    atualizarVazio(tipo);
    div.querySelector('.linha-descricao').focus();
    agendarSimulacao();
}

// ── Remover linha ─────────────────────────────────────────────
function removerLinha(btn) {
    const div  = btn.closest('.linha-item');
    const tipo = div.dataset.tipo;
    div.remove();
    atualizarVazio(tipo);
    agendarSimulacao();
}

// ── Vazio toggle ──────────────────────────────────────────────
function atualizarVazio(tipo) {
    const lista = document.getElementById('lista-' + tipo);
    const vazio = document.getElementById(tipo + '-vazio');
    vazio.style.display = lista.children.length === 0 ? '' : 'none';
}

// ── Coleta itens de adicionais/descontos ──────────────────────
function coletarItens(tipo) {
    const linhas = document.querySelectorAll(`#lista-${tipo} .linha-item`);
    let total = 0;
    const itens = [];
    linhas.forEach(div => {
        const descricao = div.querySelector('.linha-descricao')?.value?.trim() || '';
        const valor     = parseFloat(div.querySelector('.linha-valor')?.value || 0);
        if (!isNaN(valor) && valor > 0) {
            total += valor;
            itens.push({ descricao, valor });
        }
    });
    return { total, itens };
}

// ── Simulação AJAX ────────────────────────────────────────────
const SIMULAR_URL = '{{ route(auth()->user()->role.".folha.simular") }}';
const CSRF        = document.querySelector('meta[name="csrf-token"]').content;
let timer = null;

function fmt(v) {
    return 'R$ ' + parseFloat(v || 0).toLocaleString('pt-BR', {
        minimumFractionDigits: 2, maximumFractionDigits: 2
    });
}

function linhaSimulacao(sinal, cor, label, valor) {
    return `
        <div style="display:flex;justify-content:space-between;padding:6px 0 6px 12px;border-bottom:1px solid #f0f0e8;">
            <span style="font-size:.78rem;color:#888;">
                <span style="color:${cor};margin-right:4px;font-size:.7rem;">(${sinal})</span>${label}
            </span>
            <span style="font-size:.8rem;font-weight:600;color:${cor};">${fmt(valor)}</span>
        </div>`;
}

async function simular() {
    const funcId = document.getElementById('selFuncionario').value;
    const compet = document.getElementById('selCompetencia').value;

    if (!funcId || !compet) {
        document.getElementById('painelSimulacao').style.opacity = '.4';
        document.getElementById('btnSalvar').disabled = true;
        return;
    }

    const { total: totalAd, itens: itensAd } = coletarItens('adicionais');
    const { total: totalDe, itens: itensDe } = coletarItens('descontos');

    document.getElementById('painelSimulacao').style.opacity = '1';
    document.getElementById('skeleton').style.display        = '';
    document.getElementById('resultado').style.display       = 'none';
    document.getElementById('badgeSimulacao').textContent    = 'Calculando...';

    try {
        const resp = await fetch(SIMULAR_URL, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body:    JSON.stringify({
                funcionario_id:    funcId,
                competencia:       compet,
                outros_adicionais: totalAd,
                outros_descontos:  totalDe,
            }),
        });

        const d = await resp.json();

        // Campos fixos
        document.getElementById('rNome').textContent   = d.funcionario;
        document.getElementById('rCargo').textContent  = d.cargo;
        document.getElementById('rBruto').textContent  = fmt(d.salario_bruto);
        document.getElementById('rInss').textContent   = fmt(d.desconto_inss);
        document.getElementById('rIrrf').textContent   = fmt(d.desconto_irrf);
        document.getElementById('rFerias').textContent = fmt(d.adicional_ferias);
        document.getElementById('rLiquido').textContent = fmt(d.salario_liquido);

        // Info férias
        const fDiv = document.getElementById('feriasInfo');
        if (d.dias_ferias > 0) {
            document.getElementById('txtFeriasInfo').textContent = d.dias_ferias + ' dia(s) de férias aprovadas neste mês';
            fDiv.style.display = '';
        } else {
            fDiv.style.display = 'none';
        }

        // Adicionais detalhados
        const rAd = document.getElementById('rAdicionaisLista');
        rAd.innerHTML = itensAd.map(i => linhaSimulacao('+', 'var(--verde)', i.descricao || 'Adicional', i.valor)).join('');

        const rAdTotal = document.getElementById('rAdicionaisTotal');
        if (itensAd.length > 1) {
            document.getElementById('rAdicionaisTotalVal').textContent = fmt(totalAd);
            rAdTotal.style.display = 'flex';
        } else {
            rAdTotal.style.display = 'none';
        }

        // Descontos detalhados
        const rDe = document.getElementById('rDescontosLista');
        rDe.innerHTML = itensDe.map(i => linhaSimulacao('-', '#dc2626', i.descricao || 'Desconto', i.valor)).join('');

        const rDeTotal = document.getElementById('rDescontosTotal');
        if (itensDe.length > 1) {
            document.getElementById('rDescontosTotalVal').textContent = fmt(totalDe);
            rDeTotal.style.display = 'flex';
        } else {
            rDeTotal.style.display = 'none';
        }

        document.getElementById('skeleton').style.display  = 'none';
        document.getElementById('resultado').style.display = '';
        document.getElementById('badgeSimulacao').textContent = 'Simulado ✓';
        document.getElementById('btnSalvar').disabled = false;

    } catch(e) {
        document.getElementById('badgeSimulacao').textContent = 'Erro na simulação';
    }
}

function agendarSimulacao() {
    clearTimeout(timer);
    timer = setTimeout(simular, 600);
}

document.getElementById('selFuncionario').addEventListener('change', agendarSimulacao);
document.getElementById('selCompetencia').addEventListener('change', agendarSimulacao);

// Estado inicial
atualizarVazio('adicionais');
atualizarVazio('descontos');
</script>
@endpush
