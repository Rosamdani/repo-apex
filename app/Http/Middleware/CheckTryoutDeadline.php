<?php

namespace App\Http\Middleware;

use App\Models\Tryouts;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTryoutDeadline
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tryoutId = $request->route('id');
        $user = $request->user();
        $tryout = Tryouts::where('id', $tryoutId)->first();
        if ($tryout->end_time && $tryout->end_time < Carbon::now()) {
            return redirect()->back()->with('error', 'Tryout ini sudah berakhir');
        }

        return $next($request);
    }
}
