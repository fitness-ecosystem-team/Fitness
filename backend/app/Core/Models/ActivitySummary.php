<?php

namespace App\Core\Models;

use Illuminate\Database\Eloquent\Model;

class ActivitySummary extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'steps',
        'calories_burned',
        'active_minutes',
        'distance_km',
        'water_ml',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
