<?php

namespace App\Livewire;

use App\Models\PaketTryout;
use App\Models\Tryouts;
use App\Models\UserTryouts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TryoutsTab extends Component
{
    public $tab;
    public $tryouts;
    public $activeTab;
    public $paketTryout;

    public function mount($tab)
    {
        $this->tab = $tab;
        $this->activeTab = request()->query('tab', 'not_started');
    }

    public function render()
    {

        $userId = Auth::id();

        $this->tryouts = Tryouts::with('batch') // Memuat relasi questions
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('tryout_has_pakets')
                    ->whereColumn('tryouts.id', '=', 'tryout_has_pakets.tryout_id');
            })
            ->leftJoin('user_tryouts', function ($join) use ($userId) {
                $join->on('tryouts.id', '=', 'user_tryouts.tryout_id')
                    ->where('user_tryouts.user_id', '=', $userId);
            })
            ->select([
                'tryouts.id as tryout_id',
                'tryouts.nama',
                'tryouts.tanggal',
                'tryouts.image',
                'tryouts.batch_id',
                'tryouts.waktu',
                'tryouts.status as status_tryout',
                'user_tryouts.status',
                'user_tryouts.nilai',
                DB::raw('(SELECT COUNT(*) FROM soal_tryouts WHERE soal_tryouts.tryout_id = tryouts.id) as question_count') // Hitung jumlah questions
            ])
            ->get()
            ->filter(function ($tryout) {
                // Filter berdasarkan tab status
                if ($this->tab === 'not_started') {
                    return $tryout->status === null || $tryout->status === 'not_started';
                } elseif ($this->tab === 'started') {
                    return $tryout->status === 'paused' || $tryout->status === 'started';
                } elseif ($this->tab === 'finished') {
                    return $tryout->status === 'finished';
                }
            });

        $this->paketTryout = PaketTryout::with(['tryouts.userTryouts' => function ($query) use ($userId) {
            $query->where('user_id', $userId); // Ambil hanya user_tryouts milik user tertentu
        }])
            ->where('status', 'active')
            ->get();

        $this->paketTryout = $this->paketTryout->filter(function ($paket) use ($userId) {
            if ($paket->is_need_confirm) {
                $access = \App\Models\UserAccessPaket::where('user_id', $userId)
                    ->where('paket_id', $paket->id)
                    ->first();
                return $access && $access->status === 'accepted';
            } else {
                return true;
            }
        });

        dd($this->paketTryout);

        // Filter berdasarkan tab
        $this->paketTryout = $this->paketTryout->filter(function ($paket) {
            $userTryoutStatuses = collect($paket->tryouts)->flatMap(function ($tryout) {
                return collect($tryout->userTryouts)->pluck('status.value');
            })->unique();

            if ($this->tab === 'finished') {
                // Semua tryout dalam paket harus selesai
                return $userTryoutStatuses->every(fn($status) => $status === 'finished');
            } elseif ($this->tab === 'started') {
                // Salah satu tryout dalam paket sedang dikerjakan
                return $userTryoutStatuses->contains('started') || $userTryoutStatuses->contains('paused');
            } else {
                // Semua tryout dalam paket belum dimulai
                return $userTryoutStatuses->every(fn($status) => $status === null || $status === 'not_started');
            }
        });


        return view('livewire.tryouts-tab');
    }
}
