<?php

namespace App\Http\Controllers\Proveedor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('proveedor.dashboard');
    }
}