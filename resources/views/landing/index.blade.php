@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endsection

@section('content')

<section class="hero">
    <img src="{{ asset('image/img (8).jpg') }}" alt="">
    <div class="hero-overlay">
        <h1>Temukan Teman Satu Frekuensi</h1>
        <p>Ribuan komunitas dan event seru menantimu.</p>
    </div>
</section>

<section class="section">
    <div class="section-container">
        <h2 class="section-title">REKOMENDASI BERITA</h2>

        <div class="grid-3">
            @forelse($berita as $item)
            <a href="{{ route('berita.detail', $item->id) }}" class="news-card" @guest
                onclick="openLogin(); return false;" @endguest>

                <div class="news-image-frame">
                    <img src="{{ asset($item->gambar_url ?? 'image/default-news.jpg') }}">
                </div>

                <p style="text-align: center;">{{ Str::limit($item->judul, 80) }}</p>
            </a>
            @empty
            <p style="padding-left: 550px; text-align: center;">Tidak ada berita</p>
            @endforelse
        </div>
    </div>
</section>

<section class="communities" id="event">
    <h2>PILIHAN EVENT</h2>
    <p class="communities-subtitle">
        Daftar Event terpilih untuk menambah<br>
        pengalaman, relasi, dan koleksi lencana
    </p>

    <div class="slider-container">
        
        {{-- 🔥 TOMBOL PREV (Dikembalikan) 🔥 --}}
        <button class="slider-btn prev-btn" id="event-prev">❮</button>

        <div class="scroll-wrapper" id="event-list">
            @foreach($events as $event)
            <div class="community-card">
                <div class="event-image">
                    <img src="{{ asset($event->poster_url ?? 'image/default-event.jpg') }}">
                </div>

                <div class="card-content">
                    <h3>{{ Str::limit($event->judul, 60) }}</h3>

                    <a href="{{ route('events.show', $event->id) }}" class="card-link" @guest
                        onclick="openLogin(); return false;" @endguest>
                        Lihat Detail &gt;
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        {{-- 🔥 TOMBOL NEXT (Dikembalikan) 🔥 --}}
        <button class="slider-btn next-btn" id="event-next">❯</button>

    </div>
</section>

<section class="section">
    <div class="section-container">
        <h2 class="section-title">HALL OF FAME</h2>
        <h6 class="section-subtitle">
            Mereka yang telah mengukir jejak terbaik di bulan ini
        </h6>
        @include('partials.hall-of-fame', ['users' => $hallOfFame])
    </div>
</section>

@endsection

{{-- 🔥 UBAH DARI @SECTION KE @PUSH AGAR TERBACA DI APP.BLADE.PHP 🔥 --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Inisialisasi Slider
        initCustomSlider('event-list', 'event-prev', 'event-next', 300);
    });

    function initCustomSlider(containerId, prevBtnId, nextBtnId, scrollAmount) {
        const container = document.getElementById(containerId);
        const prevBtn = document.getElementById(prevBtnId);
        const nextBtn = document.getElementById(nextBtnId);

        // Jika tombol tidak ada (seperti sebelumnya), script berhenti disini
        if (!container || !prevBtn || !nextBtn) return;

        nextBtn.addEventListener('click', (e) => {
            e.preventDefault();
            container.scrollLeft += scrollAmount;
        });

        prevBtn.addEventListener('click', (e) => {
            e.preventDefault();
            container.scrollLeft -= scrollAmount;
        });
    }
</script>
@endpush