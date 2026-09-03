<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'user_id',
        'language',
        'weight_unit',
        'distance_unit',
        'theme',
        'notifications_enabled',
        'workout_reminders',
        'nutrition_reminders',
        'sleep_reminders',
        'streak_alerts',
    ];

    protected $casts = [
        'notifications_enabled' => 'boolean',
        'workout_reminders'     => 'boolean',
        'nutrition_reminders'   => 'boolean',
        'sleep_reminders'       => 'boolean',
        'streak_alerts'         => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
