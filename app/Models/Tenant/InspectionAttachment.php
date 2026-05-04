<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionAttachment extends Model
{
    protected $fillable = [
        'inspection_id',
        'inspection_room_id',
        'inspection_item_id',
        'type',
        'disk',
        'path',
        'caption',
    ];

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
}
