<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Magang extends Model
{
    protected $guarded = [];

    protected $table = 'magangs';

    // app/Models/Magang.php
    public function mahasiswas(){ return $this->hasMany(Mahasiswa::class); }
    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];
}

