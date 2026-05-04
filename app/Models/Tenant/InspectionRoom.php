<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Concerns\ImpedeAlteracoesEmVistoriaFinalizada;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InspectionRoom extends Model
{
    use ImpedeAlteracoesEmVistoriaFinalizada;

    protected $fillable = [
        'inspection_id',
        'name',
        'sort_order',
        'observations',
    ];

    protected static function booted(): void
    {
        static::saving(function (InspectionRoom $room): void {
            $room->protegerVistoriaFinalizada();
        });

        static::deleting(function (InspectionRoom $room): void {
            $room->protegerVistoriaFinalizada();
        });
    }

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InspectionItem::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(InspectionAttachment::class);
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
