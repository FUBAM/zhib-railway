<header class="navbar @yield('navbar-class')">
    <div class="navbar-container">

        {{-- LEFT NAV --}}
        <nav class="nav-left">
            @guest
            <a href="#" data-auth>Home</a>

            <div class="nav-dropdown">
                <button class="nav-link" type="button">
                    Komunitas <span class="arrow">▾</span>
                </button>
                <div class="dropdown-menu">
                    <a href="#" data-auth>Komunitas Saya</a>
                    <a href="#" data-auth>Cari Komunitas</a>
                </div>
            </div>

            <div class="nav-dropdown">
                <button class="nav-link" type="button">
                    Event <span class="arrow">▾</span>
                </button>
                <div class="dropdown-menu">
                    <a href="#" data-auth>Cari Event</a>
                    <a href="#" data-auth>Riwayat Event</a>
                </div>
            </div>
            @else
            <a href="{{ route('home') }}">Home</a>

            <div class="nav-dropdown">
                <button class="nav-link" type="button">
                    Komunitas <span class="arrow">▾</span>
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('komunitas.my') }}">Komunitas Saya</a>
                    <a href="{{ route('komunitas.index') }}">Cari Komunitas</a>
                </div>
            </div>

            <div class="nav-dropdown">
                <button class="nav-link" type="button">
                    Event <span class="arrow">▾</span>
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('events.index') }}">Cari Event</a>
                    <a href="{{ route('events.riwayat') }}">Riwayat Event</a>
                </div>
            </div>
            @endguest
        </nav>

        {{-- LOGO --}}
        <div class="logo">ZHIB</div>

        {{-- RIGHT NAV --}}
        <div class="nav-right">
            @guest
            <a href="#" onclick="openLogin()">Masuk</a>
            <span>|</span>
            <a href="#" onclick="openRegister()">Daftar</a>
            @else
            <div class="profile-dropdown" id="profileDropdown">
                <button class="profile-navbar" id="profileToggle">
                    <div class="profile-text">
                        <div class="profile-name">{{ auth()->user()->nama }}</div>
                        <div class="profile-level">
                            LVL. {{ auth()->user()->level_terkini ?? 1 }}
                        </div>
                    </div>

                    <img src="{{ auth()->user()->foto_profil_url
                                    ? asset(auth()->user()->foto_profil_url)
                                    : asset('image/avatar/avatar-default.jpg') }}" class="profile-avatar" alt="Profile">
                </button>

                <div class="profile-menu">
                    <a href="{{ route('profile.show') }}">Profil</a>
                    <hr>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
            @endguest
        </div>

    </div>
</header>

@push('scripts')
<script>
document.addEventListener('click', function(e) {
    const toggle = e.target.closest('#profileToggle');
    if (toggle) {
        e.stopPropagation();
        toggle.closest('.profile-dropdown')?.classList.toggle('active');
        return;
    }

    document.querySelectorAll('.profile-dropdown.active').forEach(function(el) {
        if (!el.contains(e.target)) el.classList.remove('active');
    });
});
</script>
@endpush