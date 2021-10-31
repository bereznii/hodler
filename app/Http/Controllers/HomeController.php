<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile()
    {
        $user = User::find(Auth::id());

        return view('profile', compact('user'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string',
        ]);

        $user = User::find(Auth::id());
        $user->name = $validated['name'];
        if ($password = request('password', false)) {
            $user->password = Hash::make($password);
        }

        if ($user->save()) {
            $request->session()->flash('notification', 'Профиль успешно обновлён!');
        } else {
            $request->session()->flash('notification', 'Произошла ошибка.');
        }

        return redirect()->route('profile');
    }
}
