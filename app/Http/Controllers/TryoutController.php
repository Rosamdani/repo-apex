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
        return view('tryouts.home', compact('batches'));
    }

    public function mytryout()
    {
        $batches = BatchTryouts::all();
        return view('tryouts.my-tryout', compact('batches'));
    }

    public function getTryouts(Request $request)
    {
        try {
            if ($request->batch == 'semua') {
                $batch = new BatchTryouts();
                $tryouts = $batch->getAllTryoutsWithUserProgress(Auth::id());
                $response = [
                    'tryouts' => $tryouts,
                ];
            } else {
                $batch = BatchTryouts::findOrFail($request->batch);
                $tryouts = $batch->getTryoutsWithUserProgress(Auth::id(), 3);
                $response = [
                    'tryouts' => count($tryouts) > 1 ? $tryouts->toArray() : [$tryouts->toArray()],
                    'batch_end_date' => $batch->end_date
                ];
            }


            return ApiResponse::success('Sukses!', $response);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Batch tidak ditemukan!');
        } catch (\Exception $e) {
            return ApiResponse::error('Gagal mendapatkan data tryout! ' . $e->getMessage());
        }
    }

    public function getNotStartedTryouts(Request $request)
    {
        try {
            $batch = BatchTryouts::find($request->batch);

            $tryouts = Tryouts::select(['tryouts.id', 'tryouts.nama', 'tryouts.tanggal', 'tryouts.image'])
                ->leftJoin('user_tryouts', function ($join) {
                    $join->on('tryouts.id', '=', 'user_tryouts.tryout_id')
                        ->where('user_tryouts.user_id', '=', Auth::id());
                })
                ->where('tryouts.batch_id', $request->batch)
                ->get()
                ->map(function ($tryout) use ($batch) {
                    // Cari user_tryout untuk tryout tertentu
                    $user_tryout = UserTryouts::where('user_id', Auth::id())
                        ->where('tryout_id', $tryout->id)
                        ->first();

                    if (!$user_tryout || $user_tryout->status === 'not_started') {
                        // Jika belum ada user_tryout atau statusnya not_started, tambahkan informasi tambahan
                        $tryout_data = [
                            'id' => $tryout->id,
                            'nama' => $tryout->nama,
                            'tanggal' => $tryout->tanggal,
                            'image' => $tryout->image,
                            'jumlah_soal' => SoalTryout::where('tryout_id', $tryout->id)->count(),
                            'status_pengerjaan' => $user_tryout ? $user_tryout->status : 'not_started',
                            'nama_batch' => $batch->nama,
                        ];

                        // Ambil nama bidang unik untuk tryout ini
                        $tryout_data['bidang'] = SoalTryout::where('tryout_id', $tryout->id)
                            ->join('bidang_tryouts', 'soal_tryouts.bidang_id', '=', 'bidang_tryouts.id')
                            ->distinct()
                            ->pluck('bidang_tryouts.nama')
                            ->toArray();

                        return $tryout_data;
                    }

                    return null; // Abaikan tryout yang tidak memenuhi kondisi
                })
                ->filter(function ($tryout) {
                    return $tryout !== null;
                })
                ->values()
                ->toArray();

            return ApiResponse::success('Sukses!', $tryouts);
        } catch (\Exception $e) {
            return ApiResponse::error('Gagal mendapatkan data tryout! ');
        }
    }

    public function getUserTryoutData(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'batch_id' => 'required',
        ]);

        $batchId = $validated['batch_id'];
        $userId = Auth::id();

        // Ambil data UserTryouts yang sesuai
        $data = UserTryouts::join('tryouts', 'user_tryouts.tryout_id', '=', 'tryouts.id')
            ->join('batch_tryouts', 'tryouts.batch_id', '=', 'batch_tryouts.id')
            ->where('user_tryouts.user_id', $userId)
            ->where('tryouts.batch_id', $batchId)
            ->where('user_tryouts.status', 'finished') // Hanya yang statusnya "finished"
            ->select('tryouts.nama as tryout_name', 'user_tryouts.nilai')
            ->get();


        // Format data untuk chart
        $chartData = [
            'labels' => $data->pluck('tryout_name'),
            'values' => $data->pluck('nilai'),
        ];

        return response()->json($chartData);
    }

    public function getLeaderboard(Request $request)
    {
        try {
            $batch = BatchTryouts::find($request->batch_id);

            if (!$batch) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Batch tidak ditemukan.'
                ]);
            }

            // Ambil tryouts yang terkait dengan batch
            $leaderboard = Tryouts::select('tryouts.id', 'tryouts.nama as tryout_nama')
                ->where('tryouts.batch_id', $batch->id)
                ->get()
                ->map(function ($tryout) {
                    $topUser = UserTryouts::where('tryout_id', $tryout->id)
                        ->where('status', 'finished')
                        ->orderByDesc('nilai')
                        ->first();

                    if ($topUser) {
                        $user = User::find($topUser->user_id);

                        return [
                            'tryout_nama' => $tryout->tryout_nama,
                            'user_nama' => $user->name,
                            'nilai' => $topUser->nilai
                        ];
                    }

                    return null;
                })
                ->filter();

            return response()->json([
                'status' => 'success',
                'message' => 'Leaderboard berhasil diambil.',
                'data' => $leaderboard
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
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

            return view('tryouts.tryout-tes', compact('tryout'));
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan']);
        }
    }
    public function hasil($id)
    {
        try {
            $tryout = Tryouts::findOrFail($id);
            return view('tryouts.hasil', compact('tryout'));
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan']);
        }
    }


    public function getQuestions(Request $request)
    {
        try {
            $soal = SoalTryout::with(['userAnswer' => function ($query) {
                $query->where('user_id', auth()->id()); // Filter berdasarkan user_id pengguna yang login
            }])
                ->select(['id', 'nomor', 'soal', 'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'pilihan_e'])
                ->where('tryout_id', $request->id_tryout)
                ->orderBy('nomor', 'asc')
                ->get();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil diambil', 'data' => $soal]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan ' . $e->getMessage()]);
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


    public function saveAnswer(Request $request)
    {
        try {
            UserAnswer::updateOrCreate(
                ['user_id' => Auth::user()->id, 'soal_id' => $request->id],
                ['jawaban' => $request->pilihan, 'status' => $request->status]
            );


            return response()->json(['message' => 'Answer saved successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Failed to save answer. ' . $th->getMessage()], 500);
        }
    }

    public function pausedTime(Request $request)
    {
        try {
            $request->validate([
                'id_tryout' => 'required|exists:tryouts,id',
            ]);
            $user_time = UserTime::updateOrCreate([
                'user_id' => Auth::user()->id,
                'tryout_id' => $request->id_tryout,
            ], [
                'sisa_waktu' => $request->sisa_waktu,
            ]);

            return response()->json(['status' => 'success', 'message' => 'Sisa waktu updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan ' . $e->getMessage()]);
        }
    }

    public function getTimeLeft(Request $request)
    {
        try {
            $user_time = UserTime::where('user_id', Auth::user()->id)
                ->where('tryout_id', $request->id_tryout)
                ->first();
            if ($user_time) {
                return response()->json(['status' => 'success', 'data' => $user_time->sisa_waktu]);
            } else {
                $tryout = Tryouts::findOrFail($request->id_tryout);
                $user_time = UserTime::create([
                    'user_id' => Auth::user()->id,
                    'tryout_id' => $request->id_tryout,
                    'sisa_waktu' => $tryout->waktu,
                ]);
                return response()->json(['status' => 'success', 'data' => $user_time->sisa_waktu]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan ']);
        }
    }

    public function saveEndTime(Request $request)
    {
        try {
            $request->validate([
                'id_tryout' => 'required|exists:tryouts,id',
                'waktu_habis' => 'required',
            ]);
            $waktu_habis = Carbon::parse($request->waktu_habis)->format('Y-m-d H:i:s');


            UserTime::updateOrCreate([
                'user_id' => Auth::user()->id,
                'tryout_id' => $request->id_tryout,
            ], [
                'waktu_habis' => $request->waktu_habis,
            ]);

            return response()->json(['status' => 'success', 'message' => 'Waktu habis updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan ' . $e->getMessage()]);
        }
    }

    public function getEndTime(Request $request)
    {
        $user_time = UserTime::where('user_id', Auth::user()->id)
            ->where('tryout_id', $request->id_tryout)
            ->first();
        if ($user_time) {
            return response()->json(['status' => 'success', 'data' => $user_time->waktu_habis]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'User time not found']);
        }
    }

    public function saveTimeLeft(Request $request)
    {
        $userTryout = UserTime::where('user_id', Auth::user()->id)
            ->where('tryout_id', $request->id_tryout)
            ->first();

        if (!$userTryout) {
            return response()->json(['status' => 'error', 'message' => 'Tryout tidak ditemukan']);
        }

        $userTryout->sisa_waktu = $request->sisa_waktu;
        $userTryout->save();

        return response()->json(['status' => 'success', 'message' => 'Waktu tersimpan']);
    }

    public function finishTryout(Request $request)
    {
        try {
            $user_time = UserTime::where('user_id', Auth::user()->id)
                ->where('tryout_id', $request->id_tryout)
                ->first();
            if ($user_time) {
                $user_time->delete();
                $user_tryout = UserTryouts::where('user_id', Auth::user()->id)
                    ->where('tryout_id', $request->id_tryout)
                    ->update(['status' => 'finished', 'nilai' => $this->calculateScore($request->id_tryout)]);
                return response()->json(['status' => 'success', 'message' => 'Tryout finished successfully', 'data' => $user_tryout]);
            } else {
                return response()->json(['status' => 'error', 'message' => 'User time not found']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan ' . $e->getMessage()]);
        }
    }

    public function calculateScore($tryoutId)
    {
        try {
            // Ambil data tryout berdasarkan ID
            $tryout = Tryouts::with('questions')->findOrFail($tryoutId);

            // Ambil daftar soal dalam tryout
            $questions = $tryout->questions;
            $totalQuestions = $questions->count();

            // Ambil jawaban pengguna untuk soal dalam tryout
            $userAnswers = UserAnswer::whereIn('soal_id', $questions->pluck('id'))
                ->where('user_id', Auth::id()) // Menggunakan Auth::id() untuk user yang sedang login
                ->get();

            // Hitung jawaban benar
            $correctAnswers = 0;

            foreach ($questions as $question) {
                $userAnswer = $userAnswers->firstWhere('soal_id', $question->id);

                if ($userAnswer && $userAnswer->jawaban === $question->jawaban) {
                    $correctAnswers++;
                }
            }

            // Hitung skor sebagai persentase
            $score = number_format($totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0, 2, '.', ',');

            return  $score;
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
