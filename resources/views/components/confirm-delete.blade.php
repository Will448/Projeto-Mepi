{{-- resources/views/components/confirm-delete.blade.php
     Uso:
       <x-confirm-delete
           :action="route('admin.funcionarios.destroy', $f)"
           label="Inativar João"
           message="Tem certeza? O funcionário será inativado." />
--}}
@props([
    'action',
    'method'  => 'DELETE',
    'label'   => 'Excluir',
    'message' => 'Esta ação não pode ser desfeita. Deseja continuar?',
    'icon'    => 'bi-trash3',
    'btnClass'=> '',
])

@php $modalId = 'modal-' . uniqid(); @endphp

<button type="button"
        onclick="document.getElementById('{{ $modalId }}').style.display='flex'"
        class="btn btn-sm {{ $btnClass }}"
        style="background:rgba(239,68,68,.1);color:#dc2626;border:none;border-radius:7px;padding:5px 10px;font-size:.78rem;">
    <i class="bi {{ $icon }}"></i>
</button>

{{-- Overlay --}}
<div id="{{ $modalId }}"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;align-items:center;justify-content:center;"
     onclick="if(event.target===this)this.style.display='none'">
    <div style="background:#fff;border-radius:16px;padding:28px 32px;max-width:400px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.2);">
        <div style="width:48px;height:48px;border-radius:50%;background:rgba(239,68,68,.1);display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
            <i class="bi bi-exclamation-triangle-fill" style="color:#dc2626;font-size:1.3rem;"></i>
        </div>
        <h6 style="font-family:'Syne',sans-serif;font-weight:800;color:#1a1a1a;margin-bottom:8px;">Confirmar ação</h6>
        <p style="font-size:.87rem;color:#666;margin-bottom:24px;line-height:1.5;">{{ $message }}</p>
        <div style="display:flex;gap:10px;">
            <form method="POST" action="{{ $action }}" style="flex:1;">
                @csrf
                @if($method !== 'POST') @method($method) @endif
                <button type="submit"
                        style="width:100%;background:#dc2626;color:#fff;border:none;border-radius:8px;padding:10px;font-size:.87rem;font-weight:600;cursor:pointer;">
                    <i class="bi {{ $icon }} me-1"></i> {{ $label }}
                </button>
            </form>
            <button type="button"
                    onclick="document.getElementById('{{ $modalId }}').style.display='none'"
                    style="flex:1;background:#f0f0e8;color:#555;border:none;border-radius:8px;padding:10px;font-size:.87rem;font-weight:600;cursor:pointer;">
                Cancelar
            </button>
        </div>
    </div>
</div>
