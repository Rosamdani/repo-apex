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

        // Ambil dan format data tryouts satuan sebagai array of objects
        $satuanTryouts = Tryouts::with('batch')
            ->leftJoin('user_tryouts', function ($join) use ($userId) {
                $join->on('tryouts.id', '=', 'user_tryouts.tryout_id')->where('user_tryouts.user_id', '=', $userId);
            })
            ->leftJoin('batch_tryouts', 'tryouts.batch_id', '=', 'batch_tryouts.id')
            ->select(['tryouts.id as tryout_id', 'tryouts.nama', 'tryouts.tanggal', 'tryouts.image', 'tryouts.batch_id', 'tryouts.waktu', 'tryouts.url', 'tryouts.harga', 'tryouts.status as status_tryout', 'batch_tryouts.nama as batch_name', 'user_tryouts.status', 'user_tryouts.nilai', DB::raw('(SELECT COUNT(*) FROM soal_tryouts WHERE soal_tryouts.tryout_id = tryouts.id) as question_count')])
            ->get()
            ->map(function ($item) {
                return (object) [
                    'tryout_id' => $item->tryout_id,
                    'nama' => $item->nama,
                    'tanggal' => $item->tanggal,
                    'image' => $item->image,
                    'batch_id' => $item->batch_id,
                    'waktu' => $item->waktu,
                    'batch_name' => $item->batch_name,
                    'url' => $item->url,
                    'harga' => $item->harga,
                    'status_tryout' => $item->status_tryout,
                    'status' => $item->status,
                    'nilai' => $item->nilai,
                    'question_count' => $item->question_count,
                    'type' => 'satuan',
                    'created_at' => $item->created_at,
                    'is_need_confirm' => $item->is_need_confirm,
                ];
            });

        // Ambil dan format data paket tryouts sebagai array of objects
        $paketTryouts = PaketTryout::with('tryouts')
            ->where('status', 'active')
            ->get()
            ->map(function ($paket) {
                return (object) [
                    'tryout_id' => $paket->id,
                    'nama' => $paket->paket,
                    'tanggal' => $paket->created_at,
                    'image' => $paket->image,
                    'batch_id' => null,
                    'waktu' => null,
                    'url' => $paket->url ?? '#',
                    'harga' => $paket->harga,
                    'status_tryout' => $paket->status,
                    'status' => null,
                    'nilai' => null,
                    'question_count' => $paket->tryouts->count(),
                    'type' => 'paket',
                    'created_at' => $paket->created_at,
                    'is_need_confirm' => $paket->is_need_confirm,
                ];
            });

        // Gabungkan kedua koleksi
        $this->tryouts = $satuanTryouts->merge($paketTryouts);

        // Urutkan tryouts berdasarkan created_at
        $this->tryouts = $this->tryouts->sortByDesc('created_at');

        $this->trendingTryouts = Tryouts::select([
            'tryouts.id as tryout_id', // Kolom tryouts.id dengan alias
            'tryouts.nama',
            'tryouts.tanggal',
            'tryouts.image',
            'tryouts.batch_id',
            'tryouts.url',
            'tryouts.harga',
            'tryouts.is_need_confirm',
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
                                <div class="d-flex flex-column">
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
                                        @if ($trending->is_need_confirm == 1)
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
        </div>
        <div class="row g-3">
            @foreach ($tryouts as $item)
                @if ($item->status_tryout === 'active')
                    @if ($item->type === 'satuan')
                        <!-- Tampilan untuk Tryout Satuan -->
                        <div class="col-xxl-3 col-md-4 col-sm-6">
                            <div class="nft-card h-100 bg-base radius-16 overflow-hidden d-flex flex-column">
                                <div class="radius-16 overflow-hidden">
                                    <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('assets/images/product/product-default.jpg') }}"
                                        alt="" class="w-100 h-100 max-h-194-px object-fit-cover">
                                </div>
                                <div class="p-10 d-flex flex-column justify-content-between flex-grow-1">
                                    <div class="d-flex flex-column">
                                        <span class="text-sm fw-semibold text-primary-600">
                                            {{ $item->batch_name ?? 'Satuan' }}
                                        </span>
                                        <a href="{{ route('katalog.detail', ['id' => $item->tryout_id]) }}"
                                            class="text-xl fw-bold text-primary-light">{{ $item->nama }}</a>
                                        <div
                                            class="mt-10 d-flex align-items-center justify-content-between gap-8 flex-wrap">
                                            <span class="text-sm text-secondary-light fw-medium">Harga:
                                                @if ($item->harga && $item->harga > 0)
                                                    <span class="text-sm fw-semibold text-primary-600">Rp
                                                        {{ number_format($item->harga, 0, ',', '.') }}</span>
                                                @else
                                                    <span class="text-sm fw-semibold text-primary-600">Gratis</span>
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
                                        @else
                                            <a href="{{ route('katalog.detail', ['id' => $item->tryout_id]) }}"
                                                class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Lihat
                                                Detail</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($item->type === 'paket')
                        <!-- Tampilan untuk Tryout Paket -->
                        <div class="col-xxl-3 col-md-4 col-sm-6">
                            <div class="nft-card h-100 bg-base radius-16 overflow-hidden d-flex flex-column">
                                <div class="radius-16 overflow-hidden">
                                    <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('assets/images/product/product-default.jpg') }}"
                                        alt="" class="w-100 h-100 max-h-194-px object-fit-cover">
                                </div>
                                <div class="p-10 d-flex flex-column justify-content-between flex-grow-1">
                                    <div class="d-flex flex-column">
                                        <span class="text-sm fw-semibold text-primary-600">Paket Tryout</span>
                                        <a href="{{ route('katalog.paketan.detail', ['id' => $item->tryout_id]) }}"
                                            class="text-xl fw-bold text-primary-light">{{ $item->nama }}</a>
                                        <div
                                            class="mt-10 d-flex align-items-center justify-content-between gap-8 flex-wrap">
                                            <span class="text-sm text-secondary-light fw-medium">Harga:
                                                <span class="text-sm fw-semibold text-primary-600">Rp
                                                    {{ number_format($item->harga, 0, ',', '.') }}</span>
                                            </span>
                                            <span class="text-sm text-secondary-light fw-medium">Total Tryouts:
                                                {{ $item->question_count }}</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center flex-column align-items-stretch gap-8 mt-10"
                                        style="margin-top: auto;">
                                        <a href="{{ route('katalog.paketan.detail', ['id' => $item->tryout_id]) }}"
                                            class="btn
                                            rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Lihat
                                            Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach
        </div>
    </div>
</div>
