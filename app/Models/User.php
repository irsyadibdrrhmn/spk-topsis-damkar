<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nip',
        'jabatan',
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

    // Relationships
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function topsisResults()
    {
        return $this->hasMany(TopsisResult::class);
    }

    // Helper Methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPersonil()
    {
        return $this->role === 'personil';
    }

    public function isPimpinan()
    {
        return $this->role === 'pimpinan';
    }

    public function canManageData()
    {
        return $this->isAdmin();
    }

    public function canViewRecommendation()
    {
        return $this->isPimpinan() || $this->isAdmin();
    }

    public function canViewRanking()
    {
        return $this->isPimpinan() || $this->isAdmin();
    }
}