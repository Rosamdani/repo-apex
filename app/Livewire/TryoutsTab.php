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

    public function mount($tab)
    {
        $this->tab = $tab;
        $this->activeTab = request()->query('tab', 'not_started');
    }

    public function render()
    {

        $userId = Auth::id();

        $this->tryouts = Tryouts::with('batch')
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
                'tryouts.id as tryout_id',    // Kolom tryouts.id dengan alias
                'tryouts.nama',
                'tryouts.tanggal',
                'tryouts.image',
                'tryouts.batch_id',
                'tryouts.waktu',
                'tryouts.status as status_tryout',
                'user_tryouts.status',
                'user_tryouts.nilai',
                'tryouts.is_need_confirm',
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

        $this->tryouts = $this->tryouts->filter(function ($tryout) use ($userId) {
            if ($tryout->is_need_confirm) {
                $access = \App\Models\UserAccessTryouts::where('user_id', $userId)
                    ->where('tryout_id', $tryout->tryout_id)
                    ->first();
                return $access && $access->status === 'accepted';
            } else {
                return true;
            }
        });

        $this->tryouts = $this->tryouts->map(function ($tryout) {
            return [
                'tryout_id' => $tryout->tryout_id,
                'batch' => $tryout->batch->nama,
                'nama' => $tryout->nama,
                'tanggal' => $tryout->tanggal,
                'image' => $tryout->image,
                'batch_id' => $tryout->batch_id,
                'status' => $tryout->status,
                'waktu' => $tryout->waktu,
                'status_tryout' => $tryout->status_tryout,
                'nilai' => $tryout->nilai,
                'type' => 'satuan',
                'is_need_confirm' => $tryout->is_need_confirm,
                'question_count' => $tryout->question_count,
            ];
        });

        $paketTryouts = PaketTryout::with(['tryouts.userTryouts' => function ($query) use ($userId) {
            $query->where('user_id', $userId); // Ambil hanya user_tryouts milik user tertentu
        }])
            ->where('status', 'active')
            ->get();

        // Filter berdasarkan kebutuhan konfirmasi
        $paketTryouts = $paketTryouts->filter(function ($paket) use ($userId) {
            if ($paket->is_need_confirm) {
                $access = \App\Models\UserAccessPaket::where('user_id', $userId)
                    ->where('paket_id', $paket->id)
                    ->first();
                return $access && $access->status === 'accepted';
            } else {
                return true;
            }
        });

        // Filter berdasarkan tab
        $paketTryouts = $paketTryouts->filter(function ($paket) {
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

        $paketTryouts = $paketTryouts->map(function ($paket) {
            return [
                'tryout_id' => $paket->id ?? null,
                'batch' => null,
                'nama' => $paket->paket ?? '',
                'tanggal' => null,
                'image' => $paket->image ?? '',
                'batch_id' => null,
                'status' => null,
                'waktu' => null,
                'status_tryout' => $paket->status ?? null,
                'nilai' => null,
                'type' => 'paket',
                'is_need_confirm' => $paket->is_need_confirm ?? false,
                'question_count' => $paket->tryouts ? $paket->tryouts->count() : 0,
            ];
        });


        $this->tryouts = $this->tryouts->concat($paketTryouts);

        return view('livewire.tryouts-tab');
    }
}
