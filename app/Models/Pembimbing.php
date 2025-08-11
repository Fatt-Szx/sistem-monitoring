<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembimbing extends Model
{
    protected $guarded = [];

    protected $table = 'pembimbings';

    public function mahasiswas(){ return $this->hasMany(Mahasiswa::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function prodi() { return $this->belongsTo(Prodi::class); }
}
