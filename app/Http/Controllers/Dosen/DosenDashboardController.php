<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DosenDashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('layouts.wrapper', [
            'title'   => 'Dashboard',
            'content' => 'admin.dashboard.index',
        ]);
    }
}
