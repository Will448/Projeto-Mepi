<?php


namespace App\Observers;

use App\Models\Funcionario;
use App\Models\Auditoria;

class FuncionarioObserver
{
    public function created(Funcionario $f): void
    {
        Auditoria::registrar('created', 'Funcionario', $f->id,
            null, $f->toArray(),
            "Funcionário \"{$f->nome}\" cadastrado."
        );
    }

    public function updated(Funcionario $f): void
    {
        Auditoria::registrar('updated', 'Funcionario', $f->id,
            $f->getOriginal(), $f->getChanges(),
            "Funcionário \"{$f->nome}\" atualizado."
        );
    }

    public function deleted(Funcionario $f): void
    {
        Auditoria::registrar('deleted', 'Funcionario', $f->id,
            $f->toArray(), null,
            "Funcionário \"{$f->nome}\" inativado/excluído."
        );
    }
}
