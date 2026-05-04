<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

class Inspection extends Model
{
    protected $fillable = [
        'client_id',
        'inspected_location_id',
        'created_by',
        'title',
        'status',
        'performed_at',
        'summary',
        'observations',
    ];

    protected function casts(): array
    {
        return [
            'performed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (Inspection $inspection): void {
            if ($inspection->getOriginal('status') === 'finalizada') {
                throw ValidationException::withMessages([
                    'status' => 'Vistorias finalizadas não podem ser alteradas.',
                ]);
            }
        });

        static::deleting(function (Inspection $inspection): void {
            if ($inspection->getOriginal('status') === 'finalizada') {
                throw ValidationException::withMessages([
                    'status' => 'Vistorias finalizadas não podem ser excluídas.',
                ]);
            }
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function inspectedLocation(): BelongsTo
    {
        return $this->belongsTo(InspectedLocation::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(InspectionRoom::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(InspectionAttachment::class);
    }
}
