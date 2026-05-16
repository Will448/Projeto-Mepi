{{-- resources/views/components/empty-state.blade.php
     Uso: <x-empty-state icon="bi-people" title="Nenhum funcionário" description="Cadastre o primeiro." :route="route('rh.funcionarios.create')" cta="Novo Funcionário" />
--}}
@props([
    'icon'        => 'bi-inbox',
    'title'       => 'Nenhum resultado encontrado',
    'description' => '',
    'route'       => null,
    'cta'         => null,
])

<div style="text-align:center;padding:56px 24px;">
    <div style="width:64px;height:64px;border-radius:16px;background:#f0f0e8;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
        <i class="bi {{ $icon }}" style="font-size:1.8rem;color:#ccc;"></i>
    </div>
    <h6 style="font-family:'Syne',sans-serif;font-weight:700;color:#555;margin-bottom:6px;">{{ $title }}</h6>
    @if($description)
        <p style="font-size:.85rem;color:#aaa;margin-bottom:20px;">{{ $description }}</p>
    @endif
    @if($route && $cta)
        <a href="{{ $route }}" class="btn-mepi" style="display:inline-flex;">
            <i class="bi bi-plus-lg"></i> {{ $cta }}
        </a>
    @endif
</div>
