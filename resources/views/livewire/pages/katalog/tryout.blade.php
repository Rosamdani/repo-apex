<?php

use Livewire\Volt\Component;
use App\Models\Tryouts;

new class extends Component {
    public $tryouts;
    public $trendingTryouts;

    public function mount()
    {
        $userId = auth()->id();
        $this->tryouts = Tryouts::with('batch')
            ->leftJoin('user_tryouts', function ($join) use ($userId) {
                $join->on('tryouts.id', '=', 'user_tryouts.tryout_id')->where('user_tryouts.user_id', '=', $userId);
            })
            ->select([
                'tryouts.id as tryout_id', // Kolom tryouts.id dengan alias
                'tryouts.nama',
                'tryouts.tanggal',
                'tryouts.image',
                'tryouts.batch_id',
                'tryouts.waktu',
                'tryouts.status as status_tryout',
                'user_tryouts.status',
                'user_tryouts.nilai',
                DB::raw('(SELECT COUNT(*) FROM soal_tryouts WHERE soal_tryouts.tryout_id = tryouts.id) as question_count'), // Hitung jumlah questions
            ])
            ->get();
        $this->trendingTryouts = Tryouts::select([
            'tryouts.id as tryout_id', // Kolom tryouts.id dengan alias
            'tryouts.nama',
            'tryouts.tanggal',
            'tryouts.image',
            'tryouts.batch_id',
            'tryouts.waktu',
            'tryouts.status as status_tryout',
            'user_tryouts.status',
            'user_tryouts.nilai',
        ])
            ->withCount('userTryouts')
            ->leftJoin('user_tryouts', function ($join) use ($userId) {
                $join->on('tryouts.id', '=', 'user_tryouts.tryout_id')->where('user_tryouts.user_id', '=', $userId);
            })
            ->orderBy('user_tryouts_count', 'desc')
            ->limit(4)
            ->get();
    }
}; ?>

<div class="row">
    <div class="col-12 mb-36">
        <div class="mb-16 mt-8 d-flex flex-wrap justify-content-between gap-16">
            <h6 class="mb-0">{{ __('Trending Saat Ini') }}</h6>
        </div>
        <div class="row g-3">
            @foreach ($trendingTryouts as $trending)
                @if ($trending->status_tryout === 'active')
                    <div class="col-xxl-3 col-md-4 col-sm-6">
                        <div class="nft-card h-100 bg-base radius-16 overflow-hidden d-flex flex-column">
                            <div class="radius-16 overflow-hidden">
                                <img src="{{ $trending->image ? asset('storage/' . $trending->image) : asset('assets/images/product/product-default.jpg') }}"
                                    alt="" class="w-100 h-100 max-h-194-px object-fit-cover">
                            </div>
                            <div class="p-10 d-flex flex-column justify-content-between flex-grow-1">
                                <div>
                                    <span
                                        class="text-sm fw-semibold text-primary-600">{{ $trending->batch->nama }}</span>
                                    <a href="{{ route('katalog.detail', ['id' => $trending->tryout_id]) }}"
                                        wire:navigate
                                        class="text-xl fw-bold text-primary-light">{{ $trending->nama }}</a>
                                    <div
                                        class="mt-10 d-flex align-items-center justify-content-between gap-8 flex-wrap">
                                        <span class="text-sm text-secondary-light fw-medium">Harga:
                                            <span class="text-sm fw-semibold text-primary-600">Rp
                                                {{ number_format(150000, 0, ',', '.') }}</span>
                                        </span>

                                    </div>
                                </div>
                                <div class="d-flex align-items-center flex-column align-items-stretch gap-8 mt-10"
                                    style="margin-top: auto;">
                                    @if ($trending->status == 'finished')
                                        <a href="{{ route('tryouts.hasil.index', $trending->tryout_id) }}" wire:navigate
                                            class="btn rounded-pill border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">Hasil</a>
                                        <a href="{{ route('tryouts.hasil.pembahasan', $trending->tryout_id) }}"
                                            wire:navigate
                                            class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Pembahasan</a>
                                    @elseif ($trending->status == 'started' || $trending->status == 'paused')
                                        <a href="{{ route('tryouts.show', ['id' => $trending->tryout_id]) }}"
                                            wire:navigate
                                            class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Lanjutkan</a>
                                    @else
                                        <a href="{{ route('katalog.detail', ['id' => $trending->tryout_id]) }}"
                                            wire:navigate
                                            class="btn rounded-pill border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">Detail</a>
                                        <a href="{{ $trending->url ?? '#' }}"
                                            class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Beli
                                            Sekarang</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="col-12 mb-36">
        <div class="mb-16 mt-8 d-flex flex-wrap justify-content-between gap-16">
            <h6 class="mb-0">{{ __('Semua Tryout') }}</h6>
        </div>
        <div class="row g-3">
            @foreach ($tryouts as $item)
                @if ($item->status_tryout === 'active')
                    <div class="col-xxl-3 col-md-4 col-sm-6">
                        <div class="nft-card h-100 bg-base radius-16 overflow-hidden d-flex flex-column">
                            <div class="radius-16 overflow-hidden">
                                <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('assets/images/product/product-default.jpg') }}"
                                    alt="" class="w-100 h-100 max-h-194-px object-fit-cover">
                            </div>
                            <div class="p-10 d-flex flex-column justify-content-between flex-grow-1">
                                <div>
                                    <span class="text-sm fw-semibold text-primary-600">{{ $item->batch->nama }}</span>
                                    <a href="{{ route('katalog.detail', ['id' => $item->tryout_id]) }}" wire:navigate
                                        class="text-xl fw-bold text-primary-light">{{ $item->nama }}</a>
                                    <div
                                        class="mt-10 d-flex align-items-center justify-content-between gap-8 flex-wrap">
                                        <span class="text-sm text-secondary-light fw-medium">Harga:
                                            <span class="text-sm fw-semibold text-primary-600">Rp
                                                {{ number_format(150000, 0, ',', '.') }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center flex-column align-items-stretch gap-8 mt-10"
                                    style="margin-top: auto;">
                                    @if ($item->status == 'finished')
                                        <a href="{{ route('tryouts.hasil.index', $item->tryout_id) }}" wire:navigate
                                            class="btn rounded-pill border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">Hasil</a>
                                        <a href="{{ route('tryouts.hasil.pembahasan', $item->tryout_id) }}"
                                            wire:navigate
                                            class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Pembahasan</a>
                                    @elseif ($item->status == 'started' || $item->status == 'paused')
                                        <a href="{{ route('tryouts.show', ['id' => $item->tryout_id]) }}" wire:navigate
                                            class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Lanjutkan</a>
                                    @else
                                        <a href="{{ route('katalog.detail', ['id' => $item->tryout_id]) }}"
                                            wire:navigate
                                            class="btn rounded-pill border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">Detail</a>
                                        <a href="{{ $item->url ?? '#' }}"
                                            class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Beli
                                            Sekarang</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
