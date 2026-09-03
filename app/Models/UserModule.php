<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModule extends Model
{
    protected $fillable = [
        'user_id',
        'module_name',
        'is_active',
        'activated_at',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'activated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
