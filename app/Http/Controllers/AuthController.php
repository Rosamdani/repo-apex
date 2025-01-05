<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\Session;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.signin');
    }

    public function loginStore(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if (session()->has('sessions')) {
                session()->forget('sessions');
            }

            $userSession = Session::where('user_id', $user->id)->get();
            if ($userSession && $userSession->count() >= 2) {
                $request->session()->put('sessions', Auth::user()->sessions);
                Auth::logout();
                return redirect()->route('anotherDevice');
            } else {
                $agent = new Agent();
                $session = new Session();
                $session->user_id = $user->id;
                $session->browser = $agent->browser();
                $session->device = $agent->isMobile() ? 'Mobile' : ($agent->isTablet() ? 'Tablet' : 'Desktop');
                $session->os = $agent->platform();
                $session->os_version = $agent->version($agent->platform());
                $session->last_activity = now();
                $session->save();
                session()->put('session_id', $session->id);
            }


            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function showSessions()
    {
        $sessions = session('sessions');
        return view('auth.another-device', compact('sessions'));
    }


    public function register()
    {
        return view('auth.register');
    }


    public function registerStore(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('tryout.index')->with('success', 'Pendaftaran berhasil!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $session = Session::findOrFail(session('session_id'));

        $session->delete();

        $request->session()->flush();

        return redirect()->route('login');
    }

    public function logoutSession(Request $request)
    {
        $session = Session::findOrFail($request->session_id);

        $session->delete();

        return redirect()->route('login')->with('success', 'Perangkat lain berhasil di-logout.');
    }
}
