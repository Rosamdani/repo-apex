@extends('layouts.layout')
@php
    $title = $userTryout->tryout->nama;
    $subTitle = 'Hasil Try out';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-xxxl-9">
            <div class="row gy-4">
                <div class="col-12  d-lg-none">
                    <div class="nft-promo-card h-240-px card radius-12 overflow-hidden position-relative z-1">
                        <img src="{{ $userTryout->tryout->image ? asset('storage/' . $userTryout->tryout->image) : asset('assets/images/product/product-default.jpg') }}"
                            class="position-absolute start-0 top-0 w-100 h-100 z-n1" alt="">
                        <div class="nft-promo-card__inner d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-16 text-white">{{ $userTryout->tryout->nama }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6">
                    <div class="card p-3 shadow-2 radius-8 h-100 bg-gradient-end-6">
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">

                                <div class="d-flex align-items-center gap-2">
                                    <span
                                        class="mb-0 w-48-px h-48-px bg-cyan-100 text-cyan-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
                                        <i class="ri-medal-fill"></i>
                                    </span>
                                    <div>
                                        <span class="fw-medium text-secondary-light text-sm">Nilai</span>
                                        <h6 class="fw-semibold mb-2">{{ $userTryout->nilai }}</h6>
                                    </div>
                                </div>
                            </div>
                            {{-- <p class="text-sm mb-0"> <span class="text-cyan-600">4</span> Doctors joined this week</p> --}}
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6">
                    <div class="card p-3 shadow-2 radius-8 h-100 bg-gradient-end-4">
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">

                                <div class="d-flex align-items-center gap-2">
                                    <span
                                        class="mb-0 w-48-px h-48-px bg-lilac-100 text-lilac-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
                                        <i class="ri-trophy-fill"></i>
                                    </span>
                                    <div>
                                        <span class="fw-medium text-secondary-light text-sm">Rangking</span>
                                        <h6 class="fw-semibold mb-2">{{ $userTryoutRank }}</h6>
                                    </div>
                                </div>
                            </div>
                            {{-- <p class="text-sm mb-0"> <span class="text-lilac-600">8</span> Staffs on vacation</p> --}}
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6">
                    <div class="card p-3 shadow-2 radius-8 h-100 bg-gradient-end-1">
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">

                                <div class="d-flex align-items-center gap-2">
                                    <span
                                        class="mb-0 w-48-px h-48-px bg-primary-100 text-primary-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
                                        <i class="ri-award-fill"></i>
                                    </span>
                                    <div>
                                        <span class="fw-medium text-secondary-light text-sm">Status</span>
                                        <h6 class="fw-semibold mb-2">{{ $status_lulus }}</h6>
                                    </div>
                                </div>
                            </div>
                            {{-- <p class="text-sm mb-0"> <span class="text-primary-600">170</span>New patients admitted</p> --}}
                        </div>
                    </div>
                </div>



                <div class="col-xxl-12">
                    <livewire:category-summary-cart :tryoutId="$userTryout->tryout_id" />
                </div>

                <!-- Leaderboard -->
                <livewire:pages.result.leaderboard :tryoutId="$userTryout->tryout_id" />
                <!-- Leaderboard -->

            </div>
        </div>

        <div class="col-xxxl-3">
            <div class="row gy-4">
                <div class="col-xxl-12 col-xl-6">

                    <livewire:result-chart :userTryout="$userTryout" />
                </div>
                {{-- Create User Testimonials Here --}}
                <livewire:pages.result.testimonials :tryoutId="$userTryout->tryout_id" />
            </div>
        </div>
    </div>
@endsection
