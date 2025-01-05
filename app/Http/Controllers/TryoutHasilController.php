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
                ->orderBy('nilai', 'DESC')
                ->pluck('nilai')
                ->search($userTryout->nilai) + 1;

            if (!$userTryout) {
                abort(404);
            }

            $status_lulus = $userTryout->nilai >= 60 ? 'Lulus' : 'Belum Lulus';
            return view('result', compact('userTryout', 'status_lulus', 'totalUser', 'userTryoutRank'));
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan disini ' . $e->getMessage()]);
        }
    }

    public function perangkingan($id)
    {
        $userTryout = UserTryouts::with(['tryout', 'user'])->where('tryout_id', $id)->where('user_id', Auth::user()->id)->first();
        $totalUser = UserTryouts::where('tryout_id', $id)->where('status', 'finished')->count();
        $allUserRank = UserTryouts::with('user')->where('tryout_id', $id)
            ->where('status', 'finished')
            ->orderBy('nilai', 'DESC') // Urutkan berdasarkan nilai tertinggi
            ->limit(10)
            ->get();

        $userTryoutRank = UserTryouts::where('tryout_id', $id)
            ->orderBy('nilai', 'DESC')
            ->pluck('nilai')
            ->search($userTryout->nilai) + 1;
        $status_lulus = $userTryout->nilai >= 60 ? 'Lulus' : 'Gagal';
        // Hitung ranking
        $allUserRank->transform(function ($user, $index) {
            $user->rank = $index + 1; // Ranking mulai dari 1
            $user->status_lulus = $user->nilai >= 60 ? 'Lulus' : 'Gagal';
            return $user;
        });



        return view('tryouts.perangkingan', compact('userTryout', 'allUserRank', 'totalUser', 'userTryoutRank', 'status_lulus'));
    }

    public function pembahasan($id)
    {
        $userTryout = UserTryouts::where('tryout_id', $id)->where('user_id', Auth::id())->first();
        if (!$userTryout || $userTryout->status->value != 'finished') {
            abort(404);
        }

        return view('tryouts.pembahasan', compact('userTryout'));
    }

    public function ranking($id)
    {
        return view('tryouts.ranking', compact('id'));
    }
}
