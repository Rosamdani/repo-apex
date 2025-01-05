<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-2" style="min-height: fit-content;">
    <div class="container mb-0 d-flex flex-column align-items-start">
        <div class="w-100 d-flex justify-content-between align-items-center">
            <!-- Logo dan Input Search -->
            <div class="d-flex align-items-center gap-3">
                <!-- Logo -->
                <img class="d-none d-md-block" src="{{ asset('storage/' . setting('general.logo')) }}" alt="app_logo"
                    style="height: 40px;">

                <!-- Input Search -->
                <div class="rounded-pill border form-search d-flex align-items-center"
                    style="background-color: transparent; border-color: transparent; overflow: hidden;">
                    <input type="text" class="form-control border-0 shadow-none" placeholder="Search"
                        style="box-shadow: none;">
                    <button type="button"
                        class="me-1 border-0 primary-button rounded-circle d-flex align-items-center justify-content-center shadow-none"
                        style="outline: none; box-shadow: none; width: 30px; height: 30px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </div>
            </div>
            <!-- Profile -->
            <div class="dropdown">
                <button class="dropdown-toggle bg-transparent border-0 d-flex align-items-center gap-2" type="button"
                    id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <span
                        class="avatar rounded-circle secondary-background rounded-3 d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px;">
                        <span class="text-white fs-5">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </span>
                    <div class="d-flex flex-column align-items-start justify-content-between h-100">
                        <span class="d-none fw-bold d-lg-block">{{ auth()->user()->name }}</span>
                        <span class="d-none d-lg-block text-muted">User</span>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item" type="submit">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Navbar --}}
        <ul class="d-flex gap-4 mb-0 px-0 mt-2">
            <li class="list-unstyled"><a href="{{ route('index') }}"
                    class="link-secondary {{ request()->routeIs('index') ? 'active' : '' }} text-muted px-0 text-decoration-none">Home</a>
            </li>
            <li class="list-unstyled"><a href="{{ route('tryouts.mytryout') }}"
                    class="link-secondary {{ request()->routeIs('tryouts.mytryout') ? 'active' : '' }} text-muted px-0 text-decoration-none">My
                    Tryouts</a>
            </li>
        </ul>

    </div>
</nav>
