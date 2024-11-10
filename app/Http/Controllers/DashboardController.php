<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_BASE_URL', "ini harus url");
    }

    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Dashboard Admin',
            'list' => ['Home', 'Dashboard']
        ];

        $page = (object) [
            'title' => 'Dashboard admin'
        ];

        $activeMenu = 'dashboard';

        return view('dashboard', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }
}
