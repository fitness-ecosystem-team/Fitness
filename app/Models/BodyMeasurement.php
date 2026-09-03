<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BodyMeasurement extends Model
{
    protected $fillable = [
        'user_id',
        'measured_at',
        'weight_kg',
        'body_fat_percent',
        'muscle_mass_kg',
        'waist_cm',
        'chest_cm',
        'hips_cm',
        'arms_cm',
        'thighs_cm',
        'neck_cm',
    ];

    protected $casts = [
        'measured_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
