<?php
// ============================================================
// ARQUIVO 2: app/Observers/FeriasObserver.php
// ============================================================
namespace App\Observers;

use App\Models\Ferias;
use App\Models\Auditoria;

class FeriasObserver
{
    public function created(Ferias $f): void
    {
        Auditoria::registrar('created', 'Ferias', $f->id,
            null, $f->toArray(),
            "Férias solicitadas por funcionário #{$f->funcionario_id}."
        );
    }

    public function updated(Ferias $f): void
    {
        $status = $f->status;
        Auditoria::registrar('updated', 'Ferias', $f->id,
            $f->getOriginal(), $f->getChanges(),
            "Férias #{$f->id} atualizadas — status: {$status}."
        );
    }
}
