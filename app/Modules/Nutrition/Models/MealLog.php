<?php

namespace App\Modules\Nutrition\Models;

use Illuminate\Database\Eloquent\Model;

class MealLog extends Model
{
    protected $table = 'meal_logs';

    protected $fillable = [
        'user_id',
        'food_id',
        'meal_type',
        'quantity_g',
        'calories',
        'protein',
        'carbs',
        'fat',
        'logged_at',
    ];

    protected $casts = [
        'logged_at' => 'date',
    ];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}
