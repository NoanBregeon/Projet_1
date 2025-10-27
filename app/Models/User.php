<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRolesAndAbilities;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Accessor pour le nom complet
    public function getNameAttribute(): string
    {
        $first = $this->first_name ?? '';
        $last  = $this->last_name ?? '';
        return trim($first . ' ' . $last);
    }

    // Méthodes compatibles avec Bouncer
    public function isAdmin(): bool
    {
        return $this->isA('admin');
    }

    public function isUser(): bool
    {
        return $this->isA('user') || !$this->isA('admin');
    }
}
