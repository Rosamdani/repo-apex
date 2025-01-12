<?php

namespace App\Http\Controllers;

use App\Models\SoalTryout;
use App\Models\UserTryouts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TryoutHasilController extends Controller
{
    public function index($id)
    {
        try {
            $userTryout = UserTryouts::where('tryout_id', $id)->where('user_id', Auth::user()->id)->first();
            if ($userTryout->status->value != 'finished') {
                return redirect()->back();
            }

            $totalUser = UserTryouts::where('tryout_id', $id)->where('status', 'finished')->count();
            $userTryoutRank = UserTryouts::where('tryout_id', $id)
                ->orderBy('nilai', 'desc')
                ->orderBy('created_at', 'asc') // Urutan tambahan untuk tie
                ->pluck('nilai')
                ->search($userTryout->nilai) + 1;

            if (!$userTryout) {
                abort(404);
            }

            $nilai_minimal = setting('soal.minimal_nilai') ?? 60;
            $status_lulus = $userTryout->nilai >= $nilai_minimal ? 'Lulus' : 'Belum Lulus';
            return view('result', compact('userTryout', 'status_lulus', 'totalUser', 'userTryoutRank'));
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan disini ' . $e->getMessage()]);
        }
    }

    public function ranking($id)
    {
        return view('tryouts.ranking', compact('id'));
    }
}
