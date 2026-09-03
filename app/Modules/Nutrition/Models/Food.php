<?php

namespace App\Modules\Nutrition\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $table = 'foods';

    protected $fillable = [
        'user_id',
        'name',
        'brand',
        'calories_per_100g',
        'protein_per_100g',
        'carbs_per_100g',
        'fat_per_100g',
        'fiber_per_100g',
        'sugar_per_100g',
        'is_custom',
    ];

    protected $casts = [
        'is_custom' => 'boolean',
    ];
}
