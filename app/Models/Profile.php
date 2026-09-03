<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'date_of_birth',
        'gender',
        'height_cm',
        'weight_kg',
        'fitness_goal',
        'activity_level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
