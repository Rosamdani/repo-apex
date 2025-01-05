<?php

namespace App\Http\Middleware;

use App\Models\Session;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (session('session_id') && !Session::where('id', session('session_id'))->exists()) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Perangkat anda telah dikeluarkan dari perangkat lain!');
        }

        if (session('session_id')) {
            $session = Session::findOrFail(session('session_id'));
            $session->last_activity = now();
            $session->save();
        }
        return $next($request);
    }
}
