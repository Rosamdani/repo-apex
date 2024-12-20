<?php

namespace App\Http\Controllers;

use App\Models\Testimoni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimoniController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'tryout_id' => 'required|exists:tryouts,id',
                'testimoni' => 'required|string|max:1000',
                'rating' => 'required|integer|min:1|max:5',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Harap lengkapi data testimoni.');
        }

        try {
            Testimoni::create([
                'user_id' => Auth::id(),
                'tryout_id' => $request->input('tryout_id'),
                'nilai' => $request->input('rating'),
                'testimoni' => $request->input('testimoni'),
            ]);

            return redirect()->back()->with('success', 'Testimoni berhasil dikirim.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
