<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderator Event - {{ $komunitas->nama }}</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/moderator-kegiatan.css') }}">
</head>
<body>

    <nav class="chat-navbar">
        <div class="nav-left">
            <a href="{{ route('home') }}" class="nav-item"><i class="fa-solid fa-arrow-left"></i></a>
            <a href="{{ route('moderator.chat', $komunitas->id) }}" class="nav-item">Chat</a>
            <a href="{{ route('moderator.events', $komunitas->id) }}" class="nav-item active">Events</a>
        </div>
        
        <div class="nav-center">
             <h1>{{ strtoupper($komunitas->nama) }} (MODERATOR MODE)</h1>
        </div>

        <div class="nav-right">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Search">
            </div>
        </div>
    </nav>

    <main class="event-dashboard">
        
        <div class="section-header">
            <h2>KEGIATAN MENDATANG (INTERNAL)</h2>
            <button class="btn-tambah" onclick="openModal()">+ Tambah Kegiatan</button>
        </div>

        <div class="kegiatan-grid">
            @forelse($kegiatan as $event)
                <div class="card-kegiatan">
                    <h3>{{ $event->judul }}</h3>
                    <div class="card-info">
                        <i class="fa-regular fa-calendar"></i>
                        <span>{{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="card-info">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>{{ $event->kota->nama ?? 'Online' }}</span>
                    </div>
                    <div style="margin-top: 10px;">
                         <button style="background:none; border:none; color:red; cursor:pointer; font-size:12px;">Hapus</button>
                    </div>
                </div>
            @empty
                <p>Belum ada kegiatan.</p>
            @endforelse
        </div>

        <div class="section-header center-title" style="margin-top: 60px;">
            <h2>LOMBA MENDATANG (GLOBAL)</h2>
        </div>

        <div class="lomba-grid">
            @foreach($lomba as $event)
                <div class="card-lomba">
                    <img src="{{ $event->poster_url ? asset($event->poster_url) : asset('image/default-event.jpg') }}" 
                         alt="Poster" onerror="this.src='{{ asset('image/default-event.jpg') }}'">
                    <div class="lomba-content">
                        <h4>{{ $event->judul }}</h4>
                        <div class="lomba-info"><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}</div>
                        <div class="lomba-info"><i class="fa-solid fa-location-dot"></i> {{ $event->kota->nama ?? 'Nasional' }}</div>
                    </div>
                </div>
            @endforeach
        </div>

    </main>

    {{-- MODAL TAMBAH KEGIATAN --}}
    <div id="modalAdd" class="modal-overlay">
        <div class="modal-content">
            {{-- Form ini nanti diarahkan ke EventsController@store --}}
            <form action="{{ route('events.storeRegistration', 0) }}" method="POST"> 
                @csrf
                <h3 style="margin-bottom: 20px;">Tambah Kegiatan Baru</h3>
                
                <div class="modal-group">
                    <label>Nama Acara</label>
                    <input type="text" name="judul" placeholder="Contoh: Kopdar Rutin">
                </div>
                
                <div class="modal-group">
                    <label>Tanggal</label>
                    <input type="date" name="start_date">
                </div>

                <div class="modal-group">
                    <label>Deskripsi Singkat</label>
                    <input type="text" name="deskripsi" placeholder="Deskripsi...">
                </div>

                <button type="button" class="btn-modal-submit" onclick="closeModal()">Tambahkan (Demo)</button>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalAdd');
        function openModal() { modal.style.display = 'flex'; }
        function closeModal() { modal.style.display = 'none'; }
        window.onclick = function(event) { if (event.target == modal) modal.style.display = "none"; }
    </script>

</body>
</html>