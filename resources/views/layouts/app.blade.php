@extends('styles.landing')
<!-- @extends('styles.registrasi')
@extends('styles.reset') -->

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'ZHIB')</title>

</head>

<body>

    @if(!isset($noHeader))
        @include('partials.header')
    @endif

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    {{-- 🔥 STRUKTUR POPUP (WAJIB ADA DISINI) 🔥 --}}
    @guest
        <div id="authOverlay" onclick="closeAuth()"></div>
        
        @include('auth.login-form')
        @include('auth.register-form')
        @include('auth.forgot-form')
        @endguest

    <script>
        // --- 1. FUNGSI PEMBERSIH (Mencegah Background Nyangkut) ---
        function resetAuthUI() {
            const overlay = document.getElementById('authOverlay');
            const modals = document.querySelectorAll('.auth-modal');

            if (overlay) overlay.classList.remove('active');
            
            modals.forEach(modal => {
                modal.classList.remove('active');
                modal.style.display = ''; // Hapus style inline jika ada
            });
        }

        // --- 2. BUKA LOGIN ---
        function openLogin() {
            resetAuthUI(); // Bersihkan layar dulu
            
            const modal = document.getElementById('loginModal');
            const overlay = document.getElementById('authOverlay');

            if (modal && overlay) {
                setTimeout(() => {
                    overlay.classList.add('active');
                    modal.classList.add('active');
                }, 10);
            }
        }

        // --- 3. BUKA REGISTER ---
        function openRegister() {
            resetAuthUI(); 

            const modal = document.getElementById('registerModal');
            const overlay = document.getElementById('authOverlay');

            if (modal && overlay) {
                setTimeout(() => {
                    overlay.classList.add('active');
                    modal.classList.add('active');
                }, 10);
            }
        }

        // --- 4. BUKA LUPA PASSWORD ---
        function openForgot() {
            resetAuthUI(); 

            const modal = document.getElementById('forgotModal');
            const overlay = document.getElementById('authOverlay');

            if (modal && overlay) {
                setTimeout(() => {
                    overlay.classList.add('active');
                    modal.classList.add('active');
                }, 10);
            }
        }

        // --- 5. TUTUP SEMUA ---
        function closeAuth() {
            resetAuthUI();
        }

        // --- 6. PINDAH-PINDAH POPUP ---
        function switchToRegister() {
            openRegister(); // Karena sudah ada resetAuthUI di dalamnya, aman.
        }

        function switchToLogin() {
            openLogin();
        }
        
        // --- 7. GLOBAL LISTENER ---
        document.addEventListener('DOMContentLoaded', () => {
            // Tangkap klik dari Header/Footer (data-auth)
            const guestLinks = document.querySelectorAll('[data-auth]');
            guestLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    openLogin();
                });
            });
        });

    function goToResetPage() {
        // Cari input text pertama di dalam modal forgot
        // (Asumsi input username/email adalah input pertama)
        const input = document.querySelector('#forgotModal input[type="text"]');
        
        if (input && input.value.trim() === "") {
            alert("Silakan masukkan Email atau Username terlebih dahulu!");
            return; // Batalkan pindah halaman
        }

        // Jika ada isinya, baru pindah
        window.location.href = "{{ url('/reset-password') }}";
    }
    </script>

    @stack('scripts')

    <div id="blade-helpers" 
        data-register-errors="{{ $errors->hasAny(['username', 'email', 'password', 'terms']) ? '1' : '0' }}"
        style="display: none;">
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const helpers = document.getElementById('blade-helpers');
            
            // Cek jika ada error khusus register, buka popup register
            if (helpers && helpers.dataset.registerErrors === '1') {
                openRegister();
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const termsCheck = document.getElementById('terms-check');
            const btnRegister = document.getElementById('btn-register');

            if (termsCheck && btnRegister) {
                // Fungsi untuk update status tombol
                function toggleRegisterButton() {
                    if (termsCheck.checked) {
                        btnRegister.disabled = false;
                        btnRegister.style.opacity = '1';
                        btnRegister.style.cursor = 'pointer';
                    } else {
                        btnRegister.disabled = true;
                        btnRegister.style.opacity = '0.5';
                        btnRegister.style.cursor = 'not-allowed';
                    }
                }

                // Cek saat pertama kali load (untuk old input)
                toggleRegisterButton();

                // Cek setiap kali diklik
                termsCheck.addEventListener('change', toggleRegisterButton);
            }
        });
    </script>

</body>
</html>