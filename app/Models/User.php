<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'unit_opd',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function uploads() {
        return $this->hasMany(UploadRetribusi::class);
    }

    public function realisasi() {
        return $this->hasMany(RealisasiRetribusi::class);
    }

    public function isAdmin(): bool {
        return $this->role === 'admin';
    }
}