<div class="tab-pane fade show {{ $activeTab === $tab ? 'active' : '' }}" id="pills-button-{{ $tab }}"
    role="tabpanel" aria-labelledby="pills-button-{{ $tab }}-tab" tabindex="0">
    <div class="row g-3">
        @if (!$tryouts->isEmpty() && !$paketTryout->isEmpty())
            @if (!$tryouts->isEmpty())
                @foreach ($tryouts as $tryout)
                    @if ($tryout->status_tryout == 'active')
                        <div class="col-xxl-3 col-sm-6 col-xs-6">
                            <div class="nft-card h-100 bg-base radius-16 overflow-hidden d-flex flex-column">
                                <div class="radius-16 overflow-hidden">
                                    <img src="{{ $tryout->image ? asset('storage/' . $tryout->image) : asset('assets/images/product/product-default.jpg') }}"
                                        alt="" class="w-100 h-100 object-fit-cover">
                                </div>
                                <div class="p-10 d-flex flex-column justify-content-between flex-grow-1">
                                    <div>

                                        <span
                                            class="text-sm fw-semibold text-primary-600">{{ $tryout->batch->nama }}</span>
                                        <h6 class="text-md fw-bold text-primary-light">{{ $tryout->nama }}</h6>
                                        {{-- <div class="d-flex align-items-center gap-8">
                                <img src="{{ asset('assets/images/nft/nft-user-img1.png') }}"
                                    class="w-28-px h-28-px rounded-circle object-fit-cover" alt="">
                                <span class="text-sm text-secondary-light fw-medium">Watson Kristin</span>
                            </div> --}}
                                        @if ($tryout->status == 'finished')
                                            <div
                                                class="mt-10 d-flex align-items-center justify-content-between gap-8 flex-wrap">
                                                <span class="text-sm text-secondary-light fw-medium">Nilai:
                                                    <span
                                                        class="text-sm text-primary-light fw-semibold">{{ $tryout->nilai }}</span>
                                                </span>
                                                {{-- <span class="text-sm fw-semibold text-primary-600">{{ $tryout->nilai }}</span> --}}
                                            </div>
                                            <div
                                                class="mt-7 d-flex align-items-center justify-content-between gap-8 flex-wrap">
                                                <span class="text-sm text-secondary-light fw-medium">Status:
                                                    <span
                                                        class="text-sm {{ $tryout->nilai > 60 ? 'text-success' : 'text-danger' }} fw-semibold">{{ $tryout->nilai > 60 ? 'Lulus' : 'Gagal' }}
                                                    </span>
                                                </span>
                                            </div>
                                        @else
                                            <div
                                                class="mt-10 d-flex align-items-center justify-content-between gap-8 flex-wrap">
                                                <span class="text-sm text-secondary-light fw-medium">Waktu:
                                                    <span
                                                        class="text-sm text-primary-light fw-semibold">{{ $tryout->waktu }}
                                                        Menit</span>
                                                </span>
                                            </div>
                                            <div
                                                class="mt-7 d-flex align-items-center justify-content-between gap-8 flex-wrap">
                                                <span class="text-sm text-secondary-light fw-medium">Soal:
                                                    <span
                                                        class="text-sm text-primary-light fw-semibold">{{ $tryout->question_count }}
                                                        Soal</span>
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="d-flex align-items-center flex-wrap gap-8" style="margin-top: auto;">
                                        @if ($tryout->status == 'finished')
                                            <a href="{{ route('tryouts.hasil.index', $tryout->tryout_id) }}"
                                                wire:navigate
                                                class="btn rounded-pill border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">Hasil</a>
                                            <a href="{{ route('tryouts.hasil.pembahasan', $tryout->tryout_id) }}"
                                                wire:navigate
                                                class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Pembahasan</a>
                                        @elseif ($tryout->status == 'started' || $tryout->status == 'paused')
                                            <a href="{{ route('tryouts.show', ['id' => $tryout->tryout_id]) }}"
                                                wire:navigate
                                                class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Lanjutkan</a>
                                        @else
                                            <a href="{{ route('tryouts.show', ['id' => $tryout->tryout_id]) }}"
                                                wire:navigate
                                                class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Mulai</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
            @if (!$paketTryout->isEmpty())
                @foreach ($paketTryout as $item)
                    @dd($item)
                    @if ($item->status == 'active')
                        <div class="col-xxl-3 col-md-4 col-sm-6">
                            <div class="nft-card h-100 bg-base radius-16 overflow-hidden d-flex flex-column">
                                <div class="radius-16 overflow-hidden">
                                    <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('assets/images/product/product-default.jpg') }}"
                                        alt="" class="w-100 h-100 max-h-194-px object-fit-cover">
                                </div>
                                <div class="p-10 d-flex flex-column justify-content-between flex-grow-1">
                                    <div class="d-flex flex-column">
                                        <span class="text-sm fw-semibold text-primary-600">Paket Tryout</span>
                                        <a href="{{ route('katalog.paketan.detail', ['id' => $item->id]) }}"
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
                                        <a href="{{ route('katalog.paketan.detail', ['id' => $item->id]) }}"
                                            class="btn
                                        rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Lihat
                                            Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        @else
            <div class="text-center p-20">
                <img src="{{ asset('assets/images/vector/11669671_20943865.svg') }}" width="200" height="200"
                    class="mx-auto mb-10" alt="Placeholder">
                <h6 class="fw-bold text-secondary-light">Data tidak tersedia</h6>
            </div>
        @endif
    </div>
</div>
