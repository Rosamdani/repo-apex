<?php

use Livewire\Volt\Component;
use App\Models\UserTryouts;

new class extends Component {
    public $tryoutId; // ID Tryout
    public $leaderboard = [];

    protected $listeners = ['refreshLeaderboard' => '$refresh'];

    public function mount($tryoutId)
    {
        $this->tryoutId = $tryoutId;
        $this->leaderboard = UserTryouts::select(['user_id', 'tryout_id', 'nilai'])
            ->where('tryout_id', $this->tryoutId)
            ->with(['user:id,name,image_url,email', 'tryout:id,nama'])
            ->limit(10)
            ->orderBy('nilai', 'desc')
            ->get();
    }
}; ?>

<div class="col-12">
    <div class="card h-100">
        <div class="card-body p-24">
            <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                <div class="d-flex align-items-end">
                    <iconify-icon icon="fluent:trophy-24-filled"
                        class="icon text-warning-500 text-xl me-1"></iconify-icon>
                    <h6 class="fw-bold text-lg mb-0">{{ __('Leaderboard') }}</h6>
                </div>
                @if ($leaderboard->count() == 10)
                    <a href="javascript:void(0)"
                        class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                        View All
                        <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                    </a>
                @endif
            </div>
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table mb-0">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('Rank') }}</th>
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
                                            <h6 class="text-sm fw-semibold mb-0">{{ $leaderboard->user->name }}</h6>
                                            <span
                                                class="text-sm text-secondary-light">{{ $leaderboard->user->email }}</span>
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
                        {{-- <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('assets/images/users/user1.png') }}" alt=""
                                        class="flex-shrink-0 me-12 radius-8">
                                    <span class="text-lg text-secondary-light fw-semibold flex-grow-1">Dianne
                                        Russell</span>
                                </div>
                            </td>
                            <td>#6352148</td>
                            <td>iPhone 14 max</td>
                            <td>2</td>
                            <td>$5,000.00</td>
                            <td class="text-center"> <span
                                    class="bg-success-focus text-success-main px-24 py-4 rounded-pill fw-medium text-sm">Paid</span>
                            </td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
