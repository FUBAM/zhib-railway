@extends('layouts.app')

@section('title', 'Komunitas Saya | ZHIB')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
<link rel="stylesheet" href="{{ asset('css/komunitas-saya.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    /* Fix Navbar tertutup */
    .my-comm-page {
        padding-top: 120px;
        min-height: 80vh;
        background-color: #f9f9f9;
        padding-bottom: 50px;
    }
    .comm-card {
        display: flex; 
    }
</style>
@endpush

@section('content')

<div class="my-comm-page">
    <div class="container">

        <div class="page-header">
            <h1 class="page-title">Komunitas Saya</h1>
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <form onsubmit="return false;">
                    <input type="search" id="searchInput" placeholder="Cari komunitas saya..."
                        onkeyup="searchMyCommunity()" 
                        onsearch="searchMyCommunity()">
                </form>
            </div>
        </div>

        <div class="community-list" id="communityList">
            @if(isset($komunitas) && $komunitas->count())
                @foreach($komunitas as $k)
                
                {{-- 🔥 LOGIKA PENGECEKAN ROLE (MODERATOR vs MEMBER) 🔥 --}}
                @php
                    // 1. Cek apakah user yang login adalah MODERATOR di komunitas ini
                    $isModerator = \App\Models\AnggotaKomunitas::where('user_id', Auth::id())
                        ->where('komunitas_id', $k->id)
                        ->where('role', 'moderator')
                        ->exists();

                    // 2. Tentukan URL Tujuan
                    if ($isModerator) {
                        // JIKA MODERATOR -> Masuk ke Blade Moderator Chat
                        // Route: moderator.chat (sesuai web.php)
                        $targetUrl = route('moderator.chat', $k->id);
                        $btnTitle = "Masuk sebagai Moderator";
                    } else {
                        // JIKA MEMBER BIASA -> Cek apakah ada grup chat?
                        $grupUtama = $k->grup->first();
                        
                        // Jika ada grup -> Masuk Chat Member, Jika tidak -> Masuk Detail Komunitas
                        $targetUrl = $grupUtama ? route('grup.chat', $grupUtama->id) : route('komunitas.show', $k->id);
                        $btnTitle = "Masuk ke Chat";
                    }
                @endphp

                <div class="comm-card">
                    <div class="comm-img">
                        <img src="{{ $k->logo_url ? asset($k->logo_url) : asset('image/komunitas/komunitas-default.jpg') }}" 
                             alt="{{ $k->nama }}"
                             onerror="this.onerror=null; this.src='{{ asset('image/default-community.jpg') }}'">
                    </div>
                    
                    <div class="comm-content">
                        <h2 class="comm-title">{{ $k->nama }}</h2>
                        <p class="comm-desc">{{ \Illuminate\Support\Str::limit($k->deskripsi, 120) }}</p>
                        
                        {{-- (Opsional) Badge Penanda Role --}}
                        @if($isModerator)
                            <span style="background: #000; color: #fff; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: bold;">
                                MODERATOR
                            </span>
                        @endif
                    </div>
                    
                    <div class="comm-action">
                        {{-- 🔥 TOMBOL DENGAN URL DINAMIS 🔥 --}}
                        <a href="{{ $targetUrl }}" class="btn-open-chat" title="{{ $btnTitle }}">
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            @else
                {{-- Kosong --}}
            @endif
        </div>

        <p id="noResults" class="no-results {{ (isset($komunitas) && $komunitas->count() === 0) ? '' : 'hidden' }}"
            style="text-align:center; color:#666; margin-top:20px; font-weight: 500;"
            data-empty-text="Anda belum bergabung dengan komunitas apapun."
            data-search-text="Tidak ada komunitas yang cocok.">
            {{ (isset($komunitas) && $komunitas->count() === 0) ? 'Anda belum bergabung dengan komunitas apapun.' : '' }}
        </p>

    </div>
</div>

@endsection

@push('scripts')
<script>
function searchMyCommunity() {
    const inputEl = document.getElementById('searchInput');
    const noEl = document.getElementById('noResults');
    const query = inputEl ? inputEl.value.trim().toLowerCase() : '';
    const tokens = query.split(/\s+/).filter(Boolean);
    
    const cards = Array.from(document.getElementsByClassName('comm-card'));
    let anyVisible = false;

    cards.forEach(card => {
        const titleEl = card.querySelector('.comm-title');
        const descEl = card.querySelector('.comm-desc');
        const text = ((titleEl ? titleEl.textContent : '') + ' ' + (descEl ? descEl.textContent : '')).toLowerCase();

        const matched = tokens.length === 0 || tokens.every(t => text.includes(t));

        if (matched) {
            card.style.display = 'flex';
            anyVisible = true;
        } else {
            card.style.display = 'none';
        }
    });

    if (noEl) {
        if (!anyVisible) {
            const msg = (query.length > 0) ? noEl.dataset.searchText : noEl.dataset.emptyText;
            noEl.textContent = msg;
            noEl.classList.remove('hidden');
        } else {
            noEl.classList.add('hidden');
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    searchMyCommunity();
});
</script>
@endpush