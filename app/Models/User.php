<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    public function goals()
    {
        return $this->hasMany(Goal::class);
    }

    public function bodyMeasurements()
    {
        return $this->hasMany(BodyMeasurement::class);
    }

    public function activitySummaries()
    {
        return $this->hasMany(ActivitySummary::class);
    }

    public function streaks()
    {
        return $this->hasMany(Streak::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function settings()
    {
        return $this->hasOne(Setting::class);
    }

    public function userModules()
    {
        return $this->hasMany(UserModule::class);
    }

    public function userConsents()
    {
        return $this->hasMany(UserConsent::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
