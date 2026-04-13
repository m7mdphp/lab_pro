<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'branch_id', 'package_id',
        'test_name', 'preferred_date', 'notes', 'status',
    ];

    protected $casts = ['preferred_date' => 'date'];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
