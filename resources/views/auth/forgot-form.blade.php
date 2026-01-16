<div class="auth-modal" id="forgotModal">
    <button class="close-btn" onclick="closeAuth()">×</button>

    <h2>Lupa sandi?</h2>

    <img src="{{ asset('image/icon/lupasandi.png') }}" alt="Lupa Sandi" class="forgot-icon">

    <p class="forgot-desc">
        Silahkan masukkan Username atau Email Anda dan kami akan
        mengirimkan tautan untuk masuk ke akun anda semula
    </p>

    <input type="text" placeholder="Masukkan Username/Email Anda">

    <button class="primary-btn" onclick="goToResetPage()">
        Kirim Tautan
    </button>

    <div class="divider">ATAU</div>

    <a href="#" onclick="switchToRegister()">Buat Akun Baru</a>
</div>