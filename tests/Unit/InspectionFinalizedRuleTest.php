<?php

namespace Tests\Unit;

use App\Models\Tenant\Inspection;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class InspectionFinalizedRuleTest extends TestCase
{
    public function test_finalized_inspection_cannot_be_updated(): void
    {
        $inspection = new Inspection();
        $inspection->setRawAttributes([
            'id' => 1,
            'title' => 'Vistoria original',
            'status' => 'finalizada',
        ], true);
        $inspection->exists = true;

        $inspection->title = 'Tentativa de alteracao';

        $this->expectException(ValidationException::class);

        $inspection->save();
    }

    public function test_finalized_inspection_cannot_be_deleted(): void
    {
        $inspection = new Inspection();
        $inspection->setRawAttributes([
            'id' => 1,
            'title' => 'Vistoria original',
            'status' => 'finalizada',
        ], true);
        $inspection->exists = true;

        $this->expectException(ValidationException::class);

        $inspection->delete();
    }
}
