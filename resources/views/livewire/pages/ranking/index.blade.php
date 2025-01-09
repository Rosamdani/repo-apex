<?php

use Livewire\Volt\Component;
use App\Models\UserTryouts;

new class extends Component {
    public $tryoutId;
    public $leaderboard = [];

    protected $listeners = ['refreshLeaderboard' => '$refresh'];

    public function mount($tryoutId)
    {
        $this->tryoutId = $tryoutId;
        $this->leaderboard = UserTryouts::select(['user_id', 'tryout_id', 'nilai'])
            ->where('tryout_id', $this->tryoutId)
            ->with(['user:id,name,image_url,email', 'tryout:id,nama'])
            ->orderBy('nilai', 'desc')
            ->get();
    }
}; ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ $leaderboard[0]->tryout->nama }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table bordered-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Peringkat') }}</th>
                                <th scope="col">{{ __('Peserta') }}</th>
                                <th scope="col">{{ __('Nilai') }}</th>
                                <th scope="col">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($this->leaderboard as $leaderboard)
                                <tr>
                                    <td style="width: 60px" class="fw-bold">#{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-8">
                                            <img src="{{ $leaderboard->user->image_url ? asset('storage/' . $leaderboard->user->image_url) : asset('assets/images/avatar/profile-default.png') }}"
                                                alt="{{ $leaderboard->user->name }}"
                                                class="rounded-circle w-32-px h-32-px me-8">
                                            <div class="flex-grow-1">
                                                <h6 class="text-sm fw-semibold mb-0">{{ $leaderboard->user->name }}
                                                    @if ($leaderboard->user->id === auth()->user()->id)
                                                        <span class="badge bg-primary-300">{{ __('Anda') }}</span>
                                                    @endif
                                                </h6>
                                                <span
                                                    class="text-sm text-secondary-light">{{ $leaderboard->user->userAcademy->universitas ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $leaderboard->nilai }}</td>
                                    <td>
                                        <span
                                            class="{{ $leaderboard->nilai >= 60 ? 'bg-success-focus text-success-main' : 'bg-danger-focus text-danger-main' }} px-12 py-4 rounded-pill fw-medium text-sm">
                                            {{ $leaderboard->nilai >= 60 ? __('Lulus') : __('Tidak Lulus') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- card end -->
    </div>
</div>
