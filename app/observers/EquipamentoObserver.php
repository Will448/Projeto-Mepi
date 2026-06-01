<?php

namespace App\Observers;

use App\Models\Equipamento;
use App\Models\Auditoria;

class EquipamentoObserver
{
    public function created(Equipamento $e): void
    {
        Auditoria::registrar('created', 'Equipamento', $e->id,
            null, $e->toArray(),
            "Equipamento \"{$e->nome}\" cadastrado."
        );
    }

    public function updated(Equipamento $e): void
    {
        Auditoria::registrar('updated', 'Equipamento', $e->id,
            $e->getOriginal(), $e->getChanges(),
            "Equipamento \"{$e->nome}\" atualizado."
        );
    }

    public function deleted(Equipamento $e): void
    {
        Auditoria::registrar('deleted', 'Equipamento', $e->id,
            $e->toArray(), null,
            "Equipamento \"{$e->nome}\" excluído."
        );
    }
}
