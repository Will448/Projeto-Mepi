{{-- resources/views/components/breadcrumb.blade.php
     Uso: <x-breadcrumb :items="[['label'=>'Funcionários','route'=>'rh.funcionarios.index'],['label'=>'João Silva']]" />
--}}
@props(['items' => []])

@if(count($items) > 0)
<nav style="margin-bottom:20px;" aria-label="breadcrumb">
    <ol style="display:flex;align-items:center;gap:6px;list-style:none;padding:0;margin:0;flex-wrap:wrap;">
        <li>
            <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->role === 'rh' ? route('rh.dashboard') : route('funcionario.dashboard')) }}"
               style="font-size:.78rem;color:#888;text-decoration:none;display:flex;align-items:center;gap:4px;">
                <i class="bi bi-grid-1x2" style="font-size:.7rem;"></i> Início
            </a>
        </li>
        @foreach($items as $item)
        <li style="display:flex;align-items:center;gap:6px;">
            <i class="bi bi-chevron-right" style="font-size:.6rem;color:#ccc;"></i>
            @if(!$loop->last && isset($item['route']))
                <a href="{{ route($item['route'], $item['params'] ?? []) }}"
                   style="font-size:.78rem;color:#888;text-decoration:none;">
                    {{ $item['label'] }}
                </a>
            @else
                <span style="font-size:.78rem;color:var(--verde-escuro);font-weight:600;">{{ $item['label'] }}</span>
            @endif
        </li>
        @endforeach
    </ol>
</nav>
@endif
