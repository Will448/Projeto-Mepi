@extends('layout.app')
@section('title', 'Alterar Senha')
@section('page-title', 'Alterar Senha')

@section('content')

<div class="card-mepi" style="max-width:480px;">
    <div class="card-mepi-header">
        <h6><i class="bi bi-key me-2"></i>Alterar Minha Senha</h6>
    </div>
    <div class="card-mepi-body">

        @if($errors->any())
        <div class="alert-mepi-error mb-3">
            <i class="bi bi-exclamation-circle me-2"></i>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $e)
                    <li style="font-size:.83rem;">{{ $e }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('senha.update') }}">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">
                    Senha atual *
                </label>
                <input type="password" name="senha_atual"
                       class="form-control @error('senha_atual') is-invalid @enderror"
                       placeholder="Digite sua senha atual"
                       style="border-radius:8px;border-color:#ddd;font-size:.88rem;">
                @error('senha_atual')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">
                    Nova senha *
                </label>
                <input type="password" name="nova_senha" id="novaSenha"
                       class="form-control @error('nova_senha') is-invalid @enderror"
                       placeholder="Mínimo 6 caracteres"
                       style="border-radius:8px;border-color:#ddd;font-size:.88rem;"
                       oninput="verificarForca(this.value)">
                @error('nova_senha')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                {{-- Indicador de força da senha --}}
                <div style="margin-top:8px;">
                    <div style="height:4px;background:#f0f0e8;border-radius:4px;overflow:hidden;">
                        <div id="barraSenha" style="height:100%;width:0;border-radius:4px;transition:all .3s;"></div>
                    </div>
                    <span id="textoForca" style="font-size:.72rem;color:#aaa;"></span>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">
                    Confirmar nova senha *
                </label>
                <input type="password" name="nova_senha_confirmation"
                       class="form-control"
                       placeholder="Repita a nova senha"
                       style="border-radius:8px;border-color:#ddd;font-size:.88rem;">
            </div>

            <button type="submit" class="btn-mepi w-100" style="justify-content:center;">
                <i class="bi bi-check-lg"></i> Salvar Nova Senha
            </button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function verificarForca(senha) {
    const barra  = document.getElementById('barraSenha');
    const texto  = document.getElementById('textoForca');
    let forca = 0;

    if (senha.length >= 6)  forca++;
    if (senha.length >= 10) forca++;
    if (/[A-Z]/.test(senha)) forca++;
    if (/[0-9]/.test(senha)) forca++;
    if (/[^A-Za-z0-9]/.test(senha)) forca++;

    const niveis = [
        { pct: '20%',  cor: '#ef4444', txt: 'Muito fraca'  },
        { pct: '40%',  cor: '#f59e0b', txt: 'Fraca'        },
        { pct: '60%',  cor: '#eab308', txt: 'Média'        },
        { pct: '80%',  cor: '#22c55e', txt: 'Forte'        },
        { pct: '100%', cor: '#1a6b3a', txt: 'Muito forte'  },
    ];

    const nivel = niveis[Math.min(forca, 4)];
    barra.style.width      = nivel.pct;
    barra.style.background = nivel.cor;
    texto.textContent      = senha.length > 0 ? nivel.txt : '';
    texto.style.color      = nivel.cor;
}
</script>
@endpush
