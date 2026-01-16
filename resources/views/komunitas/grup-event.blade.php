@extends('layouts.app', ['noHeader' => true])

@section('title', 'Events - ' . $komunitas->nama)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/grup-event.css') }}">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
@endpush

@section('content')

  <header class="chat-header" style="display: flex; justify-content: space-between; align-items: center;">
    
    {{-- BAGIAN KIRI (Tambahkan style flex: 1 agar lebarnya seimbang dengan kanan) --}}
    <div class="header-left" style="flex: 1; display: flex; align-items: center;">
        {{-- Tombol Kembali --}}
        <a href="{{ route('komunitas.my', $komunitas->id) }}" class="back-btn" style="margin-right: 15px; color: #000;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>

        {{-- Tab Navigasi --}}
        @php $grupUtama = $komunitas->grup->first(); @endphp
        <a href="{{ $grupUtama ? route('grup.chat', $grupUtama->id) : '#' }}" class="header-link">Chat</a>
        
        <a href="#" class="header-link active">Events</a>
    </div>

    {{-- BAGIAN TENGAH (Judul) --}}
    <div class="header-center" style="text-align: center;">
      <h1>{{ strtoupper($komunitas->nama) }}</h1>
      <small style="font-size: 12px; color: #666;">Daftar Kegiatan & Lomba</small>
    </div>

    {{-- BAGIAN KANAN (Searchbar DIHAPUS, tapi div tetap ada + flex: 1) --}}
    {{-- Ini triknya: Div kosong ini akan menyeimbangkan layout agar tengah tetap di tengah --}}
    <div class="header-right" style="flex: 1;">
    </div>

  </header>

  {{-- KONTEN UTAMA (Sama seperti sebelumnya tapi dinamis) --}}
  <main class="event-container" style="padding-top: 20px;"> {{-- Tambah padding agar tidak mepet header --}}

    {{-- BAGIAN 1: KEGIATAN INTERNAL --}}
    <section class="section-group">
      <h2 class="section-title">KEGIATAN MENDATANG</h2>

      <div class="activity-grid">
        @forelse($kegiatan as $event)
            <div class="activity-card">
              <h3>{{ Str::limit($event->judul, 60) }}</h3>
              
              <div class="meta-info">
                <div class="meta-item">
                  <i class="fa-regular fa-calendar"></i> 
                  {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d F Y') }}
                </div>
                <div class="meta-item">
                  <i class="fa-solid fa-location-dot"></i> 
                  {{ $event->kota->nama ?? 'Online' }}
                </div>

                {{-- Cek Status Terdaftar --}}
                @php
                    $isRegistered = \App\Models\PesertaKegiatan::where('user_id', Auth::id())
                                    ->where('events_id', $event->id)->exists();
                @endphp

                @if($isRegistered)
                    <div class="meta-item status-registered" style="color: #27ae60; font-weight: 700;">
                      <i class="fa-solid fa-clipboard-check"></i> Terdaftar
                    </div>
                @else
                    <div class="meta-item">
                        <a href="{{ route('events.show', $event->id) }}" style="text-decoration: underline; color: #000;">
                            Lihat Detail
                        </a>
                    </div>
                @endif
              </div>
            </div>
        @empty
            <div style="grid-column: 1/-1; text-align: center; color: #888; padding: 20px; background: #fff; border-radius: 8px;">
                <p>Belum ada kegiatan internal di komunitas ini.</p>
            </div>
        @endforelse
      </div>
    </section>

    {{-- BAGIAN 2: LOMBA (Global/Rekomendasi) --}}
    <section class="section-group">
      <h2 class="section-title">LOMBA MENDATANG (REKOMENDASI)</h2>

      <div class="competition-grid">
        @forelse($lomba as $event)
            <div class="comp-card">
              <div class="comp-img">
                <img src="{{ $event->poster_url ? asset($event->poster_url) : asset('image/default-event.jpg') }}" 
                     alt="{{ $event->judul }}"
                     onerror="this.onerror=null; this.src='{{ asset('image/default-event.jpg') }}'">
              </div>
              
              <div class="comp-content">
                <h4>{{ Str::limit($event->judul, 40) }}</h4>
                
                <div class="meta-info">
                  <div class="meta-item">
                    <i class="fa-regular fa-calendar"></i> 
                    {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d M Y') }}
                  </div>
                  <div class="meta-item">
                    <i class="fa-solid fa-location-dot"></i> 
                    {{ $event->kota->nama ?? 'Nasional' }}
                  </div>
                </div>

                <a href="{{ route('events.show', $event->id) }}" style="display: block; margin-top: 10px; font-weight: 700; color: #000; text-decoration: none;">
                    Ikuti Lomba &rarr;
                </a>
              </div>
            </div>
        @empty
            <div style="grid-column: 1/-1; text-align: center; color: #888; padding: 20px; background: #fff; border-radius: 8px;">
                <p>Belum ada info lomba terkait kategori ini.</p>
            </div>
        @endforelse
      </div>
    </section>

  </main>
@endsection