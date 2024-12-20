<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PDFController extends Controller
{
    public function downloadReportBidang(Request $request)
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
                $bidang[] = (object) [
                    'bidang_id' => $item->bidang_id,
                    'kategori' => $item->kategori,
                    'total_soal' => $item->total_soal,
                    'benar' => $item->benar,
                    'salah' => $item->salah,
                    'tidak_dikerjakan' => $item->tidak_dikerjakan,
                ];
            }

            foreach ($kompetensiData as $item) {
                $kompetensi[] = (object) [
                    'kompetensi_id' => $item->kompetensi_id,
                    'kategori' => $item->kompetensi,
                    'total_soal' => $item->total_soal,
                    'benar' => $item->benar,
                    'salah' => $item->salah,
                    'tidak_dikerjakan' => $item->tidak_dikerjakan,
                ];
            }


            $pdf = PDF::loadView('tryouts.pdf.report-bidang', ['bidang' => $bidang, 'kompetensi' => $kompetensi]);

            return $pdf->download('report-bidang.pdf');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }
}
