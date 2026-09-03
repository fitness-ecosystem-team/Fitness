<?php

namespace App\Modules\Nutrition\Models;

use Illuminate\Database\Eloquent\Model;

class WaterLog extends Model
{
    protected $table = 'water_logs';

    protected $fillable = [
        'user_id',
        'amount_ml',
        'logged_at',
    ];

    protected $casts = [
        'logged_at' => 'date',
    ];
}
