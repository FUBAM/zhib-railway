<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'ZHIB')</title>

    {{-- Global Styles --}}
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    @stack('styles')

    {{-- 🔥 CSS MODAL (LANGSUNG DISINI AGAR PASTI JALAN) 🔥 --}}
    <style>
        /* Overlay Gelap */
        #authOverlay {
            display: none; /* Sembunyi default */
            position: fixed;
            top: 0; 
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9998; /* Sangat tinggi */
            backdrop-filter: blur(2px);
        }
        #authOverlay.active {
            display: block !important;
        }

        /* Modal Box */
        .auth-modal {
            display: none; /* Sembunyi default */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            z-index: 9999; /* Di atas overlay */
            width: 90%;
            max-width: 400px;
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        /* Class helper untuk menampilkan */
        .auth-modal.active {
            display: block !important; /* Paksa Tampil */
            animation: fadeIn 0.3s ease;
            opacity: 1;
            transform: translate(-50%, -50%);
        }

        /* Hilangkan class hidden bawaan jika ada bentrok */
        .auth-modal.hidden {
            display: none !important;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translate(-50%, -60%); }
            to { opacity: 1; transform: translate(-50%, -50%); }
        }
    </style>
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