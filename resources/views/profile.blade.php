@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@php
    $defaultAvatar = 'image/avatar/avatar-default.jpg';
@endphp

@section('content')
<main class="profile-page">

    {{-- =========================
        GUEST PROMPT
    ========================== --}}
    @guest
        @if(!request()->get('user'))
        <section class="profile-card guest-prompt">
            <div class="guest-msg">
                <h2>Anda belum masuk</h2>
                <p>Silakan masuk atau daftar untuk melihat dan mengedit profil Anda.</p>
                <div class="guest-actions">
                    <a href="#" onclick="openLogin()" class="btn btn-primary">Masuk</a>
                    <a href="#" onclick="openRegister()" class="btn btn-secondary">Daftar</a>
                </div>
            </div>
        </section>
        @endif
    @endguest

    {{-- =========================
        PROFIL SENDIRI (VIEW)
    ========================== --}}
    @auth
    @php $user = auth()->user(); @endphp

    <section id="profileViewOwn" class="profile-card">

        <div class="profile-header-own">

            <div class="profile-avatar-section">
                <img class="profile-avatar"
                     src="{{ asset($user->foto_profil_url ?? $defaultAvatar) }}"
                     alt="{{ $user->nama }}">

                <div class="profile-level-box">
                    <span class="level-text">LVL. {{ $user->level_terkini ?? 1 }}</span>
                    <div class="level-bar">
                        <div class="level-progress"></div>
                    </div>
                </div>
            </div>

            <div class="profile-info-own">
                <h2>{{ $user->nama }}</h2>

                <div class="profile-badges">
                    @forelse($user->badges as $badge)
                        <img src="{{ asset($badge->image_url) }}" alt="Badge" class="badge">
                    @empty
                        <span class="text-muted">Belum ada badge</span>
                    @endforelse
                </div>

                <p class="profile-bio">
                    {{ $user->bio ?: 'Belum ada bio' }}
                </p>

                <p class="join-date-text">
                    <strong>Bergabung Sejak:</strong>
                    {{ $user->created_at->translatedFormat('d F Y') }}
                </p>
            </div>

            {{-- 🔥 PERBAIKAN 1: Tambahkan id="btnEditProfile" 🔥 --}}
            <a href="{{ route('profile.show', ['edit' => true]) }}"
            id="btnEditProfile" 
            class="btn-edit">
                Edit Profil
            </a>

        </div>

        <div class="profile-content-full">
            <div class="profile-box-transparent">
                <h3 class="center-title">Aktivitas Terakhir</h3>
                <div class="activity-grid">
                    <div class="profile-content-full">
                        <div class="profile-box-transparent">
                            @if(isset($recentEvents) && $recentEvents->count() > 0)
                                <div class="activity-grid">
                                    @foreach($recentEvents as $event)
                                        <div class="activity-item">
                                            <img
                                                src="{{ asset($event->poster_url ?? 'image/default-event.jpg') }}"
                                                alt="{{ $event->judul }}"
                                            >
                                            <p class="activity-title">
                                                {{ \Illuminate\Support\Str::limit($event->judul, 30) }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="activity-empty">
                                    Belum ada aktivitas event yang diikuti
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    @endauth

    {{-- =========================
        PROFIL SENDIRI (EDIT)
    ========================== --}}
    {{-- =========================
        PROFIL SENDIRI (EDIT)
    ========================== --}}
    @auth
    <section id="profileEdit" class="profile-card hidden">
        
        {{-- 🔥 1. BUNGKUS DENGAN FORM KE ROUTE UPDATE 🔥 --}}
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <h2 class="section-title-center">Edit Profil</h2>

            <div class="edit-profile-top">

                {{-- Bagian Foto Profil --}}
                <div class="edit-avatar">
                    <img src="{{ asset($user->foto_profil_url ?? $defaultAvatar) }}" alt="Avatar" id="previewAvatar">
                    
                    {{-- Input file tersembunyi --}}
                    <input type="file" name="foto_profil" id="inputAvatar" style="display: none;" accept="image/*">
                    <button type="button" class="btn-change-photo" onclick="document.getElementById('inputAvatar').click()">
                        Ganti Foto
                    </button>
                </div>

                {{-- Bagian Input Data --}}
                <div class="edit-form">
                    
                    {{-- Row 1: Username & Email --}}
                    <div class="form-row">
                        <div class="form-group">
                            <label>Username</label>
                            {{-- Mapping ke database 'nama' --}}
                            <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" required>
                            @error('nama') <small style="color:red">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email') <small style="color:red">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    {{-- Row 2: Bio (Full Width) --}}
                    <div class="form-group">
                        <label>Bio / Tentang Saya</label>
                        <textarea name="bio" placeholder="Ceritakan sedikit tentang dirimu...">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio') <small style="color:red">{{ $message }}</small> @enderror
                    </div>

                </div>
            </div>

            <h2 class="section-title-center">Atur Password</h2>
            <p style="text-align: center; font-size: 13px; color: #666; margin-top: -15px; margin-bottom: 20px;">
                Kosongkan jika tidak ingin mengubah password
            </p>

            <div class="edit-password">
                <div class="password-group">
                    <label>Password Saat Ini (Wajib jika ganti password)</label>
                    <input type="password" name="current_password">
                    @error('current_password') <small style="color:red">{{ $message }}</small> @enderror
                </div>
                <div class="password-group">
                    <label>Password Baru</label>
                    <input type="password" name="password">
                    @error('password') <small style="color:red">{{ $message }}</small> @enderror
                </div>
                <div class="password-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation">
                </div>
            </div>

            <div class="edit-actions">
                <button type="button" id="btnCancel" class="btn-cancel">Batal</button>
                <button type="submit" class="btn-save">Simpan</button>
            </div>
        
        </form>

    </section>
    @endauth

    {{-- =========================
        PROFIL ORANG LAIN
    ========================== --}}
    @if(isset($viewedUser))
    <section id="profileViewOther" class="profile-card">

        <h2 class="section-title-center">Profil Pengguna</h2>

        @if($viewedUser)
        <div class="profile-header-other">
            <div class="profile-avatar-section-center">
                <img class="profile-avatar"
                     src="{{ asset($viewedUser->foto_profil_url ?? $defaultAvatar) }}"
                     alt="{{ $viewedUser->nama }}">
            </div>

            <div class="profile-info-center">
                <h2>{{ $viewedUser->nama }}</h2>

                <p class="profile-bio-center">
                    {{ $viewedUser->bio ?? 'Belum ada bio' }}
                </p>

                <div class="badge-title">Badge & Pencapaian</div>
                <div class="profile-badges-center">
                    @forelse($viewedUser->badges as $badge)
                        <img src="{{ asset($badge->image_url) }}" class="badge">
                    @empty
                        <span class="text-muted">Belum ada badge</span>
                    @endforelse
                </div>

                <div class="join-date">
                    Bergabung sejak {{ $viewedUser->created_at->translatedFormat('F Y') }}
                </div>
            </div>
        </div>
        @else
        <p class="text-center">Profil tidak ditemukan.</p>
        @endif

    </section>
    @endif

</main>
@endsection

@push('scripts')
<script> 
document.addEventListener('DOMContentLoaded', function () {

    const profileViewOwn = document.getElementById('profileViewOwn');
    const profileEdit = document.getElementById('profileEdit');
    const profileViewOther = document.getElementById('profileViewOther');

    const btnEdit = document.getElementById('btnEditProfile');
    const btnCancel = document.getElementById('btnCancel');

    const params = new URLSearchParams(window.location.search);
    const viewedUser = params.get('user');
    
    // 🔥 PERBAIKAN 2: Cek apakah nilainya 'true' ATAU '1' 🔥
    const isEdit = params.get('edit') === 'true' || params.get('edit') === '1';

    const inputAvatar = document.getElementById('inputAvatar');
    const previewAvatar = document.getElementById('previewAvatar');
    // ======================
    // LOGIKA TAMPILAN
    // ======================
    
    // 1. Jika melihat user lain (Priority 1)
    if (viewedUser) {
        if (profileViewOwn) profileViewOwn.classList.add('hidden');
        if (profileEdit) profileEdit.classList.add('hidden');
        if (profileViewOther) profileViewOther.classList.remove('hidden');
    }
    // 2. Jika Mode Edit Profil Sendiri (Priority 2)
    else if (isEdit) {
        if (profileViewOther) profileViewOther.classList.add('hidden');
        if (profileViewOwn) profileViewOwn.classList.add('hidden'); // Sembunyikan view
        if (profileEdit) profileEdit.classList.remove('hidden');    // Munculkan form edit
    } 
    // 3. Default: Lihat Profil Sendiri
    else {
        if (profileViewOther) profileViewOther.classList.add('hidden');
        if (profileEdit) profileEdit.classList.add('hidden');
        if (profileViewOwn) profileViewOwn.classList.remove('hidden');
    }

    // Preview Avatar saat dipilih

    if(inputAvatar && previewAvatar) {
        inputAvatar.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewAvatar.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }
    // ======================
    // EVENT LISTENERS
    // ======================
    
    if (btnEdit) {
        btnEdit.addEventListener('click', function (e) {
            // Kita biarkan refresh halaman agar URL terupdate bersih
            // Tapi kita pastikan URL tujuannya benar
            window.location.href = "{{ url('/profile') }}?edit=1";
        });
    }

    if (btnCancel) {
        btnCancel.addEventListener('click', function (e) {
            e.preventDefault(); 
            // Kembali ke profil biasa (hapus query param)
            window.location.href = "{{ url('/profile') }}"; 
        });
    }

});
</script>
@endpush