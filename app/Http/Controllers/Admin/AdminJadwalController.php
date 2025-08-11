<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminJadwalController extends Controller
{
    public function index()
    {
        return view('layouts.wrapper', [
            'title'   => 'Prodi',
            'content' => 'admin.penjadwalan.jadwal.index',
        ]);
    }

}
