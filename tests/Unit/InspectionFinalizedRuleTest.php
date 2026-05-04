<?php

namespace Tests\Unit;

use App\Models\Tenant\Inspection;
use App\Models\Tenant\InspectionAttachment;
use App\Models\Tenant\InspectionItem;
use App\Models\Tenant\InspectionRoom;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class InspectionFinalizedRuleTest extends TestCase
{
    public function test_vistoria_finalizada_nao_pode_ser_alterada(): void
    {
        $inspection = new Inspection;
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

    public function test_vistoria_finalizada_nao_pode_ser_excluida(): void
    {
        $inspection = new Inspection;
        $inspection->setRawAttributes([
            'id' => 1,
            'title' => 'Vistoria original',
            'status' => 'finalizada',
        ], true);
        $inspection->exists = true;

        $this->expectException(ValidationException::class);

        $inspection->delete();
    }

    public function test_comodo_de_vistoria_finalizada_nao_pode_ser_alterado(): void
    {
        $room = new InspectionRoom;
        $room->setRawAttributes([
            'id' => 1,
            'inspection_id' => 1,
            'name' => 'Sala',
        ], true);
        $room->exists = true;
        $room->setRelation('inspection', $this->vistoriaFinalizada());

        $room->name = 'Sala alterada';

        $this->expectException(ValidationException::class);

        $room->save();
    }

    public function test_comodo_de_vistoria_finalizada_nao_pode_ser_excluido(): void
    {
        $room = new InspectionRoom;
        $room->setRawAttributes([
            'id' => 1,
            'inspection_id' => 1,
            'name' => 'Sala',
        ], true);
        $room->exists = true;
        $room->setRelation('inspection', $this->vistoriaFinalizada());

        $this->expectException(ValidationException::class);

        $room->delete();
    }

    public function test_item_de_vistoria_finalizada_nao_pode_ser_alterado(): void
    {
        $item = new InspectionItem;
        $item->setRawAttributes([
            'id' => 1,
            'inspection_room_id' => 1,
            'name' => 'Parede',
        ], true);
        $item->exists = true;
        $item->setRelation('inspectionRoom', $this->comodoDeVistoriaFinalizada());

        $item->name = 'Parede alterada';

        $this->expectException(ValidationException::class);

        $item->save();
    }

    public function test_item_de_vistoria_finalizada_nao_pode_ser_excluido(): void
    {
        $item = new InspectionItem;
        $item->setRawAttributes([
            'id' => 1,
            'inspection_room_id' => 1,
            'name' => 'Parede',
        ], true);
        $item->exists = true;
        $item->setRelation('inspectionRoom', $this->comodoDeVistoriaFinalizada());

        $this->expectException(ValidationException::class);

        $item->delete();
    }

    public function test_anexo_de_vistoria_finalizada_nao_pode_ser_alterado(): void
    {
        $attachment = new InspectionAttachment;
        $attachment->setRawAttributes([
            'id' => 1,
            'inspection_id' => 1,
            'type' => 'foto',
            'disk' => 'local',
            'path' => 'inspection-attachments/foto.jpg',
        ], true);
        $attachment->exists = true;
        $attachment->setRelation('inspection', $this->vistoriaFinalizada());

        $attachment->caption = 'Legenda alterada';

        $this->expectException(ValidationException::class);

        $attachment->save();
    }

    public function test_anexo_de_vistoria_finalizada_nao_pode_ser_excluido(): void
    {
        $attachment = new InspectionAttachment;
        $attachment->setRawAttributes([
            'id' => 1,
            'inspection_id' => 1,
            'type' => 'foto',
            'disk' => 'local',
            'path' => 'inspection-attachments/foto.jpg',
        ], true);
        $attachment->exists = true;
        $attachment->setRelation('inspection', $this->vistoriaFinalizada());

        $this->expectException(ValidationException::class);

        $attachment->delete();
    }

    private function vistoriaFinalizada(): Inspection
    {
        $inspection = new Inspection;
        $inspection->setRawAttributes([
            'id' => 1,
            'title' => 'Vistoria original',
            'status' => 'finalizada',
        ], true);
        $inspection->exists = true;

        return $inspection;
    }

    private function comodoDeVistoriaFinalizada(): InspectionRoom
    {
        $room = new InspectionRoom;
        $room->setRawAttributes([
            'id' => 1,
            'inspection_id' => 1,
            'name' => 'Sala',
        ], true);
        $room->exists = true;
        $room->setRelation('inspection', $this->vistoriaFinalizada());

        return $room;
    }
}
