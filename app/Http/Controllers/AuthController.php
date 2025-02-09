<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\Session;
use App\Models\User;
use App\Models\UserAcademy;
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
            'username' => 'required',
            'password' => 'required',
        ]);
        $remember = $request->has('remember');

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            if (session()->has(key: 'sessions')) {
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

        return back()->with('error', 'Username atau password salah!');
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
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telp' => $request->no_telp,
        ]);

        $user->assignRole('user');

        $userAcademy = new UserAcademy();
        $userAcademy->user_id = $user->id;
        $userAcademy->universitas = $request->universitas;
        $userAcademy->tahun_masuk = $request->tahun_masuk;
        $userAcademy->status_pendidikan = $request->status_pendidikan;
        $userAcademy->semester = $request->semester;
        $userAcademy->save();

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

        Auth::login($user);

        return redirect()->route('index')->with('success', 'Pendaftaran berhasil!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $session = Session::find(session('session_id'));

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
