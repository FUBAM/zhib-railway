<footer>
    <div class="footer-container">

        {{-- BRAND & SOSIAL --}}
        <div class="footer-section">
            <h3 class="footer-brand">ZHIB</h3>

            <div class="footer-social">
                <a href="#" class="social-link" aria-label="Instagram">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8
                                 4 4 0 0 1 16 11.37z"></path>
                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                    </svg>
                </a>

                <a href="#" class="social-link" aria-label="Facebook">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7
                                 a1 1 0 0 1 1-1h3z"></path>
                    </svg>
                </a>

                <a href="#" class="social-link" aria-label="X / Twitter">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18.244 2.25h3.308l-7.227 8.26
                                 8.502 11.24H16.17l-5.214-6.817
                                 L4.99 21.75H1.68l7.73-8.835
                                 L1.254 2.25H8.08l4.713 6.231zm
                                 -1.161 17.52h1.833L7.084
                                 4.126H5.117z"></path>
                    </svg>
                </a>
            </div>
        </div>

        {{-- KOMUNITAS --}}
        <div class="footer-section">
            @guest
            <a href="#" class="footer-text-link" onclick="openLogin(); return false;">
                Komunitas Saya
            </a>
            <a href="#" class="footer-text-link" onclick="openLogin(); return false;">
                Cari Komunitas
            </a>
            @else
            <a href="{{ route('komunitas.my') }}" class="footer-text-link">
                Komunitas Saya
            </a>
            <a href="{{ route('komunitas.index') }}" class="footer-text-link">
                Cari Komunitas
            </a>
            @endguest
        </div>

        {{-- EVENT --}}
        <div class="footer-section">
            @guest
            <a href="#" class="footer-text-link" onclick="openLogin(); return false;">
                Riwayat Event
            </a>
            <a href="#" class="footer-text-link" onclick="openLogin(); return false;">
                Cari Event
            </a>
            @else
            <a href="{{ route('events.riwayat') }}" class="footer-text-link">
                Riwayat Event
            </a>
            <a href="{{ route('events.index') }}" class="footer-text-link">
                Cari Event
            </a>
            @endguest
        </div>

        {{-- INFORMASI --}}
        <div class="footer-section">
            <a href="{{ url('/tentang_kami') }}" class="footer-text-link">
                Tentang Kami
            </a>
        </div>

    </div>
</footer>