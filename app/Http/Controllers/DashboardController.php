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
        if (session('role') != 'dosen') {
            $response = Http::withAuthToken()->get("{$this->apiUrl}/api/user/homepage-web");
        } else {
            $response = Http::withAuthToken()->withQueryParameters(['uid' => session('user_id')])->get("{$this->apiUrl}/api/user/statistic");
        }

        $data = $response->json('data');

        $page = (object) [
            'title' => 'Dashboard admin'
        ];

        $activeMenu = 'dashboard';

        return view('dashboard', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'data' => $data]);
    }
}
