<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom tabel yang boleh diisi secara massal
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nip_nik',
        'role',
    ];

    /**
     * Kolom yang disembunyikan saat data diubah ke JSON/Array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tipe data casts bawaan
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}