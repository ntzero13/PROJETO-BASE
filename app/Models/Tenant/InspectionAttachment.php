<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Concerns\ImpedeAlteracoesEmVistoriaFinalizada;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionAttachment extends Model
{
    use ImpedeAlteracoesEmVistoriaFinalizada;

    protected $fillable = [
        'inspection_id',
        'inspection_room_id',
        'inspection_item_id',
        'type',
        'disk',
        'path',
        'caption',
    ];

    protected static function booted(): void
    {
        static::saving(function (InspectionAttachment $attachment): void {
            $attachment->protegerVistoriaFinalizada();
        });

        static::deleting(function (InspectionAttachment $attachment): void {
            $attachment->protegerVistoriaFinalizada();
        });
    }

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    public function inspectionRoom(): BelongsTo
    {
        return $this->belongsTo(InspectionRoom::class);
    }

    public function inspectionItem(): BelongsTo
    {
        return $this->belongsTo(InspectionItem::class);
    }

    private function protegerVistoriaFinalizada(): void
    {
        if ($this->relationLoaded('inspection')) {
            $this->impedirAlteracaoSeVistoriaFinalizada($this->inspection);

            return;
        }

        $inspectionIds = array_unique(array_filter([
            $this->getOriginal('inspection_id'),
            $this->inspection_id,
        ]));

        foreach ($inspectionIds as $inspectionId) {
            $this->impedirAlteracaoSeVistoriaFinalizada(Inspection::query()->find($inspectionId));
        }
    }
}
