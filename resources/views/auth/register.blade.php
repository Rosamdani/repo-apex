<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">

<x-head :title="'Register'" />
<x-style />

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
                    <form action="{{ route('registerStore') }}" method="post">
                        @csrf
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
                            @if ($errors->any())
                                <div class="alert alert-danger" role="alert">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="row gy-3">
                                <div class="position-relative ">
                                    <div class="icon-field mb-16">
                                        <span class="icon top-50 translate-middle-y">
                                            <iconify-icon icon="f7:person"></iconify-icon>
                                        </span>
                                        <input type="text" name="name" value="{{ old('name') }}"
                                            class="form-control h-56-px bg-neutral-50 radius-12 {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                            placeholder="Nama Lengkap">
                                    </div>
                                </div>
                                <div class="-mb-6">
                                    <div class="position-relative ">
                                        <div class="icon-field">
                                            <span class="icon top-50 translate-middle-y">
                                                <iconify-icon icon="f7:person"></iconify-icon>
                                            </span>
                                            <input type="text" required value="{{ old('username') }}"
                                                class="form-control h-56-px bg-neutral-50 radius-12 {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                                placeholder="Username" name="username">
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
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="form-control h-56-px bg-neutral-50 radius-12 {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                            placeholder="Email">
                                    </div>
                                </div>
                                <div class="position-relative ">
                                    <div class="icon-field mb-16">
                                        <span class="icon top-50 translate-middle-y">
                                            <iconify-icon icon="mage:phone"></iconify-icon>
                                        </span>
                                        <input type="text" name="no_telp" value="{{ old('no_telp') }}"
                                            class="form-control h-56-px bg-neutral-50 radius-12 {{ $errors->has('no_telp') ? 'is-invalid' : '' }}"
                                            placeholder="Nomor Telepon Aktif (08-------------)">
                                    </div>
                                </div>

                                <div class="form-group text-end">
                                    <button type="button"
                                        class="form-wizard-next-btn btn btn-primary-600 px-32">{{ __('Selanjutnya') }}</button>
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
                                        <input type="text" name="universitas" value="{{ old('universitas') }}"
                                            class="form-control h-56-px bg-neutral-50 radius-12 {{ $errors->has('universitas') ? 'is-invalid' : '' }}"
                                            placeholder="Universitas Asal">
                                    </div>
                                </div>
                                <div class="position-relative ">
                                    <div class="icon-field mb-16">
                                        <span class="icon top-50 translate-middle-y">
                                            <iconify-icon icon="mdi:calendar-blank"></iconify-icon>
                                        </span>
                                        <select name="tahun_masuk" id="tahun_masuk"
                                            class="form-control form-select h-56-px bg-neutral-50 radius-12 {{ $errors->has('tahun_masuk') ? 'is-invalid' : '' }}">
                                            <option value="">--Pilih Tahun Masuk FK--</option>
                                            @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                                <option value="{{ $i }}"
                                                    {{ old('tahun_masuk') == $i ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="position-relative d-flex align-items-center mb-16">
                                    <select name="status_pendidikan" name="status_pendidikan" id="status_pendidikan"
                                        class="form-control form-select h-5 bg-neutral-50 radius-12 ms-auto me-auto {{ $errors->has('status_pendidikan') ? 'is-invalid' : '' }}">
                                        <option value="">--Pilih Status Pendidikan--</option>
                                        <option {{ old('status_pendidikan') == 'koas' ? 'selected' : '' }}
                                            value="koas">Koas</option>
                                        <option {{ old('status_pendidikan') == 'pre-klinik' ? 'selected' : '' }}
                                            value="pre-klinik">Pre Klinik</option>
                                    </select>
                                </div>
                                <div class="mb-16" id="semester">
                                    <div class="position-relative ">
                                        <div class="icon-field">
                                            <span class="icon top-50 translate-middle-y">
                                                <iconify-icon icon="mdi:university"></iconify-icon>
                                            </span>
                                            <input type="number" name="semester" value="{{ old('semester') }}"
                                                class="form-control h-56-px bg-neutral-50 radius-12 {{ $errors->has('semester') ? 'is-invalid' : '' }}"
                                                placeholder="Semester Saat Ini">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group d-flex align-items-center justify-content-end gap-8 mt-20">
                                    <button type="button"
                                        class="form-wizard-previous-btn btn btn-neutral-500 border-neutral-100 px-32">{{ __('Kembali') }}</button>
                                    <button type="button"
                                        class="form-wizard-next-btn btn btn-primary-600 px-32">{{ __('Selanjutnya') }}</button>
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
                                            <input type="password" name="password" value="{{ old('password') }}"
                                                class="form-control h-56-px bg-neutral-50 radius-12 {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                                id="password" placeholder="Password">
                                        </div>
                                        <span
                                            class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                            data-toggle="#password"></span>
                                    </div>
                                    <span
                                        class="mt-8 text-sm text-secondary-light">{{ __('Password minimal 8 karakter, huruf besar, huruf kecil, angka, dan simbol') }}</span>
                                </div>
                                <div class="mb-20">
                                    <div class="position-relative ">
                                        <div class="icon-field">
                                            <span class="icon top-50 translate-middle-y">
                                                <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                                            </span>
                                            <input type="password" name="password_confirmation"
                                                value="{{ old('password_confirmation') }}"
                                                class="form-control h-56-px bg-neutral-50 radius-12 {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                                                id="password_confirmation" placeholder="Konfirmasi Password">
                                        </div>
                                        <span
                                            class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                            data-toggle="#password_confirmation"></span>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="d-flex justify-content-between gap-2">
                                        <div class="form-check style-check d-flex align-items-start">
                                            <input name="syarat" id="syarat" required
                                                class="form-check-input border border-neutral-300 mt-4 {{ $errors->has('syarat') ? 'is-invalid' : '' }}"
                                                type="checkbox" value="" id="syarat">
                                            <label class="form-check-label text-sm" for="syarat">
                                                {{ __('Dengan membuat akun berarti Anda setuju dengan') }}
                                                <a href="javascript:void(0)"
                                                    class="text-primary-600 fw-semibold">{{ __('Syarat & Ketentuan') }}</a>
                                                {{ __('dan') }}
                                                <a href="javascript:void(0)"
                                                    class="text-primary-600 fw-semibold">{{ __('Kebijakan Privasi') }}</a>
                                            </label>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group d-flex align-items-center justify-content-end gap-8">
                                    <button type="button"
                                        class="form-wizard-previous-btn btn btn-neutral-500 border-neutral-100 px-32">{{ __('Kembali') }}</button>
                                    <button type="submit" class="form-wizard-submit btn btn-primary-600 px-32">Daftar
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


        $(document).on('change', '#status_pendidikan', function() {
            var status_pendidikan = $(this).val();
            if (status_pendidikan == 'pre-klinik') {
                $('#semester').show();
            } else {
                $('#semester').hide();
            }
        })

        $('#status_pendidikan').trigger('change');
    </script>

    <script>
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
        initializePasswordToggle(".toggle-password");
    </script>
</body>

</html>
