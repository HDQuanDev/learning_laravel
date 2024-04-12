<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class WebController extends Controller
{
    public function login_page()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('login');
    }
    public function dashboard()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return view('user.main', ['user' => $user]);
        }
        return redirect()->route('login_page');
    }
    public function manager_user()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->admin != 'true') {
                return redirect()->route('dashboard');
            }
            return view('user.manager.user', ['user' => $user]);
        }
    }
    public function register()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('register');
    }
}
