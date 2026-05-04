<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InspectionItem extends Model
{
    protected $fillable = [
        'inspection_room_id',
        'name',
        'condition_status',
        'observations',
    ];

    public function inspectionRoom(): BelongsTo
    {
        return $this->belongsTo(InspectionRoom::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(InspectionAttachment::class);
    }
}
