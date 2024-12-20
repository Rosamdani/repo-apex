<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        {{ 'Login - ' . setting('general.app_name') ?? 'Laravel'}}
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


    @include('layouts.style')
</head>

<body class="primary-background">

    <div class="row mx-0">
        <div class="col-6 d-none d-md-block px-0">
            <img src="{{ asset('asset/images/cotton-plants-still-life.jpg') }}" alt="" class="w-100 object-cover"
                style="height: 100vh">
        </div>
        <div class="col-12 col-md-6 px-0">
            <div class="d-flex justify-content-center align-items-center w-100" style="height: 100vh">
                <div class="card bg-transparent container border-0 px-2 px-md-5">
                    <div class="card-header primary-background border-0">
                        <h4 class="text-center">Sign In to {{ setting('general.app_name') ?? 'Apexmedika'}}</h4>
                    </div>
                    <div class="card-body border-0 px-3 px-md-5">
                        <form action="{{ route('login') }}" method="post">
                            @csrf
                            @if (session()->has('error'))
                            <div class="alert py-1 alert-danger">
                                {{ session()->get('error') }}
                            </div>
                            @endif
                            <div class="mb-3">
                                <input type="email" name="email"
                                    class="form-control {{ $errors->has('email') ? 'is-invalid' : ''}} bg-light rounded-pill"
                                    required placeholder="Email">
                                @if ($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                                @endif
                            </div>
                            <div class="mb-1">
                                <input type="password" name="password"
                                    class="form-control bg-light {{ $errors->has('password') ? 'is-invalid' : ''}} rounded-pill"
                                    required placeholder="Password">
                            </div>
                            <div class="d-flex justify-content-between align-items-center px-2 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                                    <label class="form-check-label" for="rememberMe">Remember me</label>
                                </div>
                                <a href="/forgot-password" class="text-decoration-none link-secondary">Forgot
                                    password?</a>
                            </div>
                            <div class="d-flex mb-3">
                                <button type="submit" class="btn w-100 rounded-pill primary-button">Login</button>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-grow-1">
                                    <hr class="m-0">
                                </div>
                                <div class="flex-shrink px-2 text-muted">Atau masuk dengan</div>
                                <div class="flex-grow-1">
                                    <hr class="m-0">
                                </div>
                            </div>
                            <div class="text-center mb-3">
                                <a href="/auth/google"
                                    class="btn border btn-outline-light text-secondary rounded-pill d-flex align-items-center justify-content-center">
                                    <img src="{{ asset('asset/images/google.png')}}" alt="Google"
                                        style="width: 20px; height: 20px;" class="me-2">
                                    Google
                                </a>
                            </div>
                            <div class="text-center">
                                <p class="text-muted">Belum punya akun? <a href="{{ route('register')}}"
                                        class="link-primary text-decoration-none">Registrasi</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtHqNGTfbWV9yUzIz5O0F3RRrwJZS17tM0GcMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @if ($errors->any())
    <script>
        @if ($errors->any())
            let errors = @json($errors->all());
            errors.forEach(error => {
                toastr.error(error);
            });
        @endif
    </script>
    @endif
    @if (session('error'))
    <script>
        toastr.error("{{ session('error') }}");
    </script>
    @endif
</body>

</html>