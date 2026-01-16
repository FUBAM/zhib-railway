@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
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

            <a href="{{ url('/detail-berita') }}" class="news-card">
                <div class="news-image-frame">
                    <img src="{{ asset('image/img (9).jpg') }}">
                </div>
                <p>Turnamen futsal Open Sumatera dengan total hadiah jutaan rupiah.</p>
            </a>

            <a href="{{ url('/detail-berita') }}" class="news-card">
                <div class="news-image-frame">
                    <img src="{{ asset('image/img (1).jpg') }}">
                </div>
                <p>Turnamen futsal Open Sumatera dengan total hadiah jutaan rupiah.</p>
            </a>

            <a href="{{ url('/detail-berita') }}" class="news-card">
                <div class="news-image-frame">
                    <img src="{{ asset('image/img (10).jpg') }}">
                </div>
                <p>Turnamen futsal Open Sumatera dengan total hadiah jutaan rupiah.</p>
            </a>

            <a href="{{ url('/detail-berita') }}" class="news-card">
                <div class="news-image-frame">
                    <img src="{{ asset('image/img (9).jpg') }}">
                </div>
                <p>Turnamen futsal Open Sumatera dengan total hadiah jutaan rupiah.</p>
            </a>

            <a href="{{ url('/detail-berita') }}" class="news-card">
                <div class="news-image-frame">
                    <img src="{{ asset('image/img (5).jpg') }}">
                </div>
                <p>Turnamen futsal Open Sumatera dengan total hadiah jutaan rupiah.</p>
            </a>

            <a href="{{ url('/detail-berita') }}" class="news-card">
                <div class="news-image-frame">
                    <img src="{{ asset('image/img (10).jpg') }}">
                </div>
                <p>Turnamen futsal Open Sumatera dengan total hadiah jutaan rupiah.</p>
            </a>

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
        <button class="slider-btn prev-btn" id="event-prev">❮</button>

        <div class="scroll-wrapper" id="event-list">

            <div class="community-card">
                <div class="event-image">
                    <img src="{{ asset('image/img (1).jpg') }}" alt="Poster Event">
                </div>
                <div class="card-content">
                    <h3>Turnamen futsal Open Sumatera total hadiah jutaan rupiah.</h3>
                    <a href="#" class="card-link">Lihat Detail ></a>
                </div>
            </div>

            <div class="community-card">
                <div class="event-image">
                    <img src="{{ asset('image/img (2).jpg') }}" alt="Poster Event">
                </div>
                <div class="card-content">
                    <h3>Kompetisi Mobile Legends offline.</h3>
                    <a href="#" class="card-link">Lihat Detail ></a>
                </div>
            </div>

            <div class="community-card">
                <div class="event-image">
                    <img src="{{ asset('image/img (3).jpg') }}" alt="Poster Event">
                </div>
                <div class="card-content">
                    <h3>Marathon Jakarta–Bandung 2025.</h3>
                    <a href="#" class="card-link">Lihat Detail ></a>
                </div>
            </div>

            <div class="community-card">
                <div class="event-image">
                    <img src="{{ asset('image/img (4).jpg') }}" alt="Poster Event">
                </div>
                <div class="card-content">
                    <h3>Workshop fotografi untuk pemula.</h3>
                    <a href="#" class="card-link">Lihat Detail ></a>
                </div>
            </div>

            <div class="community-card">
                <div class="event-image">
                    <img src="{{ asset('image/img (1).jpg') }}" alt="Poster Event">
                </div>
                <div class="card-content">
                    <h3>Turnamen futsal Open Sumatera total hadiah jutaan rupiah.</h3>
                    <a href="#" class="card-link">Lihat Detail ></a>
                </div>
            </div>

            <div class="community-card">
                <div class="event-image">
                    <img src="{{ asset('image/img (2).jpg') }}" alt="Poster Event">
                </div>
                <div class="card-content">
                    <h3>Kompetisi Mobile Legends offline.</h3>
                    <a href="#" class="card-link">Lihat Detail ></a>
                </div>
            </div>

            <div class="community-card">
                <div class="event-image">
                    <img src="{{ asset('image/img (9).jpg') }}" alt="Poster Event">
                </div>
                <div class="card-content">
                    <h3>Marathon Jakarta–Bandung 2025.</h3>
                    <a href="#" class="card-link">Lihat Detail ></a>
                </div>
            </div>


            <button class="slider-btn next-btn" id="event-next">❯</button>
        </div>
</section>

<section class="section">
    <div class="section-container">
        <h2 class="section-title">HALL OF FAME</h2>
        <h6 class="section-subtitle">
            Mereka yang telah mengukir jejak terbaik di komunitas ini
        </h6>


        @include('partials.hall-of-fame', ['users' => $topUsers])

    </div>

    </div>
</section>

@endsection

@section('scripts')
<script>
    // Profile Dropdown handled by header partial (no duplicate handlers)
    // Header script will toggle #profileDropdown and close other nav dropdowns


    // Komunitas Dropdown
    const komunitasToggle = document.getElementById('komunitasToggle');
    const komunitasDropdown = document.getElementById('komunitasDropdown');

    if (komunitasToggle && komunitasDropdown) {
        komunitasToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            komunitasDropdown.classList.toggle('active');

            // Close other dropdowns
            if (profileDropdown) profileDropdown.classList.remove('active');
            if (eventDropdown) eventDropdown.classList.remove('active');
        });
    }

    // Event Dropdown
    const eventToggle = document.getElementById('eventToggle');
    const eventDropdown = document.getElementById('eventDropdown');

    if (eventToggle && eventDropdown) {
        eventToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            eventDropdown.classList.toggle('active');

            // Close other dropdowns
            if (profileDropdown) profileDropdown.classList.remove('active');
            if (komunitasDropdown) komunitasDropdown.classList.remove('active');
        });
    }

    // Close all dropdowns when clicking outside
    document.addEventListener('click', () => {
        if (profileDropdown) profileDropdown.classList.remove('active');
        if (komunitasDropdown) komunitasDropdown.classList.remove('active');
        if (eventDropdown) eventDropdown.classList.remove('active');
    });

    // Prevent dropdown from closing when clicking inside menu
    document.querySelectorAll('.nav-dropdown-menu, .profile-menu').forEach(menu => {
        menu.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    });
</script>
@endsection