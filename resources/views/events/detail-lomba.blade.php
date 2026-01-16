@extends('layouts.app')

{{-- Judul Tab Browser --}}
@section('title', $event->judul . ' | ZHIB')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/event.css') }}">
    {{-- Pastikan file ini ada, atau gabungkan stylenya --}}
    <link rel="stylesheet" href="{{ asset('css/detail-lomba.css') }}"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
    .detail-page {
        padding-top: 120px; /* Samakan dengan yang di atas */
    }
</style>
@endpush

@section('content')
<div class="detail-page">
    <div class="detail-container">
        
        {{-- 1. GAMBAR POSTER (Dinamis) --}}
        <div class="poster-wrapper">
            {{-- 🔥 FIX INFINITE LOOP: Tambahkan this.onerror=null 🔥 --}}
            <img src="{{ $event->poster_url ? asset($event->poster_url) : asset('image/default-event.jpg') }}" 
                 alt="{{ $event->judul }}" 
                 class="poster-img"
                 onerror="this.onerror=null; this.src='{{ asset('image/default-event.jpg') }}'">
        </div>

        {{-- 2. JUDUL EVENT --}}
        <h1 class="detail-title">{{ $event->judul }}</h1>

        <div class="detail-content">
            
            {{-- 3. DESKRIPSI (Support Enter/Baris Baru) --}}
            <div class="description-text" style="margin-bottom: 30px; line-height: 1.6; color: #333;">
                {!! nl2br(e($event->deskripsi)) !!}
            </div>

            {{-- 4. INFO WAKTU & LOKASI --}}
            <div class="info-group" style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 30px;">
                
                {{-- Waktu --}}
                <div class="info-row" style="display: flex; align-items: center; gap: 10px;">
                    <i class="fa-regular fa-calendar" style="width: 20px; text-align: center;"></i>
                    <span>{{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('l, d F Y') }}</span>
                </div>
                
                <div class="info-row" style="display: flex; align-items: center; gap: 10px;">
                    <i class="fa-regular fa-clock" style="width: 20px; text-align: center;"></i>
                    <span>{{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }} WIB</span>
                </div>

                {{-- Kategori --}}
                <div class="info-row" style="display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-layer-group" style="width: 20px; text-align: center;"></i>
                    {{-- Gunakan ?-> untuk relasi agar aman jika data kosong --}}
                    <span style="text-transform: capitalize;">Kategori: {{ $event->kategori?->nama ?? 'Umum' }}</span>
                </div>

                <div class="info-row" style="display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-city" style="width: 20px; text-align: center;"></i>
                    {{-- 🔥 FIX ERROR: Pakai ?-> karena kota_id bisa null 🔥 --}}
                    <span style="text-transform: capitalize;">Wilayah: {{ $event->kota?->nama ?? 'Online / Lokasi Lain' }}</span>
                </div>
            </div>

            {{-- 5. HARGA --}}
            <p class="price-text" style="font-size: 20px; font-weight: 800; margin-bottom: 20px;">
                Biaya Pendaftaran: 
                @if($event->berbayar)
                    <span style="color: #000;">Rp {{ number_format($event->harga, 0, ',', '.') }}</span>
                @else
                    <span style="color: #00c853;">GRATIS</span>
                @endif
            </p>

            {{-- 6. TOMBOL DAFTAR (Dengan Logika Cek Status) --}}
            @auth
                @php
                    // Cek apakah user sudah terdaftar di event ini
                    $isRegistered = \App\Models\PesertaKegiatan::where('user_id', auth()->id())
                                    ->where('events_id', $event->id)
                                    ->exists();
                @endphp

                @if($isRegistered)
                    <button class="btn-daftar-hitam" style="background: #ccc; cursor: not-allowed; border:none; color: #666;" disabled>
                        Sudah Terdaftar
                    </button>
                @else
                    {{-- Link ke Form Pendaftaran --}}
                    <button class="btn-daftar-hitam" onclick="window.location.href='{{ route('events.register', $event->id) }}'">
                        Daftar Sekarang
                    </button>
                @endif
            @else
                {{-- Jika belum login, buka popup login --}}
                <button class="btn-daftar-hitam" onclick="openLogin()">
                    Login untuk Daftar
                </button>
            @endauth

        </div>

    </div>
</div>
@endsection