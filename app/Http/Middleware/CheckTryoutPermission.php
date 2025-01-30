<?php

namespace App\Http\Middleware;

use App\Models\Tryouts;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTryoutPermission
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
        if (!$tryout) {
            return redirect()->route('katalog.index')->with('error', 'Tryout tidak ditemukan.');
        } else {
            if ($tryout->harga && $tryout->harga > 0) {
                $userAccessTryout = $user->userAccessTryouts()->where('tryout_id', $tryoutId)->first();

                if (!$userAccessTryout) {
                    return redirect()->route('katalog.detail', $tryoutId)->with('error', 'Anda belum memiliki izin untuk mengakses tryout ini. Konfirmasi admin untuk mendapatkan akses.');
                } elseif ($userAccessTryout->status === 'denied') {
                    return redirect()->route('katalog.detail', $tryoutId)->with('error', 'Permintaan izin untuk mengakses tryout ini ditolak.');
                } elseif ($userAccessTryout->status === 'requested') {
                    return redirect()->route('katalog.detail', $tryoutId)->with('error', 'Permintaan izin untuk mengakses tryout ini sedang dalam proses.');
                }
            }
        }


        return $next($request);
    }
}
