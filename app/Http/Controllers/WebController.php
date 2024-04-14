<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Note;
use App\Models\User;
use Parsedown;

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

    public function view_note($id)
    {
        $note = Note::get_note_by_id($id);
        if (!$note) {
            return redirect()->route('dashboard');
        } else {
            $Parsedown = new Parsedown();
            $note->content = $Parsedown->text($note->content);
            $user = $note->user_id;
            $get_user = User::get_user_by_id($user);
            Note::update_view($id);
            return view('viewnote', ['user' => $get_user, 'note' => $note]);
        }
    }
}
