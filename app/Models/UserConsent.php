<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserConsent extends Model
{
    protected $fillable = [
        'user_id',
        'module',
        'data_type',
        'granted',
        'granted_at',
        'revoked_at',
    ];

    protected $casts = [
        'granted'    => 'boolean',
        'granted_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
