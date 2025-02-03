<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'image_url',
        'email',
        'no_telp',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function answers()
    {
        return $this->hasMany(UserAnswer::class);
    }

    public function userTryouts()
    {
        return $this->hasMany(UserTryouts::class);
    }

    public function userAcademy()
    {
        return $this->hasOne(UserAcademy::class, 'user_id');
    }

    public function userAccessTryouts()
    {
        return $this->hasMany(UserAccessTryouts::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole(['admin']);
    }
}
