@extends('layouts.app')

@section('title', 'Riwayat Event Saya | ZHIB')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/event.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        /* Fix Navbar Tertutup */
        .riwayat-page {
            padding-top: 120px;
            min-height: 80vh;
            background-color: #f9f9f9;
            padding-bottom: 50px;
        }

        .section-header {
            margin-bottom: 25px;
            border-left: 5px solid #000;
            padding-left: 15px;
        }

        .section-header h2 {
            font-size: 24px;
            font-weight: 800;
            color: #333;
        }

        .divider {
            height: 1px;
            background: #e0e0e0;
            margin: 50px 0;
        }

        /* Badge Status Khusus */
        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            color: white;
            z-index: 2;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .status-upcoming {
            background-color: #2196F3; /* Biru */
        }

        .status-finished {
            background-color: #666; /* Abu-abu */
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            background: #fff;
            border-radius: 12px;
            border: 1px dashed #ccc;
            color: #777;
        }
    </style>
@endpush

@section('content')
<div class="riwayat-page">
    <div class="event-container">

        {{-- BAGIAN 1: EVENT AKAN DATANG --}}
        <div class="section-header">
            <h2>Event yang Akan Diikuti</h2>
            <p style="color: #666; font-size: 14px;">Jangan sampai terlewat jadwalnya!</p>
        </div>

        <div class="event-grid">
            @forelse($upcomingEvents as $event)
                <div class="event-card">
                    <span class="status-badge status-upcoming">AKAN DATANG</span>
                    
                    <div class="card-img">
                        <img src="{{ $event->poster_url ? asset($event->poster_url) : asset('image/default-event.jpg') }}" 
                             alt="{{ $event->judul }}"
                             onerror="this.onerror=null; this.src='{{ asset('image/default-event.jpg') }}'">
                    </div>

                    <div class="card-content">
                        <h3 class="event-title">{{ Str::limit($event->judul, 50) }}</h3>
                        
                        <div class="event-meta">
                            <div class="meta-item">
                                <i class="fa-regular fa-calendar"></i> 
                                {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d F Y') }}
                            </div>
                            <div class="meta-item">
                                <i class="fa-solid fa-clock"></i>
                                {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }} WIB
                            </div>
                        </div>

                        <a href="{{ route('events.show', $event->id) }}" class="btn-detail-event" style="display:block; margin-top:15px; text-align:center; background:#000; color:#fff; padding:10px; border-radius:8px; text-decoration:none; font-weight:600;">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <i class="fa-regular fa-calendar-xmark" style="font-size: 40px; margin-bottom: 15px;"></i>
                    <p>Anda belum mendaftar event apapun yang akan datang</p>
                    <a href="{{ route('events.index') }}" style="color: #000; font-weight: bold; text-decoration: underline;">Cari Event Sekarang</a>
                </div>
            @endforelse
        </div>

        <div class="divider"></div>

        {{-- BAGIAN 2: RIWAYAT EVENT SELESAI --}}
        <div class="section-header">
            <h2>Riwayat Event Selesai</h2>
            <p style="color: #666; font-size: 14px;">Event yang telah Anda selesaikan</p>
        </div>

        <div class="event-grid">
            @forelse($pastEvents as $event)
                {{-- Ambil data pivot peserta untuk event ini --}}
                @php 
                    $peserta = $event->participants->where('id', auth()->id())->first();
                    $sudahKlaim = $peserta && $peserta->pivot->status !== null;
                @endphp

                <div class="event-card" style="opacity: 1;">
                    
                    {{-- Badge Status --}}
                    @if($sudahKlaim)
                        <span class="status-badge status-finished">
                            {{ strtoupper($peserta->pivot->status) }} (+XP)
                        </span>
                    @else
                        <span class="status-badge" style="background: #ff9800;">BELUM KLAIM</span>
                    @endif

                    <div class="card-img">
                        <img src="{{ $event->poster_url ? asset($event->poster_url) : asset('image/default-event.jpg') }}"
                             alt="{{ $event->judul }}"
                             onerror="this.onerror=null; this.src='{{ asset('image/default-event.jpg') }}'">
                    </div>

                    <div class="card-content">
                        <h3 class="event-title">{{ Str::limit($event->judul, 50) }}</h3>
                        <p class="event-date"><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d M Y') }}</p>

                        {{-- LOGIKA TOMBOL KLAIM --}}
                        @if(!$sudahKlaim)
                            <div class="klaim-area" style="margin-top: 15px; background: #f0f0f0; padding: 15px; border-radius: 8px;">
                                <form action="{{ route('events.klaim', $event->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    
                                    <p style="font-size: 13px; font-weight: 700; margin-bottom: 10px; color: #333;">Klaim Kehadiran & XP</p>
                                    
                                    {{-- Input Bukti Foto --}}
                                    <label style="font-size: 12px; display:block; margin-bottom: 4px;">Bukti Foto (Opsional +50 XP)</label>
                                    <input type="file" name="bukti_foto" style="font-size: 12px; width: 100%; margin-bottom: 10px; background: #fff; padding: 5px; border-radius: 4px; border: 1px solid #ddd;">
                                    
                                    {{-- 🔥 TAMBAHAN: INPUT REVIEW 🔥 --}}
                                    <label style="font-size: 12px; display:block; margin-bottom: 4px;">Ulasan / Review (Opsional)</label>
                                    <textarea name="review" placeholder="Bagaimana kesan Anda mengikuti event ini?" 
                                              style="width: 100%; height: 60px; font-size: 12px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 4px; padding: 8px; font-family: inherit; resize: vertical;"></textarea>

                                    <button type="submit" class="btn-daftar-hitam" style="width: 100%; padding: 10px; font-size: 13px;">
                                        Klaim Sekarang (+10 XP)
                                    </button>
                                </form>
                            </div>
                        @else
                            {{-- TAMPILKAN TOMBOL NONAKTIF JIKA SUDAH KLAIM --}}
                            <div style="margin-top: 15px;">
                                @if($peserta->pivot->review_text)
                                    <div style="background: #f9f9f9; padding: 10px; border-radius: 6px; font-size: 12px; color: #555; margin-bottom: 10px; font-style: italic;">
                                        "{{ Str::limit($peserta->pivot->review_text, 50) }}"
                                    </div>
                                @endif
                                <button class="btn-daftar-hitam" style="width:100%; background:#ddd; color:#777; cursor:default; border:none;">
                                    Sudah Diklaim
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <p>Belum ada event yang selesai</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection