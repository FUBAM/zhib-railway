<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Grup Chat - {{ $grup->komunitas->nama ?? 'Komunitas' }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  {{-- Pastikan file CSS ada di public/css/chat.css --}}
  <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
  
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

  {{-- HEADER --}}
  <header class="chat-header">
    <div class="header-left">
        {{-- Link Kembali ke Detail Komunitas --}}
        <a href="{{ route('komunitas.my') }}" class="back-btn" style="margin-right: 15px; color: #000;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>

        {{-- Tab Navigasi --}}
        <a href="#" class="header-link active">Chat</a>
        
        {{-- Link ke Event Komunitas --}}
        <a href="{{ route('komunitas.events', $grup->komunitas_id) }}" class="header-link">Events</a>
    </div>

    <div class="header-center">
      {{-- Nama Komunitas / Grup --}}
      <h1>{{ strtoupper($grup->komunitas->nama ?? 'NAMA KOMUNITAS') }}</h1>
      <small style="font-size: 12px; color: #666;">{{ $grup->nama }}</small>
    </div>

    <div class="header-right">
      <div class="search-pill">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="Search">
      </div>
    </div>
  </header>

  {{-- MAIN CHAT AREA --}}
  <main class="chat-container" id="chatContainer">

    {{-- LOOPING PESAN DARI DATABASE --}}
    @forelse($messages as $msg)
        @php
            // Cek apakah pesan ini milik user yang sedang login
            $isMe = $msg->user_id === Auth::id();
        @endphp

        <div class="message {{ $isMe ? 'outgoing' : 'incoming' }}">
            
            {{-- Avatar (Hanya tampilkan jika Incoming/Orang Lain) --}}
            @if(!$isMe)
                <img src="{{ $msg->user->foto_profil_url ? asset($msg->user->foto_profil_url) : asset('image/avatar/avatar-default.jpg') }}" 
                     class="avatar" 
                     alt="{{ $msg->user->nama }}"
                     title="{{ $msg->user->nama }}">
            @endif

            <div class="bubble-wrapper">
                {{-- Nama Pengirim (Kecil di atas bubble jika grup ramai) --}}
                @if(!$isMe)
                    <span style="font-size: 10px; color: #888; margin-bottom: 2px; display: block; margin-left: 5px;">
                        {{ $msg->user->nama }}
                    </span>
                @endif

                <div class="bubble">
                    <p>{{ $msg->pesan }}</p>
                </div>
                
                {{-- Waktu Pesan --}}
                <span style="font-size: 10px; color: #aaa; margin-top: 2px; display: block; text-align: {{ $isMe ? 'right' : 'left' }};">
                    {{ $msg->created_at->format('H:i') }}
                </span>
            </div>

            {{-- Avatar (Tampilkan di kanan jika Outgoing/Saya) --}}
            @if($isMe)
                <img src="{{ Auth::user()->foto_profil_url ? asset(Auth::user()->foto_profil_url) : asset('image/avatar/avatar-default.jpg') }}" 
                     class="avatar" 
                     alt="Me">
            @endif
        </div>

    @empty
        {{-- Tampilan Jika Belum Ada Chat --}}
        <div style="text-align: center; margin-top: 50px; color: #999;">
            <p>Belum ada obrolan di grup ini.</p>
            <p>Jadilah yang pertama menyapa! 👋</p>
        </div>
    @endforelse
    
    {{-- Spacer agar chat paling bawah tidak tertutup input --}}
    <div style="height: 100px;"></div>
    
    {{-- Elemen kosong untuk target scroll otomatis --}}
    <div id="scrollTarget"></div>

  </main>

  {{-- FOOTER INPUT AREA --}}
  <footer class="chat-input-area">
    <button class="btn-refresh" onclick="location.reload()">
      <i class="fa-solid fa-rotate-right"></i>
    </button>

    <div class="input-box">
      {{-- FORM KIRIM PESAN --}}
      <form action="{{ route('grup.chat.send', $grup->id) }}" method="POST" style="display: flex; width: 100%; align-items: center;">
          @csrf
          
          <input type="text" name="pesan" placeholder="Ketik pesan..." required autocomplete="off">
          
          <div class="input-actions">
            {{-- Tombol Lampiran (Non-aktif sementara/Butuh JS tambahan) --}}
            <button type="button"><i class="fa-solid fa-paperclip"></i></button>
            <button type="button"><i class="fa-regular fa-face-smile"></i></button>
            
            {{-- Tombol Kirim --}}
            <button type="submit" class="btn-send"><i class="fa-regular fa-paper-plane"></i></button>
          </div>
      </form>
    </div>
  </footer>

  {{-- SCRIPT SCROLL KE BAWAH OTOMATIS --}}
  <script>
    document.addEventListener("DOMContentLoaded", function() {
        const chatContainer = document.getElementById('chatContainer');
        // Scroll ke elemen paling bawah
        const target = document.getElementById('scrollTarget');
        if(target) {
            target.scrollIntoView();
        }
    });
  </script>

</body>
</html>