<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'name',
        'document',
        'email',
        'phone',
        'notes',
    ];

    public function inspectedLocations(): HasMany
    {
        return $this->hasMany(InspectedLocation::class);
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(Inspection::class);
    }
}
