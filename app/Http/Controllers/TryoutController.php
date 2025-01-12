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
        $total_tryout = Tryouts::where('status', 'active')->count();
        $total_user_tryout = UserTryouts::where('user_id', Auth::id())->count();
        $start = Carbon::now()->startOfWeek();
        $end = Carbon::now()->endOfWeek();
        $tryout_baru_minggu_ini = Tryouts::whereBetween('created_at', [$start, $end])->where('status', 'active')->count();
        return view('index', compact('batches', 'total_tryout', 'total_user_tryout', 'tryout_baru_minggu_ini'));
    }

    public function katalog()
    {
        return view('tryouts.katalog');
    }

    public function katalaogDetail($id)
    {
        $tryout = Tryouts::find($id);
        return view('tryouts.detail-tryout', compact('id', 'tryout'));
    }

    public function show($id)
    {
        try {
            $tryout = Tryouts::where('status', 'active')->where('id', $id)->firstOrFail();
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
}
