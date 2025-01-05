<?php

use Livewire\Volt\Component;
use App\Models\BatchTryouts;
use App\Models\UserTryouts;

new class extends Component {
    public $batches;
    public $leaderboards;
    public $selectedBatch = '';

    public function mount()
    {
        $this->batches = BatchTryouts::all();
        $this->fetchLeaderboards();
    }

    public function fetchLeaderboards()
    {
        $query = UserTryouts::select('user_id', 'tryout_id', 'nilai')
            ->with(['user', 'tryout.batch'])
            ->where('status', 'finished')
            ->orderByDesc('nilai');

        // Filter berdasarkan batch yang dipilih
        if (!empty($this->selectedBatch)) {
            $query->whereHas('tryout.batch', function ($q) {
                $q->where('id', $this->selectedBatch);
            });
        }

        $this->leaderboards = $query
            ->get()
            ->groupBy('tryout_id')
            ->map(function ($group) {
                return $group->sortByDesc('nilai')->first();
            });
    }

    public function updatedSelectedBatch()
    {
        $this->fetchLeaderboards();
    }
}; ?>

<div class="col-12">
    <div class="card h-100">
        <div class="card-body p-24">
            <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                <h6 class="mb-2 fw-bold text-lg mb-0">Peserta Terbaik <span class="text-neutral-500 fw-normal">(per
                        tryout)</span></h6>
                <select wire:model.change="selectedBatch"
                    class="form-select form-select-sm w-auto bg-base border text-secondary-light rounded-pill">
                    <option value="">Semua Batch</option>
                    @foreach ($batches as $batch)
                        <option value="{{ $batch->id }}">{{ $batch->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table sm-table mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Peserta </th>
                            <th scope="col">Nilai</th>
                            <th scope="col">Batch </th>
                            <th scope="col">Tryout</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($leaderboards as $leaderboard)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $leaderboard->user->image_url !== null ? asset('storage/' . $leaderboard->user->image_url) : asset('assets/images/avatar/profile-default.png') }}"
                                            alt=""
                                            class="flex-shrink-0 me-12 w-40-px h-40-px rounded-circle me-12">
                                        <div class="flex-grow-1">
                                            <h6 class="text-md mb-0 fw-semibold">{{ $leaderboard->user->name }}</h6>
                                            <span class="text-sm text-secondary-light fw-normal">Owned by
                                                {{ $leaderboard->user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $leaderboard->nilai }}</td>
                                <td>{{ $leaderboard->tryout->batch->nama }}</td>
                                <td>{{ $leaderboard->tryout->nama }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
