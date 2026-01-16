
<div class="auth-modal" id="loginModal">
    <button class="close-btn" onclick="closeAuth()">×</button>
    <h2>Masuk</h2>

    @if($errors->has('email'))
    <div class="error-message" style="color:#b00020;margin-bottom:8px;">{{ $errors->first('email') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label>Username / Email</label>
        <input type="text" name="login" id="login" placeholder="Masukkan username atau email" required value="{{ old('login') }}">

        <label>Password</label>
        <input type="password" name="password" id="password" placeholder="Masukkan password" required>

        <div class="form-options">
            <label class="remember">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                Remember me
            </label>

            <a href="#" class="forgot-link" onclick="openForgot()">Lupa?</a>
        </div>

        <button type="submit" class="primary-btn">Masuk</button>
    </form>

    <p class="switch-text">
        Belum Punya Akun?
        <a href="#" onclick="switchToRegister()">Buat Akun</a>
    </p>
</div>