<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guard_name = 'web'; 
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

        // Relasi ke profil
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'user_id');
    }

    public function pembimbing()
    {
        return $this->hasOne(Pembimbing::class, 'user_id');
    }

    // Helper: ambil profil aktif berdasar role
    public function profile()
    {
        if ($this->hasRole('mahasiswa'))  return $this->mahasiswa;
        if ($this->hasRole('dosen'))      return $this->pembimbing;
        return null;
    }

    public function displayName(): string
    {
        if ($this->hasRole('dosen') && $this->pembimbing) {
            $d = $this->pembimbing;
            return trim(($d->gelar_depan ? $d->gelar_depan.' ' : '') . $d->nama . ($d->gelar_belakang ? ', '.$d->gelar_belakang : ''));
        }
        return $this->name;
    }
}
