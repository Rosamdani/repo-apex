<div class="tab-pane fade show {{ $activeTab === $tab ? 'active' : '' }}" id="pills-button-{{ $tab }}"
    role="tabpanel" aria-labelledby="pills-button-{{ $tab }}-tab" tabindex="0">
    <div class="row g-3">
        @if (!$tryouts->isEmpty())
            @foreach ($tryouts as $tryout)
                @if ($tryout['status_tryout'] == 'active')
                    @if ($tryout['type'] === 'satuan')
                        <div class="col-xxl-3 col-sm-6 col-xs-6">
                            <div class="nft-card h-100 bg-base radius-16 overflow-hidden d-flex flex-column">
                                <div class="radius-16 overflow-hidden">
                                    <img src="{{ $tryout['image'] ? asset('storage/' . $tryout['image']) : asset('assets/images/product/product-default.jpg') }}"
                                        alt="" class="w-100 h-100 object-fit-cover">
                                </div>
                                <div class="p-10 d-flex flex-column justify-content-between flex-grow-1">
                                    <div>
                                        <span class="text-sm fw-semibold text-primary-600">{{ $tryout['batch'] }}</span>
                                        <h6 class="text-md fw-bold text-primary-light">{{ $tryout['nama'] }}</h6>
                                        @if ($tryout['status'] == 'finished')
                                            <div
                                                class="mt-10 d-flex align-items-center justify-content-between gap-8 flex-wrap">
                                                <span class="text-sm text-secondary-light fw-medium">Nilai:
                                                    <span
                                                        class="text-sm text-primary-light fw-semibold">{{ $tryout['nilai'] }}</span>
                                                </span>
                                            </div>
                                        @else
                                            <div
                                                class="mt-10 d-flex align-items-center justify-content-between gap-8 flex-wrap">
                                                <span class="text-sm text-secondary-light fw-medium">Waktu:
                                                    <span
                                                        class="text-sm text-primary-light fw-semibold">{{ $tryout['waktu'] }}
                                                        Menit</span>
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($tryout['type'] === 'paket')
                        <div class="col-xxl-3 col-md-4 col-sm-6">
                            <div class="nft-card h-100 bg-base radius-16 overflow-hidden d-flex flex-column">
                                <div class="radius-16 overflow-hidden">
                                    <img src="{{ $tryout['image'] ? asset('storage/' . $tryout['image']) : asset('assets/images/product/product-default.jpg') }}"
                                        alt="" class="w-100 h-100 max-h-194-px object-fit-cover">
                                </div>
                                <div class="p-10 d-flex flex-column justify-content-between flex-grow-1">
                                    <div class="d-flex flex-column">
                                        <span class="text-sm fw-semibold text-primary-600">Paket Tryout</span>
                                        <a href="{{ route('katalog.paketan.detail', ['id' => $tryout['tryout_id']]) }}"
                                            class="text-xl fw-bold text-primary-light">{{ $tryout['nama'] }}</a>
                                        <div
                                            class="mt-10 d-flex align-items-center justify-content-between gap-8 flex-wrap">
                                            <span class="text-sm text-secondary-light fw-medium">Total Tryouts:
                                                {{ $tryout['question_count'] }}</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center flex-column align-items-stretch gap-8 mt-10"
                                        style="margin-top: auto;">
                                        <a href="{{ route('katalog.paketan.detail', ['id' => $tryout['tryout_id']]) }}"
                                            class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Lihat
                                            Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach
        @else
            <div class="text-center p-20">
                <img src="{{ asset('assets/images/vector/11669671_20943865.svg') }}" width="200" height="200"
                    class="mx-auto mb-10" alt="Placeholder">
                <h6 class="fw-bold text-secondary-light">Data tidak tersedia</h6>
            </div>
        @endif
    </div>
</div>
