 <div class="auth-modal" id="registerModal">
    <button class="close-btn" onclick="closeAuth()">×</button>
    <h2>Buat Akun</h2>

    {{-- Pesan Sukses --}}
    @if(session('status'))
        <div class="success-message" style="color:#00695c; background: #e0f2f1; padding: 10px; border-radius: 6px; margin-bottom:15px; text-align: center; font-size: 14px;">
            {{ session('status') }}
        </div>
    @endif

    {{-- 🔥 TAMBAHAN: Pesan Error Umum (Global) 🔥 --}}
    @if ($errors->any())
        <div class="alert-danger" style="color: #b00020; background: #ffebee; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <label>Username</label>
        <input type="text" name="username" id="username" placeholder="Username" required value="{{ old('username') }}" 
               style="{{ $errors->has('username') ? 'border: 1px solid red;' : '' }}">
        {{-- Error Username --}}
        @error('username')
            <div style="color: red; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
        @enderror

        <label>Email</label>
        <input type="email" name="email" id="email" placeholder="Email Aktif" required value="{{ old('email') }}"
               style="{{ $errors->has('email') ? 'border: 1px solid red;' : '' }}">
        {{-- Error Email --}}
        @error('email')
            <div style="color: red; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
        @enderror

        <label>Password</label>
        <input type="password" name="password" id="password" placeholder="Password (Min. 8 Karakter)" required
               style="{{ $errors->has('password') ? 'border: 1px solid red;' : '' }}">
        {{-- Error Password --}}
        @error('password')
            <div style="color: red; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
        @enderror

        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi Password" required>

        <div class="register-agree">
            {{-- Tambahkan id="terms-check" dan required --}}
            <input type="checkbox" name="terms" id="terms-check" {{ old('terms') ? 'checked' : '' }} required>
            <span class="switch-text">Saya setuju dengan <a href="/tentang_kami" target="_blank">Syarat dan Ketentuan</a></span>
        </div>
        {{-- Error Terms --}}
        @error('terms')
            <div style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</div>
        @enderror

        <button type="submit" id="btn-register" class="primary-btn" style="margin-top: 15px; opacity: 0.5; cursor: not-allowed;" disabled>
            Buat Akun
        </button>
    </form>

    <p class="switch-text">
        Sudah Punya Akun?
        <a href="#" onclick="switchToLogin()">Masuk</a>
    </p>
</div>