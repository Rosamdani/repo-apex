@extends('layouts.layout')
@php
    $title = 'Dashboard';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-xxl-8">
            <div class="row gy-4">


                <div class="col-12">
                    <div class="row gy-4">
                        <!-- Dashboard Widget Start -->
                        <div class="col-lg-4 col-sm-6">
                            <div class="card px-24 py-16 shadow-none radius-12 border h-100 bg-gradient-primary-50">
                                <div class="card-body p-0">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1">
                                        <div class="d-flex align-items-center flex-wrap gap-16">
                                            <span
                                                class="mb-0 w-40-px h-40-px bg-primary-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
                                                <iconify-icon icon="ph:book-light" class="icon"></iconify-icon>
                                            </span>

                                            <div class="flex-grow-1">
                                                <h6 class="fw-semibold mb-0">{{ $total_tryout }}</h6>
                                                <span class="fw-medium text-secondary-light text-md">Total Try Out</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Dashboard Widget End -->

                        <!-- Dashboard Widget Start -->
                        <div class="col-lg-4 col-sm-6">
                            <div class="card px-24 py-16 shadow-none radius-12 border h-100 bg-gradient-primary-50">
                                <div class="card-body p-0">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1">
                                        <div class="d-flex align-items-center flex-wrap gap-16">
                                            <span
                                                class="mb-0 w-40-px h-40-px bg-primary-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
                                                <iconify-icon icon="ph:calendar-blank-light" class="icon"></iconify-icon>
                                            </span>

                                            <div class="flex-grow-1">
                                                <h6 class="fw-semibold mb-0">{{ $batches->count() }}</h6>
                                                <span class="fw-medium text-secondary-light text-md">Total Batch</span>
                                                {{-- <p
                                                class="text-sm mb-0 d-flex align-items-center flex-wrap gap-12 mt-12">
                                                <span
                                                    class="bg-danger-focus px-6 py-2 rounded-2 fw-medium text-danger-main text-sm d-flex align-items-center gap-8">
                                                    +168.001%
                                                    <i class="ri-arrow-down-line"></i>
                                                </span> This week
                                            </p> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Dashboard Widget End -->

                        <!-- Dashboard Widget Start -->
                        <div class="col-lg-4 col-sm-6">
                            <div class="card px-24 py-16 shadow-none radius-12 border h-100 bg-gradient-primary-50">
                                <div class="card-body p-0">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1">
                                        <div class="d-flex align-items-center flex-wrap gap-16">
                                            <span
                                                class="mb-0 w-40-px h-40-px bg-primary-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
                                                <iconify-icon icon="mdi:check-all" class="icon"></iconify-icon>
                                            </span>

                                            <div class="flex-grow-1">
                                                <h6 class="fw-semibold mb-0">{{ $total_user_tryout }}</h6>
                                                <span class="fw-medium text-secondary-light text-md">Try Out
                                                    Dikerjakan</span>
                                                {{-- <p
                                                class="text-sm mb-0 d-flex align-items-center flex-wrap gap-12 mt-12">
                                                <span
                                                    class="bg-success-focus px-6 py-2 rounded-2 fw-medium text-success-main text-sm d-flex align-items-center gap-8">
                                                    +168.001%
                                                    <i class="ri-arrow-up-line"></i>
                                                </span> This week
                                            </p> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Dashboard Widget End -->
                    </div>
                </div>

                <div class="col-12">
                    <div class="mb-16 mt-8 d-flex flex-wrap justify-content-between gap-16">
                        <h6 class="mb-0">Try Out</h6>
                        <ul class="nav button-tab nav-pills mb-16 gap-12" id="pills-tab-three" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link fw-semibold text-secondary-light rounded-pill px-20 py-6 border border-neutral-300 active"
                                    id="pills-button-not_started-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-button-not_started" type="button" role="tab"
                                    aria-controls="pills-button-not_started" aria-selected="false" tabindex="-1">Belum
                                    Dikerjakan</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link fw-semibold text-secondary-light rounded-pill px-20 py-6 border border-neutral-300"
                                    id="pills-button-started-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-button-started" type="button" role="tab"
                                    aria-controls="pills-button-started" aria-selected="false" tabindex="-1">Sedang
                                    Dikerjakan</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link fw-semibold text-secondary-light rounded-pill px-20 py-6 border border-neutral-300"
                                    id="pills-button-finished-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-button-finished" type="button" role="tab"
                                    aria-controls="pills-button-finished" aria-selected="false"
                                    tabindex="-1">Selesai</button>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content" id="pills-tab-threeContent">
                        <livewire:tryouts-tab :tab="'started'" />
                        <livewire:tryouts-tab :tab="'not_started'" />
                        <livewire:tryouts-tab :tab="'finished'" />
                    </div>

                </div>

            </div>
        </div>

        <div class="col-xxl-4">
            <div class="row gy-4">
                <livewire:perkembangancart>
                    {{-- <div class="col-xxl-12 col-md-6">
                    <div class="card h-100">
                        <div
                            class="card-header border-bottom d-flex align-items-center flex-wrap gap-2 justify-content-between">
                            <h6 class="fw-bold text-lg mb-0">Statistics</h6>
                            <a href="javascript:void(0)"
                                class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                                View All
                                <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-1 justify-content-between mb-44">
                                <div>
                                    <h5 class="fw-semibold mb-12">145</h5>
                                    <span class="text-secondary-light fw-normal text-xl">Total Art Sold</span>
                                </div>
                                <div id="dailyIconBarChart"></div>
                            </div>
                            <div class="d-flex align-items-center gap-1 justify-content-between">
                                <div>
                                    <h5 class="fw-semibold mb-12">750 ETH</h5>
                                    <span class="text-secondary-light fw-normal text-xl">Total Earnings</span>
                                </div>
                                <div id="areaChart"></div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                    {{-- <div class="col-xxl-12 col-md-6">
                    <div class="card h-100">
                        <div
                            class="card-header border-bottom d-flex align-items-center flex-wrap gap-2 justify-content-between">
                            <h6 class="fw-bold text-lg mb-0">Featured Creators</h6>
                            <a href="javascript:void(0)"
                                class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                                View All
                                <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between gap-8 flex-wrap">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('assets/images/nft/nft-items-img1.png') }}" alt=""
                                        class="flex-shrink-0 me-12 w-40-px h-40-px rounded-circle me-12">
                                    <div class="flex-grow-1">
                                        <h6 class="text-md mb-0 fw-semibold">Theresa Webb</h6>
                                        <span class="text-sm text-secondary-light fw-normal">Owned by ABC</span>
                                    </div>
                                </div>
                                <button type="button"
                                    class="btn btn-outline-primary-600 px-24 rounded-pill">Follow</button>
                            </div>
                            <div class="mt-24">
                                <div class="row gy-3">
                                    <div class="col-sm-6 col-xs-6">
                                        <div class="nft-card bg-base radius-16 overflow-hidden shadow-4">
                                            <div class="radius-16 overflow-hidden">
                                                <img src="{{ asset('assets/images/nft/featured-creator1.png') }}"
                                                    alt="" class="w-100 h-100 object-fit-cover">
                                            </div>
                                            <div class="p-12">
                                                <h6 class="text-md fw-bold text-primary-light mb-12">New Figures</h6>
                                                <div class="d-flex align-items-center gap-8">
                                                    <img src="{{ asset('assets/images/nft/bitcoin.png') }}"
                                                        class="w-28-px h-28-px rounded-circle object-fit-cover"
                                                        alt="">
                                                    <span class="text-sm text-secondary-light fw-medium">0.10 BTC</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-6">
                                        <div class="nft-card bg-base radius-16 overflow-hidden shadow-4">
                                            <div class="radius-16 overflow-hidden">
                                                <img src="{{ asset('assets/images/nft/featured-creator2.png') }}"
                                                    alt="" class="w-100 h-100 object-fit-cover">
                                            </div>
                                            <div class="p-12">
                                                <h6 class="text-md fw-bold text-primary-light mb-12">Abstrac Girl</h6>
                                                <div class="d-flex align-items-center gap-8">
                                                    <img src="{{ asset('assets/images/nft/bitcoin.png') }}"
                                                        class="w-28-px h-28-px rounded-circle object-fit-cover"
                                                        alt="">
                                                    <span class="text-sm text-secondary-light fw-medium">0.10 BTC</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-12 col-md-6">
                    <div class="card h-100">
                        <div
                            class="card-header border-bottom d-flex align-items-center flex-wrap gap-2 justify-content-between">
                            <h6 class="fw-bold text-lg mb-0">Featured Creators</h6>
                            <a href="javascript:void(0)"
                                class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                                View All
                                <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                            </a>
                        </div>
                        <div class="card-body pt-24">
                            <div class="d-flex align-items-center justify-content-between gap-8 flex-wrap mb-32">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('assets/images/nft/creator-img1.png') }}" alt=""
                                        class="flex-shrink-0 me-12 w-40-px h-40-px rounded-circle me-12">
                                    <div class="flex-grow-1">
                                        <h6 class="text-md mb-0 fw-semibold">Theresa Webb</h6>
                                        <span class="text-sm text-secondary-light fw-normal">@wishon</span>
                                    </div>
                                </div>
                                <button type="button"
                                    class="btn bg-primary-600 border-primary-600 text-white px-24 rounded-pill follow-btn transition-2">Follow</button>
                            </div>
                            <div class="d-flex align-items-center justify-content-between gap-8 flex-wrap mb-32">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('assets/images/nft/creator-img2.png') }}" alt=""
                                        class="flex-shrink-0 me-12 w-40-px h-40-px rounded-circle me-12">
                                    <div class="flex-grow-1">
                                        <h6 class="text-md mb-0 fw-semibold">Arlene McCoy</h6>
                                        <span class="text-sm text-secondary-light fw-normal">@nemccoy</span>
                                    </div>
                                </div>
                                <button type="button"
                                    class="btn bg-primary-600 border-primary-600 text-white px-24 rounded-pill follow-btn transition-2">Follow</button>
                            </div>
                            <div class="d-flex align-items-center justify-content-between gap-8 flex-wrap mb-32">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('assets/images/nft/creator-img3.png') }}" alt=""
                                        class="flex-shrink-0 me-12 w-40-px h-40-px rounded-circle me-12">
                                    <div class="flex-grow-1">
                                        <h6 class="text-md mb-0 fw-semibold">Kathryn Murphy</h6>
                                        <span class="text-sm text-secondary-light fw-normal">@kathrynmur</span>
                                    </div>
                                </div>
                                <button type="button"
                                    class="btn bg-primary-600 border-primary-600 text-white px-24 rounded-pill follow-btn transition-2">Follow</button>
                            </div>
                            <div class="d-flex align-items-center justify-content-between gap-8 flex-wrap mb-32">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('assets/images/nft/creator-img4.png') }}" alt=""
                                        class="flex-shrink-0 me-12 w-40-px h-40-px rounded-circle me-12">
                                    <div class="flex-grow-1">
                                        <h6 class="text-md mb-0 fw-semibold">Marvin McKinney</h6>
                                        <span class="text-sm text-secondary-light fw-normal">@marvinckin</span>
                                    </div>
                                </div>
                                <button type="button"
                                    class="btn bg-primary-600 border-primary-600 text-white px-24 rounded-pill follow-btn transition-2">Follow</button>
                            </div>
                            <div class="d-flex align-items-center justify-content-between gap-8 flex-wrap mb-0">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('assets/images/nft/creator-img5.png') }}" alt=""
                                        class="flex-shrink-0 me-12 w-40-px h-40-px rounded-circle me-12">
                                    <div class="flex-grow-1">
                                        <h6 class="text-md mb-0 fw-semibold">Dianne Russell</h6>
                                        <span class="text-sm text-secondary-light fw-normal">@dinne_r</span>
                                    </div>
                                </div>
                                <button type="button"
                                    class="btn bg-primary-600 border-primary-600 text-white px-24 rounded-pill follow-btn transition-2">Follow</button>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>

        <livewire:dashboardleaderboard />

    </div>
@endsection
