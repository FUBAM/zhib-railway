<aside class="sidebar">
    <div class="sidebar-header">
        <h2>ZHIB Admin</h2>
        <span>Regional DIY</span>
    </div>
    
    <ul class="sidebar-menu">
        {{-- Dashboard --}}
        <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fa-solid fa-border-all"></i> Dashboard
            </a>
        </li>

        {{-- Kelola Pembayaran --}}
        <li class="{{ Request::is('admin/pembayaran*') ? 'active' : '' }}">
            <a href="{{ route('admin.pembayaran') }}">
                <i class="fa-regular fa-credit-card"></i> Pembayaran
            </a>
        </li>

        {{-- Kelola Lomba --}}
        <li class="{{ Request::is('admin/lomba*') || Request::is('admin/kelola-lomba*') ? 'active' : '' }}">
            <a href="{{ route('admin.lomba') }}">
                <i class="fa-solid fa-trophy"></i> Lomba
            </a>
        </li>

        {{-- Kelola Komunitas --}}
        <li class="{{ Request::is('admin/komunitas*') ? 'active' : '' }}">
            <a href="{{ route('admin.komunitas') }}">
                <i class="fa-solid fa-user-group"></i> Komunitas
            </a>
        </li>

        {{-- Pusat Laporan --}}
        <li class="{{ Request::is('admin/laporan*') ? 'active' : '' }}">
            <a href="{{ route('admin.laporan') }}">
                <i class="fa-solid fa-triangle-exclamation"></i> Laporan
            </a>
        </li>

        {{-- Kelola Berita --}}
        <li class="{{ Request::is('admin/berita*') ? 'active' : '' }}">
            <a href="{{ route('admin.berita') }}">
                <i class="fa-regular fa-newspaper"></i> Berita
            </a>
        </li>

        <li class="spacer"></li>

        {{-- Logout --}}
        <li>
            <form action="{{ route('logout') }}" method="POST" style="width: 100%;">
                @csrf
                <button type="submit" class="logout-btn" style="background:none; border:none; width:100%; text-align:left; padding:12px 15px; font-family:inherit; font-size:16px; font-weight:500; display:flex; align-items:center; gap:12px; cursor:pointer; color: #ef4444;">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</aside>