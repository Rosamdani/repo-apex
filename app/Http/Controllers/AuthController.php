<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\Tryouts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use TomatoPHP\FilamentLogger\Facades\FilamentLogger;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginStore(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email tidak valid',
            'password.required' => 'Password wajib diisi',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $deviceId = Str::uuid()->toString(); // Buat ID unik perangkat

            // Hitung perangkat aktif saat ini
            $activeDevices = $user->devices()->count();

            // Jika perangkat aktif sudah mencapai batas maksimal
            if ($activeDevices >= 5) {
                return redirect()->back()->with('error', 'Anda sudah login di 2 perangkat lain. Silakan logout dahulu sebelum login kembali.');
            }

            $user->devices()->create(['device_id' => $deviceId]);

            session(['device_id' => $deviceId]);

            Auth::login($user, $request->boolean('remember'));
            return redirect()->intended();
        }

        return redirect()->back()->with('error', 'Email atau password salah!');
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
        if ($request->user()) {
            $deviceId = session('device_id');
            $request->user()->devices()->where('device_id', $deviceId)->delete();
        }

        Auth::logout();
        $request->session()->flush();

        return redirect()->route('login');
    }
}
