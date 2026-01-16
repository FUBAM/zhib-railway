@extends('layouts.app')

@section('title', 'Registrasi Peserta - ' . $event->judul)

@push('styles')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/form-lomba.css') }}">
  <style>
    /* Fix Navbar tertutup */
    .form-area-wrapper { 
        padding-top: 0; 
    }
    .hero-section { 
        background-image: url("{{ asset('image/esport.jpg') }}"); 
    }
    .input-readonly {
        background-color: #eee;
        cursor: not-allowed;
        color: #555;
    }
    /* Sembunyikan field game jika bukan kategori game (bisa diatur via JS nanti) */
    .game-fields {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px dashed #ccc;
    }
  </style>
@endpush

@section('content')
<div class="form-area-wrapper">
    <section class="hero-section">
        <div class="hero-content">
            <h2>REGISTRASI INDIVIDU</h2>
            <p>Daftarkan dirimu untuk mengikuti <strong>{{ $event->judul }}</strong>.</p>
        </div>
    </section>

    <main class="form-container">
        <div class="form-card">
            
            <div class="form-title-box">
                <h3><i class="fa-solid fa-user-pen"></i> FORMULIR PENDAFTARAN</h3>
                <p>Pastikan data diri Anda benar sebelum melanjutkan ke pembayaran.</p>
            </div>

            {{-- 🔥 FORM ACTION MENGARAH KE CONTROLLER 🔥 --}}
            <form action="{{ route('events.storeRegistration', $event->id) }}" method="POST">
                @csrf

                {{-- INFORMASI DASAR (Otomatis dari Profil User) --}}
                <div class="form-group">
                    <label>Username</label>
                    <div class="input-with-icon">
                        <i class="fa-regular fa-user"></i>
                        {{-- Readonly karena mengambil dari akun --}}
                        <input type="text" value="{{ Auth::user()->nama }}" class="input-readonly" readonly>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <div class="input-with-icon">
                            <i class="fa-regular fa-envelope"></i>
                            <input type="email" value="{{ Auth::user()->email }}" class="input-readonly" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nomor WhatsApp Aktif</label>
                        <div class="input-with-icon">
                            <i class="fa-solid fa-mobile-screen"></i>
                            {{-- Bisa diedit jika user ingin nomor yang berbeda untuk lomba --}}
                            <input type="text" name="no_wa" placeholder="081234567890" required value="{{ Auth::user()->no_telepon }}">
                        </div>
                    </div>
                </div>

                {{-- INFORMASI GAME (Opsional: Hanya tampil jika relevan) --}}
                <div class="game-fields">
                    <h4 style="font-size:14px; margin-bottom:15px; font-weight:700;">
                        <i class="fa-solid fa-gamepad"></i> Detail Akun Game (Opsional)
                    </h4>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nickname In-Game</label>
                            <div class="input-with-icon">
                                <i class="fa-solid fa-tag"></i>
                                <input type="text" name="nickname" placeholder="Contoh: RRQ Lemon">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>ID Akun / Server</label>
                            <div class="input-with-icon">
                                <i class="fa-solid fa-hashtag"></i>
                                <input type="text" name="game_id" placeholder="Contoh: 123456 (1001)">
                            </div>
                        </div>
                    </div>
                    <small style="color:#666; font-size:12px;">*Kosongkan jika event ini bukan kompetisi game.</small>
                </div>

                <div class="checkbox-group" style="margin-top: 30px;">
                    <input type="checkbox" id="agree" required>
                    <label for="agree">Saya menyetujui syarat dan ketentuan.<br>Data yang saya kirimkan adalah benar.</label>
                </div>

                {{-- SUBMIT BUTTON --}}
                <button type="submit" class="btn-submit">LANJUT KE PEMBAYARAN</button>
            </form>
        </div>
    </main>
</div>
@endsection