<?php

// app/Http/Controllers/ManajemenController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManajemenController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manajemen Admin',
            'list' => ['Home', 'Manajemen']
        ];

        $page = (object) [
            'title' => 'Manajemen admin'
        ];

        
        $activeMenu = 'manajemen';

        return view('manajemen.manajemen', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }
}


