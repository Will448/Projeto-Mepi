{{-- resources/views/components/flash.blade.php
     Uso: <x-flash /> em qualquer view (já está no layout, mas pode usar avulso também)
--}}

@if(session('success'))
<div class="mepi-alert mepi-alert-success" role="alert" id="flash-msg">
    <div class="mepi-alert-icon"><i class="bi bi-check-circle-fill"></i></div>
    <div class="mepi-alert-body">{{ session('success') }}</div>
    <button type="button" class="mepi-alert-close" onclick="this.closest('.mepi-alert').remove()">
        <i class="bi bi-x-lg"></i>
    </button>
</div>
@endif

@if(session('error'))
<div class="mepi-alert mepi-alert-error" role="alert" id="flash-msg">
    <div class="mepi-alert-icon"><i class="bi bi-exclamation-circle-fill"></i></div>
    <div class="mepi-alert-body">{{ session('error') }}</div>
    <button type="button" class="mepi-alert-close" onclick="this.closest('.mepi-alert').remove()">
        <i class="bi bi-x-lg"></i>
    </button>
</div>
@endif

@if(session('warning'))
<div class="mepi-alert mepi-alert-warning" role="alert" id="flash-msg">
    <div class="mepi-alert-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
    <div class="mepi-alert-body">{{ session('warning') }}</div>
    <button type="button" class="mepi-alert-close" onclick="this.closest('.mepi-alert').remove()">
        <i class="bi bi-x-lg"></i>
    </button>
</div>
@endif

@if(session('info'))
<div class="mepi-alert mepi-alert-info" role="alert" id="flash-msg">
    <div class="mepi-alert-icon"><i class="bi bi-info-circle-fill"></i></div>
    <div class="mepi-alert-body">{{ session('info') }}</div>
    <button type="button" class="mepi-alert-close" onclick="this.closest('.mepi-alert').remove()">
        <i class="bi bi-x-lg"></i>
    </button>
</div>
@endif

@if($errors->any() && !isset($suppressErrorBag))
<div class="mepi-alert mepi-alert-error" role="alert">
    <div class="mepi-alert-icon"><i class="bi bi-exclamation-circle-fill"></i></div>
    <div class="mepi-alert-body">
        <strong>Corrija os erros antes de continuar:</strong>
        <ul class="mb-0 mt-1 ps-3">
            @foreach($errors->all() as $error)
                <li style="font-size:.83rem;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    <button type="button" class="mepi-alert-close" onclick="this.closest('.mepi-alert').remove()">
        <i class="bi bi-x-lg"></i>
    </button>
</div>
@endif

<style>
.mepi-alert {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 16px;
    border-radius: 10px;
    margin-bottom: 20px;
    animation: slideDown .25s ease;
}
@keyframes slideDown {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}
.mepi-alert-success { background:rgba(26,107,58,.08);  border:1px solid rgba(26,107,58,.2);  }
.mepi-alert-error   { background:rgba(239,68,68,.07);  border:1px solid rgba(239,68,68,.2);  }
.mepi-alert-warning { background:rgba(245,196,0,.1);   border:1px solid rgba(245,196,0,.3);  }
.mepi-alert-info    { background:rgba(59,130,246,.08); border:1px solid rgba(59,130,246,.2); }

.mepi-alert-icon { font-size:1.1rem; flex-shrink:0; margin-top:1px; }
.mepi-alert-success .mepi-alert-icon { color:var(--verde); }
.mepi-alert-error   .mepi-alert-icon { color:#dc2626; }
.mepi-alert-warning .mepi-alert-icon { color:#b08c00; }
.mepi-alert-info    .mepi-alert-icon { color:#3b82f6; }

.mepi-alert-body { flex:1; font-size:.87rem; line-height:1.5; color:#333; }
.mepi-alert-close {
    background: none; border: none; cursor: pointer;
    color: #aaa; padding: 0 4px; font-size:.85rem; flex-shrink:0;
    transition: color .15s;
}
.mepi-alert-close:hover { color:#555; }
</style>

<script>
// Auto-dismiss após 5 segundos
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.mepi-alert').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity .4s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        }, 5000);
    });
});
</script>
