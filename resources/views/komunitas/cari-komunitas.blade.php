@extends('layouts.app')

@section('title', 'Cari Komunitas | ZHIB')

@push('styles')
{{-- CSS Library --}}
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
<link rel="stylesheet" href="{{ asset('css/cari-komunitas.css') }}"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    /* Fix Navbar tertutup */
    .comm-page {
        padding-top: 120px;
        min-height: 80vh;
        background-color: #f9f9f9;
        padding-bottom: 50px;
    }

    /* Styling Empty State */
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 50px;
        color: #777;
    }

    /* FIX CSS MODAL */
    .modal-overlay {
        z-index: 99999 !important; 
        display: none; 
    }

    .modal-overlay.active {
        display: flex !important;
    }
</style>
@endpush

@section('content')
<main class="comm-page">
    <div class="comm-container">

        {{-- SEARCH & FILTER BAR --}}
        <div class="search-bar-wrapper">
            <button class="btn-filter" type="button" onclick="openFilterModal()">
                <i class="fa-solid fa-sliders"></i> Filters
            </button>

            <div class="search-input-group">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="search" id="searchInput" placeholder="Cari komunitas..." 
                       onkeyup="searchCommunity()" 
                       onsearch="searchCommunity()">
            </div>
        </div>

        {{-- LIST KOMUNITAS --}}
        <div class="community-list" id="communityList">

            @forelse($komunitas as $k)
                <div class="comm-card" 
                     {{-- PENTING: Data Attribute menggunakan SLUG agar cocok dengan checkbox filter --}}
                     data-kota="{{ Str::slug($k->kota?->nama ?? '') }}" 
                     data-cat="{{ Str::slug($k->kategori?->nama ?? '') }}">
                    
                    <div class="comm-img">
                        <img src="{{ $k->logo_url ? asset($k->logo_url) : asset('image/komunitas/komunitas-default.jpg') }}" 
                             alt="{{ $k->nama }}"
                             onerror="this.onerror=null; this.src='{{ asset('image/default-community.jpg') }}'">
                    </div>

                    <div class="comm-content">
                        <h2 class="comm-title">{{ $k->nama }}</h2>
                        <p class="comm-desc">
                            {{ Str::limit($k->deskripsi, 120) }}
                        </p>
                        
                        <div class="comm-meta" style="margin-top: 10px; font-size: 12px; color: #666;">
                            <span class="comm-stat"><i class="fa-solid fa-users"></i> {{ $k->jumlah_anggota ?? 0 }} Anggota</span>
                            @if($k->kota)
                                <span style="margin-left: 10px;"><i class="fa-solid fa-location-dot"></i> {{ $k->kota->nama }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="comm-action">
                        {{-- Tombol Join --}}
                        <button class="btn-join" type="button"
                            onclick="openJoinModal('{{ addslashes($k->nama) }}', {{ $k->id }})">
                            Bergabung
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <img src="{{ asset('image/icon/empty.png') }}" alt="Kosong" style="width: 80px; opacity: 0.5; margin-bottom: 15px;">
                    <p>Belum ada komunitas yang tersedia.</p>
                </div>
            @endforelse

        </div>

    </div>
</main>

{{-- === MODAL FILTER (DINAMIS DARI DB) === --}}
<div class="modal-overlay" id="filterModal">
    <div class="modal-box custom-filter-box">
        <button class="close-filter-btn" type="button" onclick="closeAllModals()">×</button>

        <form id="filterFormClient" onsubmit="applyFilter(event)">
            <div class="filter-container">
                
                {{-- 1. FILTER KOTA (LOOPING) --}}
                <div class="filter-column">
                    <div class="filter-header-pill">Kota <span>▾</span></div>
                    <div class="filter-options-card">
                        @foreach($kota_list as $kota)
                            <label class="checkbox-item">
                                {{-- Value menggunakan Str::slug agar cocok dengan data-kota di kartu --}}
                                <input type="checkbox" name="kota[]" value="{{ Str::slug($kota->nama) }}">
                                <span>{{ $kota->nama }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- 2. FILTER KATEGORI (LOOPING) --}}
                 <div class="filter-column">
                    <div class="filter-header-pill">Kategori <span>▾</span></div>
                    <div class="filter-options-card">
                        @foreach($kategori_list as $kat)
                            <label class="checkbox-item">
                                <input type="checkbox" name="cat[]" value="{{ Str::slug($kat->nama) }}">
                                <span>{{ $kat->nama }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="filter-footer-custom">
                <button type="submit" class="btn-black-filter">Terapkan Filter</button>
            </div>
        </form>
    </div>
</div>

{{-- === MODAL JOIN === --}}
<div class="modal-overlay" id="joinModal">
    <div class="modal-box join-box-custom">
        <h3 style="text-align: center; margin-bottom: 20px;">Yakin mau gabung<br><span id="joinCommName" style="color: #000; font-weight:800;">Komunitas</span> dek !?!</h3>

        <div class="join-buttons">
            <button class="btn-pill" type="button" onclick="closeAllModals()" style="background:#eee; color:#333;">Gajadi Bg</button>
            <button class="btn-pill" type="button" onclick="submitJoinForm()" style="background:#000; color:#fff;">Yakin Bg</button>
        </div>
    </div>
</div>

{{-- FORM HIDDEN JOIN --}}
<form id="joinForm" method="POST" action="{{ route('komunitas.join') }}" style="display:none;">
    @csrf
    <input type="hidden" name="komunitas_id" id="joinKomunitasId" value="">
</form>

@endsection

{{-- GUNAKAN PUSH SCRIPTS AGAR JALAN --}}
@push('scripts')
<script>
    // ============================================
    // 1. FUNGSI MODAL
    // ============================================
    
    function openFilterModal() {
        const modal = document.getElementById('filterModal');
        if (modal) modal.classList.add('active');
    }

    function openJoinModal(name, id) {
        const nameEl = document.getElementById('joinCommName');
        const idInput = document.getElementById('joinKomunitasId');
        const modal = document.getElementById('joinModal');

        if (nameEl) nameEl.innerText = name;
        if (idInput) idInput.value = id;
        
        if (modal) modal.classList.add('active');
    }

    function closeAllModals() {
        document.querySelectorAll('.modal-overlay').forEach(el => {
            el.classList.remove('active');
        });
    }

    function submitJoinForm() {
        const form = document.getElementById('joinForm');
        const idInput = document.getElementById('joinKomunitasId');
        if (form && idInput.value) form.submit();
        else closeAllModals();
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            closeAllModals();
        }
    }

    // ============================================
    // 2. FUNGSI SEARCH & FILTER (CLIENT SIDE)
    // ============================================

    function searchCommunity() {
        runFilterLogic();
    }

    function applyFilter(e) {
        e.preventDefault(); 
        runFilterLogic();
        closeAllModals(); 
    }

    function runFilterLogic() {
        // Ambil Keyword
        const searchInput = document.getElementById('searchInput');
        const keyword = searchInput ? searchInput.value.toLowerCase().trim() : "";
        const tokens = keyword ? keyword.split(/\s+/) : [];

        // Ambil Checkbox yang dicentang
        const checkedKota = Array.from(document.querySelectorAll('input[name="kota[]"]:checked')).map(cb => cb.value);
        const checkedCat = Array.from(document.querySelectorAll('input[name="cat[]"]:checked')).map(cb => cb.value);

        // Loop Kartu
        const cards = document.getElementsByClassName('comm-card');
        
        for (let i = 0; i < cards.length; i++) {
            let card = cards[i];
            
            // Text Match
            const title = (card.querySelector('.comm-title')?.textContent || '').toLowerCase();
            const desc = (card.querySelector('.comm-desc')?.textContent || '').toLowerCase();
            const content = title + " " + desc;
            const matchText = tokens.length === 0 || tokens.every(token => content.includes(token));

            // Filter Match (Bandingkan SLUG vs SLUG)
            const dataKota = card.getAttribute('data-kota') || '';
            const dataCat = card.getAttribute('data-cat') || '';

            const matchKota = checkedKota.length === 0 || checkedKota.includes(dataKota);
            const matchCat = checkedCat.length === 0 || checkedCat.includes(dataCat);

            // Toggle Display
            if (matchText && matchKota && matchCat) {
                card.style.display = ""; 
            } else {
                card.style.display = "none";
            }
        }
    }
</script>
@endpush