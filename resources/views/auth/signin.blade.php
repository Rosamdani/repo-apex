<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">

<x-head :title="'Login'" />

<body>

    <section class="auth bg-base d-flex flex-wrap">
        <div class="auth-left d-lg-block d-none">
            <div class="d-flex align-items-center flex-column h-100 justify-content-center">
                <img style="height: 100%; width: 100%"
                    src="{{ setting('auth.login_image') ? asset('storage/' . setting('auth.login_image')) : asset('assets/images/auth/auth-img.png') }}"
                    alt="">
            </div>
        </div>
        <div class="auth-right px-24 d-flex flex-column justify-content-center">
            <div class="max-w-464-px mx-auto w-100">
                <div>
                    <a href="#" class="mb-40 max-w-135-px">
                        <img src="{{ setting('general.logo') ? asset('storage/' . setting('general.logo')) : asset('assets/images/logo.png') }}"
                            alt="">
                    </a>
                    <h4 class="mb-12">Masuk ke akun anda</h4>
                    <p class="mb-32 text-secondary-light text-lg">Selamat datang kembali! masukkan data anda</p>
                </div>
                @if (session('error'))
                    <div class="alert alert-danger mb-16">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success mb-16">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger mb-16">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('loginStore') }}" method="POST">
                    @csrf
                    <div class="icon-field mb-16">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="mage:user"></iconify-icon>
                        </span>
                        <input type="text" name="username"
                            class="form-control @error('username') is-invalid @enderror h-56-px bg-neutral-50 radius-12"
                            placeholder="Username" value="{{ old('username') }}">
                    </div>
                    <div class="position-relative mb-20">
                        <div class="icon-field">
                            <span class="icon top-50 translate-middle-y">
                                <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                            </span>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror h-56-px bg-neutral-50 radius-12"
                                id="password" placeholder="Password">
                        </div>
                        <span
                            class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                            data-toggle="#password"></span>
                    </div>
                    <div class="">
                        <div class="d-flex justify-content-between gap-2">
                            <div class="form-check style-check d-flex align-items-center">
                                <input class="form-check-input border border-neutral-300" type="checkbox"
                                    name="remember" id="remeber" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remeber">Ingat saya </label>
                            </div>
                            <a href="{{ route('password.request') }}" class="text-primary-600 fw-medium">Lupa
                                password?</a>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">
                        Masuk</button>

                    {{-- <div class="mt-32 center-border-horizontal text-center">
                        <span class="bg-base z-1 px-4">Or sign in with</span>
                    </div>
                    <div class="mt-32 d-flex align-items-center gap-3">
                        <button type="button"
                            class="fw-semibold text-primary-light py-16 px-24 w-50 border radius-12 text-md d-flex align-items-center justify-content-center gap-12 line-height-1 bg-hover-primary-50">
                            <iconify-icon icon="ic:baseline-facebook" class="text-primary-600 text-xl line-height-1">
                            </iconify-icon>
                            Facebook
                        </button>
                        <button type="button"
                            class="fw-semibold text-primary-light py-16 px-24 w-50 border radius-12 text-md d-flex align-items-center justify-content-center gap-12 line-height-1 bg-hover-primary-50">
                            <iconify-icon icon="logos:google-icon" class="text-primary-600 text-xl line-height-1">
                            </iconify-icon>
                            Google
                        </button>
                    </div> --}}
                    <div class="mt-32 text-center text-sm">
                        <p class="mb-0">Belum punya akun?<a href="{{ route('register') }}"
                                class="text-primary-600 fw-semibold">Daftar Sekarang</a></p>
                    </div>

                </form>
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
    <x-script />

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
        // Call the function
        initializePasswordToggle(".toggle-password");
    </script>

</body>

</html>
