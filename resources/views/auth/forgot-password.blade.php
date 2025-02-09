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

                @if ($errors->has('email'))
                    <ul class="list-disc list-inside text-red-500">
                        @foreach ($errors->get('email') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <div>
                    <h4 class="mb-12">Lupa Password</h4>
                    <p class="mb-32 text-secondary-light text-lg">Masukkan alamat email yang terkait dengan akun Anda
                        dan kami akan mengirimkan tautan untuk mereset kata sandi Anda.</p>
                </div>
                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="icon-field">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="mage:email"></iconify-icon>
                        </span>
                        <input type="email" name="email"
                            class="form-control @error('email') is-invalid @enderror  h-56-px bg-neutral-50 radius-12"
                            placeholder="Masukkan Email">
                    </div>
                    <button type="submit"
                        class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">Lanjutkan</button>

                    <div class="mt-120 text-center text-sm">
                        <p class="mb-0">Sudah Punya Akun? <a href="{{ route('login') }}"
                                class="text-primary-600 fw-semibold">Login</a></p>
                    </div>

                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog modal-dialog-centered">
                            <div class="modal-content radius-16 bg-base">
                                <div class="modal-body p-40 text-center">
                                    <div class="mb-32">
                                        <img src="{{ asset('assets/images/auth/envelop-icon.png') }}" alt="">
                                    </div>
                                    <h6 class="mb-12">Verify your Email</h6>
                                    <p class="text-secondary-light text-sm mb-0">Terima kasih, periksa email Anda untuk
                                        instruksi
                                        mengatur
                                        ulang kata sandi Anda</p>
                                    <button type="button"
                                        class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">Skip</button>
                                    <div class="mt-32 text-sm">
                                        <p class="mb-0">Tidak menerima email? <a href=""
                                                class="text-primary-600 fw-semibold">Kirim ulang</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-body p-40 text-center">
                    <div class="mb-32">
                        <img src="{{ asset('assets/images/auth/envelop-icon.png') }}" alt="">
                    </div>
                    <h6 class="mb-12">Verify your Email</h6>
                    <p class="text-secondary-light text-sm mb-0">Terima kasih, periksa email Anda untuk instruksi
                        mengatur
                        ulang kata sandi Anda</p>
                    <button type="button"
                        class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">Skip</button>
                    <div class="mt-32 text-sm">
                        <p class="mb-0">Tidak menerima email? <a href=""
                                class="text-primary-600 fw-semibold">Kirim ulang</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
