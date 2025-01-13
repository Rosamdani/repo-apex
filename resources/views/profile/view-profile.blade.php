@extends('layouts.layout')
@php
    $title = 'Profil';
    $subTitle = 'Profil Saya';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-lg-4">
            <div class="user-grid-card position-relative border radius-16 bg-base" style="height: fit-content;">
                <img src="{{ asset('assets/images/avatar/avatar-thumbnail.jpg') }}" alt=""
                    class="w-100 max-h-194-px object-fit-cover">
                <div class="pb-24 ms-16 mb-24 me-16  mt--100">
                    <div class="text-center border border-top-0 border-start-0 border-end-0">
                        <img src="{{ auth()->user()->image_url != null ? asset('storage/' . auth()->user()->image_url) : asset('assets/images/avatar/profile-default.png') }}"
                            alt="{{ auth()->user()->name }}"
                            class="border br-white border-width-2-px w-200-px h-200-px rounded-circle object-fit-cover">
                        <h6 class="mb-0 mt-16">{{ auth()->user()->name }}</h6>
                        <span class="text-secondary-light mb-16">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="mt-24">
                        <h6 class="text-xl mb-16">Informasi Personal</h6>
                        <ul class="border border-top-0 border-start-0 border-end-0">
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light">Nama lengkap</span>
                                <span class="w-70 text-secondary-light fw-medium">: {{ auth()->user()->name }}</span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light"> Email</span>
                                <span class="w-70 text-secondary-light fw-medium">: {{ auth()->user()->email }}</span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light"> Username</span>
                                <span class="w-70 text-secondary-light fw-medium">: {{ auth()->user()->username }}</span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light"> Nomor Telepon</span>
                                <span class="w-70 text-secondary-light fw-medium">: {{ auth()->user()->no_telp }}</span>
                            </li>
                        </ul>
                        <h6 class="text-xl mt-24 mb-16">Informasi Akademik</h6>
                        <ul>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light">Universitas Asal</span>
                                <span class="w-70 text-secondary-light fw-medium">:
                                    {{ auth()->user()->userAcademy?->universitas }}</span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light">Tahun Masuk</span>
                                <span class="w-70 text-secondary-light fw-medium">:
                                    {{ auth()->user()->userAcademy?->tahun_masuk }}</span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light">Status Pendidikan</span>
                                <span class="w-70 text-secondary-light fw-medium">:
                                    {{ ucwords(auth()->user()->userAcademy?->status_pendidikan) }}</span>
                            </li>
                            @if (auth()->user()->userAcademy?->semester != null)
                                <li class="d-flex align-items-center gap-1 mb-12">
                                    <span class="w-30 text-md fw-semibold text-primary-light">Semester</span>
                                    <span class="w-70 text-secondary-light fw-medium">:
                                        {{ auth()->user()->userAcademy?->semester }}</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-body p-24">
                    <ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24 active" id="pills-edit-profile-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-edit-profile" type="button" role="tab"
                                aria-controls="pills-edit-profile" aria-selected="true">
                                Edit Profile
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24" id="pills-notification-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-notification" type="button" role="tab"
                                aria-controls="pills-notification" aria-selected="false" tabindex="-1">
                                Edit Info Akademik
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24" id="pills-change-passwork-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-change-passwork" type="button" role="tab"
                                aria-controls="pills-change-passwork" aria-selected="false" tabindex="-1">
                                Ubah Password
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <livewire:pages.profile.edit-profile />

                        <livewire:pages.profile.edit-academies />

                        <livewire:pages.profile.edit-password />

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        // ================== Password Show Hide Js Start ==========
        function initializePasswordToggle(toggleSelector) {
            $(toggleSelector).on("click", function() {
                $(this).toggleClass("ri-eye-off-line");
                var input = $($(this).attr("data-toggle"));
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });
        }
        // Call the function
        initializePasswordToggle(".toggle-password");
        // ========================= Password Show Hide Js End ===========================
    </script>
@endpush
