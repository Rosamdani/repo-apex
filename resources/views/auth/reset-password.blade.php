<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">

<x-head />

<body>

    <section class="auth forgot-password-page bg-base d-flex flex-wrap">
        <div class="auth-left d-lg-block d-none">
            <div class="d-flex align-items-center flex-column h-100 justify-content-center">
                <img src="{{ setting('auth.login_image') ? asset('storage/' . setting('auth.login_image')) : asset('assets/images/auth/auth-img.png') }}"
                    alt="">
            </div>
        </div>
        <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
            <div class="max-w-464-px mx-auto w-100">
                @if (session('status'))
                    <div class="alert alert-success mb-16">
                        {{ session('status') }}
                    </div>
                @endif
                @if (session('email'))
                    <div class="alert alert-success mb-16">
                        {{ session('email') }}
                    </div>
                @endif
                <div>
                    <h4 class="mb-12">Ubah Password</h4>
                    <p class="mb-32 text-secondary-light text-lg"></p>
                </div>
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
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
                    <div class="position-relative mb-20">
                        <div class="icon-field">
                            <span class="icon top-50 translate-middle-y">
                                <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                            </span>
                            <input type="password" name="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror h-56-px bg-neutral-50 radius-12"
                                id="password_confirmation" placeholder="Konfirmasi Password">
                        </div>
                        <span
                            class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                            data-toggle="#repassword"></span>
                    </div>
                    <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32" ">Lanjutkan</button>
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

</body>

</html>
