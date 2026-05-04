<?php

namespace App\Models\Tenant\Concerns;

use App\Models\Tenant\Inspection;
use Illuminate\Validation\ValidationException;

trait ImpedeAlteracoesEmVistoriaFinalizada
{
    protected function impedirAlteracaoSeVistoriaFinalizada(?Inspection $vistoria): void
    {
        if ($vistoria?->status !== 'finalizada') {
            return;
        }

        throw ValidationException::withMessages([
            'inspection_id' => 'Vistorias finalizadas não podem ter dados relacionados alterados.',
        ]);
    }
}
