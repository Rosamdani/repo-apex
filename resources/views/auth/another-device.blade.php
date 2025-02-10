<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">

<x-head :title="'Perangkat Lain'" />

<body>

    <section class="auth bg-base d-flex flex-wrap">
        <div class="auth-left d-lg-block d-none">
            <div class="d-flex align-items-center flex-column h-100 justify-content-center">
                <img src="{{ setting('auth.login_image') ? asset('storage/' . setting('auth.login_image')) : asset('assets/images/auth/auth-img.png') }}"
                    alt="">
            </div>
        </div>
        <div class="auth-right px-12 d-flex flex-column justify-content-center">
            <div class="max-w-500-px mx-auto w-100">
                <div>
                    <a href="#" class="mb-40 max-w-135-px">
                        <img src="{{ setting('general.logo') ? asset('storage/' . setting('general.logo')) : asset('assets/images/logo.png') }}"
                            alt="">
                    </a>
                    <h4 class="mb-12">Maaf...</h4>
                </div>
                <div class="alert alert-danger mb-16">
                    <p class="mb-0">Anda telah melakukan login dari perangkat lain. Silakan logout dari perangkat lain
                        terlebih dahulu.</p>
                </div>

                <div class="w-100 table-responsive">
                    <table class="table bordered-table sm-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Aksi</th>
                                <th scope="col">Perangkat </th>
                                <th scope="col">Terakhir dilihat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sessions as $session)
                                <tr>
                                    <td class="text-center">
                                        <form action="{{ route('logoutSession') }}" method="post" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="session_id" value="{{ $session->id }}">
                                            <button type="submit"
                                                class="btn btn-outline-primary btn-sm">Logout</button>
                                        </form>
                                    </td>
                                    <td class=""
                                        style="white-space: nowrap; overflow: scroll; scrollbar-width: none; text-overflow: ellipsis;">
                                        <p>{{ $session->browser }} - {{ $session->device }} - {{ $session->os }}
                                            {{ $session->os_version }}</p>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($session->last_activity)->locale('id_ID')->translatedFormat('l,
                                                                                                                                                                                    F
                                                                                                                                                                                    j Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <x-script />

</body>

</html>
