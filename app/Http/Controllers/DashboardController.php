<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the SuperAdmin dashboard.
     */
    public function superAdmin()
    {
        return view('dashboard.superadmin');
    }

    /**
     * Display the Admin dashboard.
     */
    public function admin()
    {
        return view('dashboard.admin');
    }

    /**
     * Display the Manager dashboard.
     */
    public function manager()
    {
        return view('dashboard.manager');
    }

    /**
     * Display the User dashboard.
     */
    public function user()
    {
        return view('dashboard.user');
    }
}
