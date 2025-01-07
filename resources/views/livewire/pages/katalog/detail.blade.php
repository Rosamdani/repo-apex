<?php

namespace App\Http\Livewire\Pages\Katalog;

use Livewire\Volt\Component;
use App\Models\Tryouts;

new class extends Component {
    public Tryouts $tryout;

    public function mount($tryoutId)
    {
        $this->tryout = Tryouts::where('id', $tryoutId)
            ->select(['id', 'nama', 'harga', 'image', 'waktu', 'batch_id'])
            ->with('batch')
            ->first();
    }
}; ?>

<div class="row">
    <div class="col-xl-8 mb-20">
        <img src="{{ $tryout->image ? asset('storage/' . $tryout->image) : asset('assets/images/product/product-default.jpg') }}"
            alt="{{ $tryout->nama }}" class="w-100 h-auto max-h-400-px rounded mb-20">
        @if ($tryout->deskripsi)
            <h5 class="fw-semibold">Deskripsi</h5>
            <p class="mt-10 text-secondary-light" style="text-align: justify;">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sed ultricies nisl, in finibus erat. Morbi
                in nulla sed nibh porta euismod. Donec auctor, nunc in lacinia auctor, erat nibh lacinia lectus, quis
                faucibus lorem ipsum a dolor. Sed eu lectus ut nunc ultrices malesuada. Sed nec nulla at metus
                malesuada faucibus. Nullam auctor, nunc in lacinia auctor, erat nibh lacinia lectus, quis faucibus lorem
                ipsum a dolor. Sed eu lectus ut nunc ultrices malesuada. Sed nec nulla at metus malesuada faucibus.
                Nullam auctor, nunc in lacinia auctor, erat nibh lacinia lectus, quis faucibus lorem ipsum a dolor. Sed
                eu
                lectus ut nunc ultrices malesuada. Sed nec nulla at metus malesuada faucibus. Nullam auctor, nunc in
                lacinia
                auctor, erat nibh lacinia lectus, quis faucibus lorem ipsum a dolor. Sed eu lectus ut nunc ultrices
                malesuada. Sed nec nulla at metus malesuada faucibus. Nullam auctor, nunc in lacinia auctor, erat nibh
                lacinia lectus, quis faucibus lorem ipsum a dolor. Sed eu lectus ut nunc ultrices malesuada. Sed nec
                nulla
                at metus malesuada faucibus. Nullam auctor, nunc in lacinia auctor, erat nibh lacinia lectus, quis
                faucibus
                lorem ipsum a dolor. Sed eu lectus ut nunc ultrices malesuada. Sed nec nulla at metus malesuada
                faucibus.
                Nullam auctor, nunc in lacinia auctor, erat nibh lacinia lectus, quis faucibus lorem ipsum a dolor. Sed
                eu
                lectus ut nunc ultrices malesuada. Sed nec nulla at metus malesuada faucibus. Nullam auctor, nunc in
                lacinia
                auctor, erat nibh lacinia lectus, quis faucibus lorem ipsum a dolor. Sed eu lectus ut nunc ultrices
                malesuada. Sed nec nulla at metus malesuada faucibus. Nullam auctor, nunc in lacinia auctor, erat nibh
                lacinia lectus, quis faucibus lorem ipsum a dolor. Sed eu lectus ut nunc ultrices malesuada. Sed nec
                nulla
                at metus malesuada faucibus. Nullam auctor, nunc in lacinia auctor, erat nibh lacinia lectus, quis
                faucibus
                lorem ipsum a dolor. Sed eu lectus ut nunc ultrices malesuada. Sed nec nulla at metus malesuada
                faucibus.
                Nullam auctor, nunc in lacinia auctor, erat nibh lacinia lectus, quis faucibus lorem ipsum a dolor. Sed
                eu
                lectus ut nunc ultrices malesuada. Sed nec nulla at metus malesuada faucibus. Nullam auctor, nunc in
                lacinia
                auctor, erat nibh lacinia lectus, quis faucibus lorem ipsum a dolor. Sed eu lectus ut nunc ultrices
                malesuada. Sed nec nulla at metus malesuada faucibus. Nullam auctor, nunc in lacinia auctor, erat nibh
                lacinia lectus, quis faucibus lorem ipsum a dolor. Sed eu lectus ut nunc ultrices malesuada. Sed nec
                nulla
                at metus malesuada faucibus. Nullam auctor, nunc in lacinia auctor, erat nibh lacinia lectus, quis
                faucibus
                lorem ipsum a dolor. Sed eu lectus ut nunc ultrices malesuada. Sed nec nulla at metus malesuada
                faucibus.
                Nullam auctor, nunc in lacinia auctor, erat nibh lacinia lectus, quis faucibus lorem ipsum a dolor. Sed
                eu
                lectus ut nunc ultrices malesuada. Sed nec nulla at metus malesuada faucibus. Nullam auctor, nunc in
                lacinia
                auctor, erat nibh lacinia lectus, quis faucibus lorem ipsum a dolor. Sed eu lectus ut nunc ultrices
                malesuada. Sed nec nulla at metus malesuada faucibus. Nullam auctor, nunc in lacinia auctor, erat nibh
                lacinia lectus, quis faucibus lorem ipsum a dolor. Sed eu lectus ut nunc ultrices malesuada. Sed nec
                nulla
                at metus malesuada faucibus. Nullam auctor, nunc in lacinia auctor, erat nibh lacinia lectus, quis
                faucibus
                lorem ipsum a dolor. Sed eu lectus ut nunc ultrices malesuada. Sed nec nulla at metus malesuada
                faucibus.
                Nullam auctor, nunc in lacinia auctor, erat nibh lacinia lectus, quis faucibus lorem ipsum a dolor. Sed
                eu
                lectus ut nunc ultrices malesuada. Sed nec nulla at metus malesuada faucibus. Nullam auctor, nunc in
                lacinia
                auctor, erat nibh lacinia lectus, quis faucibus lorem ipsum a dolor. Sed eu lectus ut nunc ultrices
                malesuada. Sed nec nulla at metus malesuada faucet
            </p>
        @endif
    </div>
    <div class="col-xl-4 mb-20">
        <div class="sticky-top z-1" style="top: 80px;">
            <div class="card p-3 gap-20">
                <h6 class="fw-bold text-primary-600">{{ $tryout->nama }}</h6>
                <ul class="list-unstyled d-flex flex-column">
                    <li
                        class="d-flex align-items-center flex-wrap justify-content-between text-sm text-secondary-light mb-1 border-start-0 border-end-0 border-bottom-0 border py-10">
                        <div class="d-flex align-items-center flex-nowrap gap-1">
                            <iconify-icon icon="mdi:clock-time-four"
                                class="text-primary-600 icon me-1 text-lg"></iconify-icon>
                            <span class="text-xl">Durasi:</span>
                        </div>
                        <span class="text-xl">{{ $tryout->waktu }} Menit</span>
                    </li>
                    <li
                        class="d-flex align-items-center flex-wrap justify-content-between text-sm text-secondary-light mb-1 border-start-0 border-end-0 border-bottom-0 border py-10">
                        <div class="d-flex align-items-center flex-nowrap gap-1">
                            <iconify-icon icon="mdi:currency-usd"
                                class="text-primary-600 icon me-1 text-xl"></iconify-icon>
                            <span class="text-xl">Harga:</span>
                        </div>
                        <span class="text-xl">Rp. {{ number_format($tryout->harga, 0, ',', '.') }}</span>
                    </li>
                    <li
                        class="d-flex align-items-center flex-wrap justify-content-between text-sm text-secondary-light mb-1 border-start-0 border-end-0 border-bottom-0 border py-10">
                        <div class="d-flex align-items-center flex-nowrap gap-1">
                            <iconify-icon icon="mdi:calendar" class="text-primary-600 icon me-1 text-xl"></iconify-icon>
                            <span class="text-xl">Batch:</span>
                        </div>
                        <span class="text-xl">{{ $tryout->batch->nama }}</span>
                    </li>
                    <li
                        class="d-flex align-items-center flex-wrap justify-content-between text-sm text-secondary-light mb-1 border-start-0 border-end-0 border py-10">
                        <div class="d-flex align-items-center flex-nowrap gap-1">
                            <iconify-icon icon="mdi:account" class="text-primary-600 icon me-1 text-xl"></iconify-icon>
                            <span class="text-xl">Peserta:</span>
                        </div>
                        <span class="text-xl">{{ $tryout->userTryouts->count() }}</span>
                    </li>
                </ul>

                @if ($tryout->status == 'finished')
                    <a href="{{ route('tryouts.hasil.index', $tryout->id) }}" wire:navigate
                        class="btn btn-primary-600 radius-8 px-12 py-6 mt-16">
                        Hasil
                    </a>
                @elseif ($tryout->status == 'started' || $tryout->status == 'paused')
                    <a href="{{ route('tryouts.show', ['id' => $tryout->id]) }}" wire:navigate
                        class="btn btn-secondaru-600 radius-8 px-12 py-6 mt-16">
                        Lanjutkan
                    </a>
                @else
                    <a href="{{ route('tryouts.show', ['id' => $tryout->id]) }}" wire:navigate
                        class="btn btn-primary-600 radius-8 px-12 py-6 mt-16">
                        Beli Sekarang
                    </a>
                    <a href="{{ route('tryouts.show', ['id' => $tryout->id]) }}" wire:navigate
                        class="btn border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">
                        Konfirmasi Admin
                    </a>
                @endif

            </div>
        </div>
    </div>
</div>
