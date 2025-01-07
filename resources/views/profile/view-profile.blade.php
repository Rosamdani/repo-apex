@extends('layouts.layout')
@php
    $title = 'Profil';
    $subTitle = 'Profil Saya';
    $script = '<script>
        // ======================== Upload Image Start =====================
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $("#imagePreview").css("background-image", "url(" + e.target.result + ")");
                    $("#imagePreview").hide();
                    $("#imagePreview").fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#imageUpload").change(function() {
            readURL(this);
        });
        // ======================== Upload Image End =====================

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
    </script>';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-lg-4">
            <div class="user-grid-card position-relative border radius-16 overflow-hidden bg-base h-100">
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
                                <span class="w-70 text-secondary-light fw-medium">: Design</span>
                            </li>
                        </ul>
                        <h6 class="text-xl mt-24 mb-16">Informasi Akademik</h6>
                        <ul>
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
                                <span class="w-70 text-secondary-light fw-medium">: Design</span>
                            </li>
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
                        <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel"
                            aria-labelledby="pills-edit-profile-tab" tabindex="0">
                            <h6 class="text-md text-primary-light mb-16">Foto Profil</h6>
                            <!-- Upload Image Start -->
                            <div class="mb-24 mt-16">
                                <div class="avatar-upload">
                                    <div
                                        class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                                        <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" hidden>
                                        <label for="imageUpload"
                                            class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                                            <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                        </label>
                                    </div>
                                    <div class="avatar-preview">
                                        <div id="imagePreview">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Upload Image End -->
                            <form action="#">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="mb-20">
                                            <label for="name"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Lengkap
                                                <span class="text-danger-600">*</span></label>
                                            <input type="text" class="form-control radius-8"
                                                value="{{ auth()->user()->name }}" id="name"
                                                placeholder="Masukkan nama lengkap">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="mb-20">
                                            <label for="email"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span
                                                    class="text-danger-600">*</span></label>
                                            <input type="text" class="form-control radius-8"
                                                value="{{ auth()->user()->username }}" id="username"
                                                placeholder="Masukkan username">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="mb-20">
                                            <label for="number"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Phone</label>
                                            <input type="email" class="form-control radius-8" id="number"
                                                placeholder="Enter phone number">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-start gap-3">

                                    <button type="button"
                                        class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="pills-notification" role="tabpanel"
                            aria-labelledby="pills-notification-tab" tabindex="0">
                            <form action="#">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="mb-20">
                                            <label for="name"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Lengkap
                                                <span class="text-danger-600">*</span></label>
                                            <input name="university" type="text" class="form-control radius-8"
                                                value="{{ auth()->user()->name }}" id="university"
                                                placeholder="Masukkan nama universitas">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="mb-20">
                                            <label for="email"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span
                                                    class="text-danger-600">*</span></label>
                                            <input type="email" class="form-control radius-8"
                                                value="{{ auth()->user()->email }}" id="email"
                                                placeholder="Masukkan alamat email">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="mb-20">
                                            <label for="email"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Phone <span
                                                    class="text-danger-600">*</span></label>
                                            <input type="email" class="form-control radius-8" id="email"
                                                placeholder="Masukkan nomor telepon">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-start gap-3">

                                    <button type="button"
                                        class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="pills-change-passwork" role="tabpanel"
                            aria-labelledby="pills-change-passwork-tab" tabindex="0">
                            <div class="mb-20">
                                <label for="your-password"
                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Password Saat Ini <span
                                        class="text-danger-600">*</span></label>
                                <div class="position-relative">
                                    <input type="password" class="form-control radius-8" id="your-password"
                                        placeholder="Masukkan Password Saat Ini">
                                    <span
                                        class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                        data-toggle="#your-password"></span>
                                </div>
                            </div>
                            <div class="mb-20">
                                <label for="your-password"
                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Password Baru <span
                                        class="text-danger-600">*</span></label>
                                <div class="position-relative">
                                    <input type="password" class="form-control radius-8" id="your-password"
                                        placeholder="Masukkan Password Baru">
                                    <span
                                        class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                        data-toggle="#your-password"></span>
                                </div>
                            </div>
                            <div class="mb-20">
                                <label for="confirm-password"
                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Konfirmasi Password
                                    <span class="text-danger-600">*</span></label>
                                <div class="position-relative">
                                    <input type="password" class="form-control radius-8" id="confirm-password"
                                        placeholder="Masukkan Konfirmasi Password*">
                                    <span
                                        class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                        data-toggle="#confirm-password"></span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-start gap-3">
                                <button type="button"
                                    class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                                    Save
                                </button>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-notification" role="tabpanel"
                            aria-labelledby="pills-notification-tab" tabindex="0">
                            <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
                                <label for="companzNew" class="position-absolute w-100 h-100 start-0 top-0"></label>
                                <div class="d-flex align-items-center gap-3 justify-content-between">
                                    <span class="form-check-label line-height-1 fw-medium text-secondary-light">Company
                                        News</span>
                                    <input class="form-check-input" type="checkbox" role="switch" id="companzNew">
                                </div>
                            </div>
                            <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
                                <label for="pushNotifcation" class="position-absolute w-100 h-100 start-0 top-0"></label>
                                <div class="d-flex align-items-center gap-3 justify-content-between">
                                    <span class="form-check-label line-height-1 fw-medium text-secondary-light">Push
                                        Notification</span>
                                    <input class="form-check-input" type="checkbox" role="switch" id="pushNotifcation"
                                        checked>
                                </div>
                            </div>
                            <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
                                <label for="weeklyLetters" class="position-absolute w-100 h-100 start-0 top-0"></label>
                                <div class="d-flex align-items-center gap-3 justify-content-between">
                                    <span class="form-check-label line-height-1 fw-medium text-secondary-light">Weekly News
                                        Letters</span>
                                    <input class="form-check-input" type="checkbox" role="switch" id="weeklyLetters"
                                        checked>
                                </div>
                            </div>
                            <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
                                <label for="meetUp" class="position-absolute w-100 h-100 start-0 top-0"></label>
                                <div class="d-flex align-items-center gap-3 justify-content-between">
                                    <span class="form-check-label line-height-1 fw-medium text-secondary-light">Meetups
                                        Near
                                        you</span>
                                    <input class="form-check-input" type="checkbox" role="switch" id="meetUp">
                                </div>
                            </div>
                            <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
                                <label for="orderNotification"
                                    class="position-absolute w-100 h-100 start-0 top-0"></label>
                                <div class="d-flex align-items-center gap-3 justify-content-between">
                                    <span class="form-check-label line-height-1 fw-medium text-secondary-light">Orders
                                        Notifications</span>
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        id="orderNotification" checked>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
