<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageTranslation extends Model
{
    protected $fillable = [
        'package_id', 'locale', 'name', 'description',
        'short_description', 'meta_title', 'meta_description',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
