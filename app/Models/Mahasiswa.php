<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $guarded = [];

    protected $table = 'mahasiswas';

    public function user(){ return $this->belongsTo(User::class); }
    public function prodi(){ return $this->belongsTo(Prodi::class,'prodi_id'); }
    public function magang(){ return $this->belongsTo(Magang::class,'magang_id'); }
    public function pembimbing(){ return $this->belongsTo(Pembimbing::class,'pembimbing_id'); }
    
}
