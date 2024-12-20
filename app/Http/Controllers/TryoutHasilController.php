<?php

namespace App\Http\Controllers;

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
            if ($userTryout->status != 'finished') {
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
            return view('tryouts.hasil', compact('userTryout', 'status_lulus', 'totalUser', 'userTryoutRank'));
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan ']);
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
        if ($userTryout->status != 'finished') {
            abort(404);
        }

        return view('tryouts.pembahasan', compact('userTryout'));
    }

    public function getKompetensiAnswer(Request $request)
    {
        try {
            $request->validate([
                'tryout_id' => 'required|exists:tryouts,id',
            ]);

            $tryoutId = $request->input('tryout_id');
            $userId = Auth::user()->id;

            $bidangData = DB::table('soal_tryouts')
                ->select(
                    'soal_tryouts.bidang_id',
                    'bidang_tryouts.nama as kategori', // Nama kategori (Bidang)
                    DB::raw('COUNT(soal_tryouts.id) as total_soal'), // Total soal
                    DB::raw('COUNT(CASE WHEN user_answers.jawaban = soal_tryouts.jawaban THEN 1 END) as benar'), // Jumlah soal yang dijawab benar
                    DB::raw('COUNT(CASE WHEN user_answers.jawaban != soal_tryouts.jawaban AND user_answers.jawaban IS NOT NULL THEN 1 END) as salah'), // Jumlah soal yang dijawab salah
                    DB::raw('COUNT(CASE WHEN user_answers.jawaban IS NULL THEN 1 END) as tidak_dikerjakan') // Jumlah soal yang tidak dikerjakan
                )
                ->leftJoin('bidang_tryouts', 'soal_tryouts.bidang_id', '=', 'bidang_tryouts.id')
                ->leftJoin('user_answers', function ($join) use ($userId) {
                    $join->on('soal_tryouts.id', '=', 'user_answers.soal_id')
                        ->where('user_answers.user_id', '=', $userId);
                })
                ->where('soal_tryouts.tryout_id', $tryoutId)
                ->groupBy('soal_tryouts.bidang_id', 'bidang_tryouts.nama')
                ->get();

            $kompetensiData = DB::table('soal_tryouts')
                ->select(
                    'soal_tryouts.kompetensi_id',
                    'kompetensi_tryouts.nama as kompetensi', // Nama kompetensi
                    DB::raw('COUNT(soal_tryouts.id) as total_soal'), // Total soal
                    DB::raw('COUNT(CASE WHEN user_answers.jawaban = soal_tryouts.jawaban THEN 1 END) as benar'), // Jumlah soal yang dijawab benar
                    DB::raw('COUNT(CASE WHEN user_answers.jawaban != soal_tryouts.jawaban AND user_answers.jawaban IS NOT NULL THEN 1 END) as salah'), // Jumlah soal yang dijawab salah
                    DB::raw('COUNT(CASE WHEN user_answers.jawaban IS NULL THEN 1 END) as tidak_dikerjakan') // Jumlah soal yang tidak dikerjakan
                )
                ->leftJoin('kompetensi_tryouts', 'soal_tryouts.kompetensi_id', '=', 'kompetensi_tryouts.id')
                ->leftJoin('user_answers', function ($join) use ($userId) {
                    $join->on('soal_tryouts.id', '=', 'user_answers.soal_id')
                        ->where('user_answers.user_id', '=', $userId);
                })
                ->where('soal_tryouts.tryout_id', $tryoutId)
                ->groupBy('soal_tryouts.kompetensi_id', 'kompetensi_tryouts.nama')
                ->get();

            // Format data untuk hasil yang diinginkan
            $bidang = [];
            $kompetensi = [];

            foreach ($bidangData as $item) {
                $bidang[] = [
                    'bidang_id' => $item->bidang_id,
                    'kategori' => $item->kategori,
                    'total_soal' => $item->total_soal,
                    'benar' => $item->benar,
                    'salah' => $item->salah,
                    'tidak_dikerjakan' => $item->tidak_dikerjakan,
                ];
            }

            foreach ($kompetensiData as $item) {
                $kompetensi[] = [
                    'kompetensi_id' => $item->kompetensi_id,
                    'kategori' => $item->kompetensi,
                    'total_soal' => $item->total_soal,
                    'benar' => $item->benar,
                    'salah' => $item->salah,
                    'tidak_dikerjakan' => $item->tidak_dikerjakan,
                ];
            }

            // Format response
            $formattedData = [
                'status' => 'success',
                'message' => 'Data berhasil diambil',
                'data' => [
                    'bidang' => $bidang,
                    'kompetensi' => $kompetensi,
                ]
            ];

            return response()->json($formattedData);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }

    public function persentaseBidang(Request $request)
    {
        try {
            $request->validate([
                'tryout_id' => 'required|exists:tryouts,id',
            ]);

            $tryoutId = $request->input('tryout_id');
            $userId = Auth::user()->id;

            $bidangData = DB::table('soal_tryouts')
                ->select(
                    'soal_tryouts.bidang_id',
                    'bidang_tryouts.nama as kategori',
                    DB::raw('COUNT(soal_tryouts.id) as total_soal'),
                    DB::raw('COUNT(CASE WHEN user_answers.jawaban = soal_tryouts.jawaban THEN 1 END) as benar'),
                    DB::raw('COUNT(CASE WHEN user_answers.jawaban != soal_tryouts.jawaban AND user_answers.jawaban IS NOT NULL THEN 1 END) as salah'),
                    DB::raw('COUNT(CASE WHEN user_answers.jawaban IS NULL THEN 1 END) as tidak_dikerjakan')
                )
                ->leftJoin('bidang_tryouts', 'soal_tryouts.bidang_id', '=', 'bidang_tryouts.id')
                ->leftJoin('user_answers', function ($join) use ($userId) {
                    $join->on('soal_tryouts.id', '=', 'user_answers.soal_id')
                        ->where('user_answers.user_id', '=', $userId);
                })
                ->where('soal_tryouts.tryout_id', $tryoutId)
                ->groupBy('soal_tryouts.bidang_id', 'bidang_tryouts.nama')
                ->get();

            $bidang = [];
            foreach ($bidangData as $item) {
                $totalSoal = $item->total_soal;
                $benar = $item->benar;

                $persenBenar = $totalSoal ? ($benar / $totalSoal) * 100 : 0;

                $bidang[] = [
                    'bidang_id' => $item->bidang_id,
                    'kategori' => $item->kategori,
                    'total_soal' => $totalSoal,
                    'benar' => $benar,
                    'persen_benar' => round($persenBenar, 2)
                ];
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diambil',
                'data' => [
                    'bidang' => $bidang,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }
}
