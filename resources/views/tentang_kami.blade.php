@extends('layouts.app')

@extends('styles.tentang_kami')

@section('content')
<main class="about-page">
    <div class="about-container">

        {{-- SECTION: SIAPA KAMI --}}
        <section class="about-row">
            <div class="about-image">
                <img src="{{ asset('image/others/siapa_kami.jpg') }}" alt="Siapa Kami">
            </div>

            <div class="about-text">
                <h2>Siapa kami?</h2>
                <p>
                    Kami adalah sebuah platform komunitas dan forum internasional yang dirancang
                    untuk membantu individu menemukan teman, komunitas, dan event yang sesuai
                    dengan hobi serta ketertarikan mereka.
                </p>
                <p>
                    Platform ini hadir sebagai ruang aman bagi siapa saja—terutama bagi mereka
                    yang merasa sulit memulai interaksi sosial, memiliki kepribadian introvert,
                    atau tinggal di lingkungan yang kurang mendukung aktivitas komunitas.
                </p>
            </div>
        </section>

        {{-- SECTION: TUJUAN --}}
        <section class="about-row reverse">
            <div class="about-text">
                <h2>Tujuan kami!</h2>
                <p>
                    Kami adalah platform komunitas dan forum berbasis minat yang inklusif dan aman
                    bagi semua orang—terutama bagi mereka yang introvert dan butuh ruang aman.
                    Sistem ini dirancang tanpa tekanan komunikasi realtime, namun tetap mendorong
                    partisipasi melalui leveling dan achievement.
                </p>
            </div>

            <div class="about-image">
                <img src="{{ asset('image/others/tujuan_kami.jpg') }}" alt="Tujuan Kami">
            </div>
        </section>

        {{-- SECTION: MISI --}}
        <section class="about-row">
            <div class="about-image overlay-wrapper">
                <img src="{{ asset('image/others/misi.jpg') }}" alt="Misi">
                <h2 class="overlay-text">Misi</h2>
            </div>

            <div class="about-text">
                <p>
                    Menyediakan wadah komunitas dan forum terverifikasi yang ramah bagi semua
                    pengguna, khususnya individu yang kesulitan bersosialisasi, dengan fitur
                    komunitas, forum, dan event berbasis minat serta sistem apresiasi.
                </p>
            </div>
        </section>

        {{-- SECTION: VISI --}}
        <section class="about-row reverse">
            <div class="about-text">
                <p>
                    Menjadi platform komunitas berbasis minat yang inklusif dan nyaman, tempat
                    setiap hobi dapat menemukan komunitas yang sesuai dan membangun koneksi
                    secara alami tanpa tekanan interaksi realtime.
                </p>
            </div>

            <div class="about-image overlay-wrapper">
                <img src="{{ asset('image/others/visi.jpg') }}" alt="Visi">
                <h2 class="overlay-text">Visi</h2>
            </div>
        </section>

        {{-- SECTION: TIM --}}
        <section class="team-section">
            <h2 class="team-title">Tim Kami</h2>

            @php
              $team = [
                  ['name' => 'IHSAN ZUFAR', 'role' => 'UI / UX DESIGN', 'img' => 'zufar.png'],
                  ['name' => 'HABIB FARHAN', 'role' => 'FRONTEND', 'img' => 'habib.jpeg'],
                  ['name' => 'MUHAMMAD BASIRU', 'role' => 'BACKEND', 'img' => 'basir.jpg'],
                  ['name' => 'AFRIZAL IBNU AZIZ', 'role' => 'BACKEND', 'img' => 'ibnu.jpg'],
                  ['name' => 'RIDWAN NUR HERIYANTO', 'role' => 'AUDITOR', 'img' => 'ridwan.jpeg'],
                  ['name' => 'ENI SULISTYO RINI', 'role' => 'AUDITOR', 'img' => 'mbak.webp'],
              ];
            @endphp

            <div class="team-grid">
                @foreach($team as $member)
                    <div class="team-card">
                        <img src="{{ asset('image/tim/' . $member['img']) }}" alt="{{ $member['name'] }}">
                        <div class="team-info">
                            <h4>{{ $member['name'] }}</h4>
                            <span>{{ $member['role'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

        </section>

    </div>
</main>
@endsection
