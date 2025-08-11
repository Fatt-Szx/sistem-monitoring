<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MahasiswaDashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('layouts.wrapper', [
            'title'   => 'Dashboard',
            'content' => 'admin.dashboard.index',
        ]);
    }
}
