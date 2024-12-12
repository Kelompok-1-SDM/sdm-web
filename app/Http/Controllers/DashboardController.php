<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

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
            $responseDos = Http::withAuthToken()->get("{$this->apiUrl}/api/user", ['role' => 'dosen']);
            $response = Http::withAuthToken()->get("{$this->apiUrl}/api/user/homepage-web");
        } else {
            $responseDos = Http::withAuthToken()->get("{$this->apiUrl}/api/user/homepage-mobile", ['uid' => '']);
            $response = Http::withAuthToken()->withQueryParameters(['uid' => session('user_id')])->get("{$this->apiUrl}/api/user/statistic");
        }

        $data = $response->json('data');
        $dosen = $responseDos->json('data');

        $page = (object) [
            'title' => 'Dashboard admin'
        ];

        $activeMenu = 'dashboard';

        return view('dashboard', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'data' => $data, 'dosen' => $dosen]);
    }
}
