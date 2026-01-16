@extends('layouts.app')

@section('title', 'Cari Event & Lomba | ZHIB')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/event.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        .event-page {
            /* Sesuaikan angka 100px ini dengan tinggi Navbar Anda */
            padding-top: 120px; 
            min-height: 80vh; /* Agar footer tidak naik jika konten sedikit */
            background-color: #f9f9f9; /* Opsional: warna background abu muda */
        }

        /* Perbaikan kecil agar container search bar lebih rapi */
        .search-bar-wrapper {
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
    </style>
@endpush

@section('content')
<div class="event-page">
    <div class="event-container">

        {{-- SEARCH & FILTER SECTION --}}
        <div class="search-bar-wrapper">
            <button class="btn-filter" onclick="openFilter()">
                Filters <i class="fa-solid fa-sliders"></i>
            </button>
            <div class="search-input-group">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="searchEventInput" placeholder="Cari event, lomba, workshop..." onkeyup="searchEvent()">
            </div>
        </div>

        {{-- EVENT GRID (DYNAMIC FROM DATABASE) --}}
        <div class="event-grid" id="eventGrid">

            {{-- 🔥 LOOPING DATA DARI DATABASE 🔥 --}}
            @forelse($events as $event)
            
            <div class="event-card">
                <div class="card-img">
                    {{-- Logika Gambar: Jika ada poster pakai poster, jika tidak pakai default --}}
                    <img src="{{ $event->poster_url ? asset($event->poster_url) : asset('image/default-event.jpg') }}" 
                         alt="{{ $event->judul }}"
                         onerror="this.src='{{ asset('image/default-event.jpg') }}'">
                </div>

                <div class="card-content">
                    {{-- Judul Event --}}
                    <h3 class="event-title">{{ Str::limit($event->judul, 50) }}</h3>
                    
                    <div class="event-meta">
                        {{-- Tanggal Pelaksanaan --}}
                        <div class="meta-item">
                            <i class="fa-regular fa-calendar"></i> 
                            {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d F Y') }}
                        </div>
                        
                        {{-- Harga / Kategori --}}
                        <div class="meta-item">
                            <i class="fa-solid fa-tag"></i>
                            @if($event->berbayar)
                                Rp {{ number_format($event->harga, 0, ',', '.') }}
                            @else
                                Gratis
                            @endif
                        </div>
                    </div>

                    {{-- Tombol Detail --}}
                    <a href="{{ route('events.show', $event->id) }}" class="btn-detail-event" style="display:block; margin-top:10px; text-align:center; background:#000; color:#fff; padding:8px; border-radius:6px; text-decoration:none; font-size:14px;">
                        Lihat Detail
                    </a>
                </div>
            </div>

            @empty
            {{-- TAMPILAN JIKA TIDAK ADA EVENT --}}
            <div style="grid-column: 1 / -1; text-align: center; padding: 50px;">
                <img src="{{ asset('image/icon/empty.png') }}" alt="Kosong" style="width: 100px; opacity: 0.5; margin-bottom: 20px;">
                <p style="color: #666; font-weight: 600;">Belum ada event yang tersedia saat ini.</p>
            </div>
            @endforelse

        </div> 
    </div>

    {{-- FILTER MODAL POPUP --}}
    <div id="filterModal" class="modal-overlay">
        <div class="modal-box">
            
            {{-- Form Filter Mengarah ke Index --}}
            <form action="{{ route('events.index') }}" method="GET">
                
                <div class="modal-header">
                    <h3>Filter Event</h3>
                    <button type="button" class="close-btn" onclick="closeFilter()">&times;</button>
                </div>

                <div class="modal-body">
                    
                    {{-- 1. FILTER KATEGORI (Dinamis dari DB) --}}
                    <div class="filter-group">
                        <label class="filter-label">Kategori</label>
                        <div class="checkbox-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            @foreach($kategori_list as $kat)
                                <label style="font-size: 14px; display: flex; align-items: center; gap: 8px;">
                                    <input type="checkbox" name="kategori[]" value="{{ $kat->id }}"
                                        {{ in_array($kat->id, request('kategori', [])) ? 'checked' : '' }}>
                                    {{ $kat->nama }}
                                </label>
                            @endforeach
                            
                            {{-- Opsi Kategori Umum (Manual/Null) jika diperlukan --}}
                            {{-- 
                            <label>
                                <input type="checkbox" name="kategori_null" value="1"> Umum / Lainnya
                            </label> 
                            --}}
                        </div>
                    </div>

                    {{-- 2. FILTER WILAYAH / KOTA (Dinamis dari DB) --}}
                    <div class="filter-group">
                        <label class="filter-label">Wilayah (Kota)</label>
                        <select class="filter-select" name="kota[]">
                            <option value="">Semua Lokasi</option>
                            @foreach($kota_list as $kota)
                                <option value="{{ $kota->id }}" 
                                    {{ in_array($kota->id, request('kota', [])) ? 'selected' : '' }}>
                                    {{ $kota->nama }}
                                </option>
                            @endforeach
                        </select>
                        <small style="color: #666; font-size: 11px;">*Pilih kota pelaksanaan</small>
                    </div>

                </div>

                <div class="modal-footer">
                    {{-- Tombol Reset (Link ke halaman events bersih) --}}
                    <a href="{{ route('events.index') }}" class="btn-reset" style="text-decoration: none; padding: 10px 20px; color: #333; text-align: center;">
                        Reset
                    </a>
                    <button type="submit" class="btn-apply">Terapkan Filter</button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // 1. SEARCH FUNCTION (Client Side - Filter kartu yang sudah diload)
    function searchEvent() {
        let input = document.getElementById('searchEventInput').value.toLowerCase();
        let cards = document.getElementsByClassName('event-card');

        for (let i = 0; i < cards.length; i++) {
            let titleEl = cards[i].getElementsByClassName('event-title')[0];
            
            if (titleEl) {
                let title = titleEl.textContent.toLowerCase();
                // Tampilkan jika judul cocok dengan input search
                if (title.includes(input)) {
                    cards[i].style.display = ""; 
                } else {
                    cards[i].style.display = "none";
                }
            }
        }
    }

    // 2. MODAL LOGIC
    const filterModal = document.getElementById('filterModal');

    function openFilter() {
        if (filterModal) filterModal.classList.add('active');
    }

    function closeFilter() {
        if (filterModal) filterModal.classList.remove('active');
    }

    window.onclick = function(event) {
        if (event.target == filterModal) {
            closeFilter();
        }
    }
</script>
@endpush