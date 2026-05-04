<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InspectionRoom extends Model
{
    protected $fillable = [
        'inspection_id',
        'name',
        'sort_order',
        'observations',
    ];

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
}
