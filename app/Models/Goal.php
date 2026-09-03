<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'user_id',
        'category',
        'title',
        'target_value',
        'unit',
        'deadline',
        'status',
    ];

    protected $casts = [
        'deadline'     => 'date',
        'target_value' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
