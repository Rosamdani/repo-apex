<?php

use Livewire\Volt\Component;
use App\Models\Tryouts;
use App\Models\PaketTryout;

new class extends Component {
    public $tryouts;
    public $trendingTryouts;
    public $paketTryouts;

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
                'tryouts.url',
                'tryouts.harga',
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
            'tryouts.url',
            'tryouts.harga',
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
        $this->getPaketTryout();
    }

    public function getPaketTryout()
    {
        $this->paketTryouts = PaketTryout::with('tryouts')->where('status', 'active')->get();
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
                                        class="text-xl fw-bold text-primary-light">{{ $trending->nama }}</a>
                                    <div
                                        class="mt-10 d-flex align-items-center justify-content-between gap-8 flex-wrap">
                                        <span class="text-sm text-secondary-light fw-medium">Harga:
                                            @if ($trending->harga || $trending->harga > 0)
                                                <span class="text-sm fw-semibold text-primary-600">Rp
                                                    {{ number_format($trending->harga, 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-sm fw-semibold text-primary-600">
                                                    Gratis
                                                </span>
                                            @endif
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
                                        @if ($trending->harga && $trending->harga > 0)
                                            <a href="{{ route('katalog.detail', ['id' => $trending->tryout_id]) }}"
                                                class="btn rounded-pill border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">Detail</a>
                                            <a href="{{ $trending->url ?? '#' }}"
                                                class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Beli
                                                Sekarang</a>
                                        @else
                                            <a href="{{ route('katalog.detail', ['id' => $trending->tryout_id]) }}"
                                                class="btn rounded-pill border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">Detail</a>
                                            <a href="{{ route('tryouts.show', ['id' => $trending->tryout_id]) }}"
                                                wire:navigate
                                                class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Mulai
                                                Kerjakan</a>
                                        @endif
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
            <ul class="nav button-tab nav-pills mb-16 gap-12" id="pills-tab-three" role="tablist">
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link d-flex align-items-center gap-2 fw-semibold text-secondary-light rounded-pill px-20 py-6 border border-neutral-300 active"
                        id="pills-button-satuan-tab" data-bs-toggle="pill" data-bs-target="#pills-button-satuan"
                        type="button" role="tab" aria-controls="pills-button-satuan" aria-selected="false"
                        tabindex="-1"><iconify-icon icon="mdi:clock-outline"
                            class="icon text-xl me-1"></iconify-icon>Satuan</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link d-flex align-items-center gap-2 fw-semibold text-secondary-light rounded-pill px-20 py-6 border border-neutral-300"
                        id="pills-button-paketan-tab" data-bs-toggle="pill" data-bs-target="#pills-button-paketan"
                        type="button" role="tab" aria-controls="pills-button-paketan" aria-selected="false"
                        tabindex="-1"><iconify-icon icon="ph:package-light"
                            class="icon text-xl me-1"></iconify-icon>Paket</button>
                </li>
            </ul>
        </div>
        <div class="tab-content" id="pills-tab-threeContent">
            <div class="tab-pane fade show active" id="pills-button-satuan" role="tabpanel"
                aria-labelledby="pills-button-satuan-tab" tabindex="0">
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
                                            <span
                                                class="text-sm fw-semibold text-primary-600">{{ $item->batch->nama }}</span>
                                            <a href="{{ route('katalog.detail', ['id' => $item->tryout_id]) }}"
                                                class="text-xl fw-bold text-primary-light">{{ $item->nama }}</a>
                                            <div
                                                class="mt-10 d-flex align-items-center justify-content-between gap-8 flex-wrap">
                                                <span class="text-sm text-secondary-light fw-medium">Harga:
                                                    @if ($item->harga && $item->harga > 0)
                                                        <span class="text-sm fw-semibold text-primary-600">Rp
                                                            {{ number_format($item->harga, 0, ',', '.') }}</span>
                                                    @else
                                                        <span class="text-sm fw-semibold text-primary-600">
                                                            Gratis
                                                        </span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center flex-column align-items-stretch gap-8 mt-10"
                                            style="margin-top: auto;">
                                            @if ($item->status == 'finished')
                                                <a href="{{ route('tryouts.hasil.index', $item->tryout_id) }}"
                                                    wire:navigate
                                                    class="btn rounded-pill border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">Hasil</a>
                                                <a href="{{ route('tryouts.hasil.pembahasan', $item->tryout_id) }}"
                                                    wire:navigate
                                                    class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Pembahasan</a>
                                            @elseif ($item->status == 'started' || $item->status == 'paused')
                                                <a href="{{ route('tryouts.show', ['id' => $item->tryout_id]) }}"
                                                    wire:navigate
                                                    class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Lanjutkan</a>
                                            @else
                                                @if ($item->harga && $item->harga > 0)
                                                    <a href="{{ route('katalog.detail', ['id' => $item->tryout_id]) }}"
                                                        class="btn rounded-pill border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">Detail</a>
                                                    <a href="{{ $item->url ?? '#' }}"
                                                        class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Beli
                                                        Sekarang</a>
                                                @else
                                                    <a href="{{ route('katalog.detail', ['id' => $item->tryout_id]) }}"
                                                        class="btn rounded-pill border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">Detail</a>
                                                    <a href="{{ route('tryouts.show', ['id' => $item->tryout_id]) }}"
                                                        wire:navigate
                                                        class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Mulai
                                                        Kerjakan</a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="tab-pane fade show" id="pills-button-paketan" role="tabpanel"
                aria-labelledby="pills-button-paketan-tab" tabindex="0">
                <div class="row g-3">
                    @foreach ($paketTryouts as $item)
                        <div class="col-xxl-3 col-md-4 col-sm-6">
                            <div class="nft-card h-100 bg-base radius-16 overflow-hidden d-flex flex-column">
                                <div class="radius-16 overflow-hidden">
                                    <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('assets/images/product/product-default.jpg') }}"
                                        alt="" class="w-100 h-100 max-h-194-px object-fit-cover">
                                </div>
                                <div class="p-10 d-flex flex-column justify-content-between flex-grow-1">
                                    <div>
                                        <a href="{{ route('katalog.paketan.detail', ['id' => $item->id]) }}"
                                            class="text-xl fw-bold text-primary-light">{{ $item->paket }}</a>
                                        <div
                                            class="mt-10 d-flex align-items-center justify-content-between gap-8 flex-wrap">
                                            <span class="text-sm text-secondary-light fw-medium">Harga:
                                                @if ($item->harga && $item->harga > 0)
                                                    <span class="text-sm fw-semibold text-primary-600">Rp
                                                        {{ number_format($item->harga, 0, ',', '.') }}</span>
                                                @else
                                                    <span class="text-sm fw-semibold text-primary-600">
                                                        Gratis
                                                    </span>
                                                @endif
                                            </span>
                                        </div>
                                        <div
                                            class="mt-10 d-flex align-items-center justify-content-between gap-8 flex-wrap">
                                            <span class="text-sm text-secondary-light fw-medium">Total Tryout: <span
                                                    class="text-sm fw-semibold text-primary-600">{{ $item->tryouts->count() }}</span></span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center flex-column align-items-stretch gap-8 mt-10"
                                        style="margin-top: auto;">
                                        @if ($item->harga && $item->harga > 0)
                                            <a href="{{ route('katalog.paketan.detail', ['id' => $item->id]) }}"
                                                class="btn rounded-pill border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">Detail</a>
                                            <a href="{{ $item->url ?? '#' }}"
                                                class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Beli
                                                Sekarang</a>
                                        @else
                                            <a href="{{ route('katalog.paketan.detail', ['id' => $item->id]) }}"
                                                class="btn rounded-pill border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">Detail</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
