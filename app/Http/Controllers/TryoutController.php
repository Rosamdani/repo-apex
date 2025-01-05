<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\BatchTryouts;
use App\Models\SoalTryout;
use App\Models\Tryouts;
use App\Models\User;
use App\Models\UserAnswer;
use App\Models\UserTime;
use App\Models\UserTryouts;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TryoutController extends Controller
{
    public function index()
    {
        $batches = BatchTryouts::all();
        $total_tryout = Tryouts::count();
        $total_user_tryout = UserTryouts::where('user_id', Auth::id())->count();
        $start = Carbon::now()->startOfWeek();
        $end = Carbon::now()->endOfWeek();
        $tryout_baru_minggu_ini = Tryouts::whereBetween('created_at', [$start, $end])->count();
        return view('index', compact('batches', 'total_tryout', 'total_user_tryout', 'tryout_baru_minggu_ini'));
    }


    public function show($id)
    {
        try {
            $tryout = Tryouts::findOrFail($id);
            UserTryouts::updateOrCreate(
                [
                    'tryout_id' => $tryout->id,
                    'user_id' => Auth::user()->id
                ],
                [
                    'status' => 'started'
                ]
            );

            return view('tryouts.ujian', compact('tryout'));
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan']);
        }
    }

    public function getPembahasan(Request $request)
    {
        $userTryout = UserTryouts::where('user_id', Auth::user()->id)->where('tryout_id', $request->id_tryout)->where('status', 'finished')->first();
        if ($userTryout && $userTryout->status == 'finished') {
            try {
                $soal = SoalTryout::with(['userAnswer' => function ($query) {
                    $query->where('user_id', Auth::id());
                }])
                    ->select(['id', 'nomor', 'soal', 'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'pilihan_e', 'jawaban'])
                    ->where('tryout_id', $request->id_tryout)
                    ->orderBy('nomor', 'asc')
                    ->get();

                $soal->each(function ($s) {
                    $userAnswer = $s->userAnswer ? $s->userAnswer->where('soal_id', $s->id)->first() : null;


                    if ($userAnswer) {
                        if ($userAnswer->status === 'ragu') {
                            $s->status_jawaban = 'ragu-ragu';
                        } else if ($userAnswer->status === 'dijawab') {
                            $s->status_jawaban = ($s->jawaban !== $userAnswer->jawaban) ? 'salah' : 'benar';
                        }
                    } else {
                        $s->status_jawaban = 'tidak dijawab';
                    }
                });


                return response()->json(['status' => 'success', 'message' => 'Data berhasil diambil', 'data' => $soal]);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan ' . $e->getMessage()]);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Tryout belum selesai']);
        }
    }
}
