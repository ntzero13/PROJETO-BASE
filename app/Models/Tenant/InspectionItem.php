<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Concerns\ImpedeAlteracoesEmVistoriaFinalizada;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InspectionItem extends Model
{
    use ImpedeAlteracoesEmVistoriaFinalizada;

    protected $fillable = [
        'inspection_room_id',
        'name',
        'condition_status',
        'observations',
    ];

    protected static function booted(): void
    {
        static::saving(function (InspectionItem $item): void {
            $item->protegerVistoriaFinalizada();
        });

        static::deleting(function (InspectionItem $item): void {
            $item->protegerVistoriaFinalizada();
        });
    }

    public function inspectionRoom(): BelongsTo
    {
        return $this->belongsTo(InspectionRoom::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(InspectionAttachment::class);
    }

    private function protegerVistoriaFinalizada(): void
    {
        if ($this->relationLoaded('inspectionRoom')) {
            $this->impedirAlteracaoSeVistoriaFinalizada($this->inspectionRoom?->inspection);

            return;
        }

        $roomIds = array_unique(array_filter([
            $this->getOriginal('inspection_room_id'),
            $this->inspection_room_id,
        ]));

        foreach ($roomIds as $roomId) {
            $room = InspectionRoom::query()->with('inspection')->find($roomId);

            $this->impedirAlteracaoSeVistoriaFinalizada($room?->inspection);
        }
    }
}
