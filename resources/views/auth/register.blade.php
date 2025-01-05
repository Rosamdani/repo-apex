<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">

<x-head :title="'Register'" />

<body>

    <section class="auth bg-base d-flex flex-wrap">
        <div class="auth-left d-lg-block d-none">
            <div class="d-flex align-items-center flex-column h-100 justify-content-center">
                <img style="height: 100%; width: 100%"
                    src="{{ setting('auth.login_image') ? asset('storage/' . setting('auth.login_image')) : asset('assets/images/auth/auth-img.png') }}"
                    alt="">
            </div>
        </div>
        <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
            <div class="max-w-464-px mx-auto w-100">
                <div>
                    <a href="{{ route('index') }}" class="mb-40 max-w-290-px">
                        <img style="height: 60px;"
                            src="{{ setting('general.logo') ? asset('storage/' . setting('general.logo')) : asset('assets/images/logo.png') }}"
                            alt="">
                    </a>
                    <h4 class="mb-12">Buat akun anda</h4>
                    <p class="mb-32 text-secondary-light text-lg">Untuk membuat akun, isi informasi berikut</p>
                </div>
                <div class="form-wizard">
                    <form action="#" method="post">
                        <div class="form-wizard-header overflow-x-auto scroll-sm pb-8 my-32">
                            <ul class="list-unstyled form-wizard-list">
                                <li class="form-wizard-list__item active">
                                    <div class="form-wizard-list__line">
                                        <span class="count">1</span>
                                        <span class="form-wizard-list__line__connector"></span>
                                    </div>
                                    <span class="text text-xs fw-semibold">Informasi Personal </span>
                                </li>
                                <li class="form-wizard-list__item">
                                    <div class="form-wizard-list__line">
                                        <span class="count">2</span>
                                        <span class="form-wizard-list__line__connector"></span>
                                    </div>
                                    <span class="text text-xs fw-semibold">Informasi Akademik</span>
                                </li>
                                <li class="form-wizard-list__item">
                                    <div class="form-wizard-list__line">
                                        <span class="count">3</span>
                                    </div>
                                    <span class="text text-xs fw-semibold">Keamanan Akun</span>
                                </li>
                            </ul>
                        </div>

                        <fieldset class="wizard-fieldset show">
                            <h6 class="text-md text-neutral-500">Informasi Personal</h6>
                            <div class="row gy-3">
                                <div class="position-relative ">
                                    <div class="icon-field mb-16">
                                        <span class="icon top-50 translate-middle-y">
                                            <iconify-icon icon="f7:person"></iconify-icon>
                                        </span>
                                        <input type="text" class="form-control h-56-px bg-neutral-50 radius-12"
                                            placeholder="Nama Lengkap">
                                    </div>
                                </div>
                                <div class="-mb-6">
                                    <div class="position-relative ">
                                        <div class="icon-field">
                                            <span class="icon top-50 translate-middle-y">
                                                <iconify-icon icon="f7:person"></iconify-icon>
                                            </span>
                                            <input type="text" class="form-control h-56-px bg-neutral-50 radius-12"
                                                placeholder="Username">
                                        </div>
                                    </div>
                                    <span class="mt-8 text-sm text-secondary-light">Username digunakan untuk
                                        login</span>
                                </div>
                                <div class="position-relative ">
                                    <div class="icon-field mb-16">
                                        <span class="icon top-50 translate-middle-y">
                                            <iconify-icon icon="mage:email"></iconify-icon>
                                        </span>
                                        <input type="email" class="form-control h-56-px bg-neutral-50 radius-12"
                                            placeholder="Email">
                                    </div>
                                </div>
                                <div class="position-relative ">
                                    <div class="icon-field mb-16">
                                        <span class="icon top-50 translate-middle-y">
                                            <iconify-icon icon="mage:phone"></iconify-icon>
                                        </span>
                                        <input type="text" class="form-control h-56-px bg-neutral-50 radius-12"
                                            placeholder="Nomor Telepon Aktif (08-------------)">
                                    </div>
                                </div>

                                <div class="form-group text-end">
                                    <button type="button"
                                        class="form-wizard-next-btn btn btn-primary-600 px-32">Next</button>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="wizard-fieldset">
                            <h6 class="text-md text-neutral-500">Informasi Akademik</h6>
                            <div class="row gy-3">
                                <div class="position-relative ">
                                    <div class="icon-field mb-16">
                                        <span class="icon top-50 translate-middle-y">
                                            <iconify-icon icon="mdi:school"></iconify-icon>
                                        </span>
                                        <input type="text" class="form-control h-56-px bg-neutral-50 radius-12"
                                            placeholder="Universitas Asal">
                                    </div>
                                </div>
                                <div class="position-relative ">
                                    <div class="icon-field mb-16">
                                        <span class="icon top-50 translate-middle-y">
                                            <iconify-icon icon="mdi:calendar-blank"></iconify-icon>
                                        </span>
                                        <input type="number" class="form-control h-56-px bg-neutral-50 radius-12"
                                            placeholder="Tahun masuk">
                                    </div>
                                </div>
                                <div class="position-relative d-flex align-items-center">
                                    <select name="status_pendidikan" id="status_pendidikan"
                                        class="form-control form-select h-56-px bg-neutral-50 radius-12 ms-auto me-auto">
                                        <option value="">--Pilih Status Pendidikan--</option>
                                        <option value="koas">Koas</option>
                                        <option value="pre-klinik">Pre Klinik</option>
                                    </select>
                                </div>
                                <div class="mb-20">
                                    <div class="position-relative ">
                                        <div class="icon-field">
                                            <span class="icon top-50 translate-middle-y">
                                                <iconify-icon icon="mdi:university"></iconify-icon>
                                            </span>
                                            <input type="number" class="form-control h-56-px bg-neutral-50 radius-12"
                                                placeholder="Semester Saat Ini">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group d-flex align-items-center justify-content-end gap-8">
                                    <button type="button"
                                        class="form-wizard-previous-btn btn btn-neutral-500 border-neutral-100 px-32">Back</button>
                                    <button type="button"
                                        class="form-wizard-next-btn btn btn-primary-600 px-32">Next</button>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="wizard-fieldset">
                            <h6 class="text-md text-neutral-500">Keamanan Akun</h6>
                            <div class="row gy-3">

                                <div class="">
                                    <div class="position-relative ">
                                        <div class="icon-field">
                                            <span class="icon top-50 translate-middle-y">
                                                <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                                            </span>
                                            <input type="password"
                                                class="form-control h-56-px bg-neutral-50 radius-12"
                                                id="your-password" placeholder="Password">
                                        </div>
                                        <span
                                            class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                            data-toggle="#your-password"></span>
                                    </div>
                                    <span class="mt-8 text-sm text-secondary-light">Password minimal 8 karakter</span>
                                </div>
                                <div class="mb-20">
                                    <div class="position-relative ">
                                        <div class="icon-field">
                                            <span class="icon top-50 translate-middle-y">
                                                <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                                            </span>
                                            <input type="password"
                                                class="form-control h-56-px bg-neutral-50 radius-12"
                                                id="your-re-password" placeholder="Konfirmasi Password">
                                        </div>
                                        <span
                                            class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                            data-toggle="#your-password"></span>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="d-flex justify-content-between gap-2">
                                        <div class="form-check style-check d-flex align-items-start">
                                            <input class="form-check-input border border-neutral-300 mt-4"
                                                type="checkbox" value="" id="condition">
                                            <label class="form-check-label text-sm" for="condition">
                                                By creating an account means you agree to the
                                                <a href="javascript:void(0)"
                                                    class="text-primary-600 fw-semibold">Terms
                                                    &
                                                    Conditions</a> and our
                                                <a href="javascript:void(0)"
                                                    class="text-primary-600 fw-semibold">Privacy Policy</a>
                                            </label>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group d-flex align-items-center justify-content-end gap-8">
                                    <button type="button"
                                        class="form-wizard-previous-btn btn btn-neutral-500 border-neutral-100 px-32">Back</button>
                                    <button type="button"
                                        class="form-wizard-next-btn btn btn-primary-600 px-32">Daftar
                                        Sekarang</button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                    <div class="mt-32 text-center text-sm">
                        <p class="mb-0">Sudah punya akun? <a href="{{ route('login') }}"
                                class="text-primary-600 fw-semibold">Masuk</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php
        $script = '<script>
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

    <!-- jQuery library js -->
    <script src="{{ asset('assets/js/lib/jquery-3.7.1.min.js') }}"></script>
    <!-- Bootstrap js -->
    <script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>
    <!-- Iconify Font js -->
    <script src="{{ asset('assets/js/lib/iconify-icon.min.js') }}"></script>
    <!-- jQuery UI js -->
    <script src="{{ asset('assets/js/lib/jquery-ui.min.js') }}"></script>
    <!-- Vector Map js -->
    <script src="{{ asset('assets/js/lib/jquery-jvectormap-2.0.5.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/jquery-jvectormap-world-mill-en.js') }}"></script>
    <!-- Popup js -->
    <script src="{{ asset('assets/js/lib/magnifc-popup.min.js') }}"></script>
    <x-script />
    <script>
        // =============================== Wizard Step Js Start ================================
        $(document).ready(function() {
            // click on next button
            $(".form-wizard-next-btn").on("click", function() {
                var parentFieldset = $(this).parents(".wizard-fieldset");
                var currentActiveStep = $(this).parents(".form-wizard").find(".form-wizard-list .active");
                var next = $(this);
                var nextWizardStep = true;
                parentFieldset.find(".wizard-required").each(function() {
                    var thisValue = $(this).val();

                    if (thisValue == "") {
                        $(this).siblings(".wizard-form-error").show();
                        nextWizardStep = false;
                    } else {
                        $(this).siblings(".wizard-form-error").hide();
                    }
                });
                if (nextWizardStep) {
                    next.parents(".wizard-fieldset").removeClass("show", "400");
                    currentActiveStep.removeClass("active").addClass("activated").next().addClass("active",
                        "400");
                    next.parents(".wizard-fieldset").next(".wizard-fieldset").addClass("show", "400");
                    $(document).find(".wizard-fieldset").each(function() {
                        if ($(this).hasClass("show")) {
                            var formAtrr = $(this).attr("data-tab-content");
                            $(document).find(".form-wizard-list .form-wizard-step-item").each(
                                function() {
                                    if ($(this).attr("data-attr") == formAtrr) {
                                        $(this).addClass("active");
                                        var innerWidth = $(this).innerWidth();
                                        var position = $(this).position();
                                        $(document).find(".form-wizard-step-move").css({
                                            "left": position.left,
                                            "width": innerWidth
                                        });
                                    } else {
                                        $(this).removeClass("active");
                                    }
                                });
                        }
                    });
                }
            });
            //click on previous button
            $(".form-wizard-previous-btn").on("click", function() {
                var counter = parseInt($(".wizard-counter").text());;
                var prev = $(this);
                var currentActiveStep = $(this).parents(".form-wizard").find(".form-wizard-list .active");
                prev.parents(".wizard-fieldset").removeClass("show", "400");
                prev.parents(".wizard-fieldset").prev(".wizard-fieldset").addClass("show", "400");
                currentActiveStep.removeClass("active").prev().removeClass("activated").addClass("active",
                    "400");
                $(document).find(".wizard-fieldset").each(function() {
                    if ($(this).hasClass("show")) {
                        var formAtrr = $(this).attr("data-tab-content");
                        $(document).find(".form-wizard-list .form-wizard-step-item").each(
                            function() {
                                if ($(this).attr("data-attr") == formAtrr) {
                                    $(this).addClass("active");
                                    var innerWidth = $(this).innerWidth();
                                    var position = $(this).position();
                                    $(document).find(".form-wizard-step-move").css({
                                        "left": position.left,
                                        "width": innerWidth
                                    });
                                } else {
                                    $(this).removeClass("active");
                                }
                            });
                    }
                });
            });
            //click on form submit button
            $(document).on("click", ".form-wizard .form-wizard-submit", function() {
                var parentFieldset = $(this).parents(".wizard-fieldset");
                var currentActiveStep = $(this).parents(".form-wizard").find(".form-wizard-list .active");
                parentFieldset.find(".wizard-required").each(function() {
                    var thisValue = $(this).val();
                    if (thisValue == "") {
                        $(this).siblings(".wizard-form-error").show();
                    } else {
                        $(this).siblings(".wizard-form-error").hide();
                    }
                });
            });
            // focus on input field check empty or not
            $(".form-control").on("focus", function() {
                var tmpThis = $(this).val();
                if (tmpThis == "") {
                    $(this).parent().addClass("focus-input");
                } else if (tmpThis != "") {
                    $(this).parent().addClass("focus-input");
                }
            }).on("blur", function() {
                var tmpThis = $(this).val();
                if (tmpThis == "") {
                    $(this).parent().removeClass("focus-input");
                    $(this).siblings(".wizard-form-error").show();
                } else if (tmpThis != "") {
                    $(this).parent().addClass("focus-input");
                    $(this).siblings(".wizard-form-error").hide();
                }
            });
        });
        // =============================== Wizard Step Js End ================================
    </script>

</body>

</html>
