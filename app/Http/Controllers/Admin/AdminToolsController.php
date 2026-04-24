<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AdminToolsController extends Controller
{
    public function notifications(): View
    {
        return view('admin.tools.notifications');
    }

    public function terminal(): View
    {
        return view('admin.tools.terminal');
    }

    public function settings(): View
    {
        return view('admin.tools.settings');
    }
}
