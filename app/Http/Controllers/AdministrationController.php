<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdministrationController extends Controller
{
    public function index($tab = 'stats')
    {
        $users = User::all();
        return Inertia::render('Administration/Index', [
            'users' => $users,
            'activeTab' => $tab,
        ]);
    }
}
