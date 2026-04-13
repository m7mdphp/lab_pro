<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchTranslation extends Model
{
    protected $fillable = [
        'branch_id', 'locale', 'name', 'address', 'area', 'city', 'working_hours',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
