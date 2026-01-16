<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderator Chat - {{ $komunitas->nama }}</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/moderator-chat.css') }}">
</head>
<body>

    <nav class="chat-navbar">
        <div class="nav-left">
            {{-- Tombol Back ke Dashboard --}}
            <a href="{{ route('home') }}" class="nav-item" style="font-size: 18px;"><i class="fa-solid fa-arrow-left"></i></a>
            
            {{-- Navigasi Dinamis --}}
            <a href="{{ route('moderator.chat', $komunitas->id) }}" class="nav-item active">Chat</a>
            <a href="{{ route('moderator.events', $komunitas->id) }}" class="nav-item">Events</a>
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

    <main class="chat-area" id="chatContainer">
        
        @forelse($messages as $msg)
            @php $isMe = $msg->user_id === Auth::id(); @endphp

            <div class="msg-row {{ $isMe ? 'right' : 'left' }}">
                
                @if(!$isMe)
                    {{-- Avatar User Lain (Klik untuk Menu Moderator) --}}
                    <div class="msg-avatar" onclick="openMenu(event, '{{ $msg->user->nama }}', {{ $msg->user->id }})">
                        <img src="{{ $msg->user->foto_profil_url ? asset($msg->user->foto_profil_url) : asset('image/avatar/avatar-default.jpg') }}" 
                             onerror="this.src='{{ asset('image/avatar/avatar-default.jpg') }}'" alt="User">
                    </div>
                    <div class="accent-bar bar-blue"></div>
                @endif

                <div class="msg-bubble">
                    @if($msg->lampiran_url)
                         <a href="{{ asset('storage/' . $msg->lampiran_url) }}" target="_blank">
                            <img src="{{ asset('storage/' . $msg->lampiran_url) }}" style="max-width: 200px; border-radius: 8px; display:block; margin-bottom:5px;">
                         </a>
                    @endif
                    <p>{{ $msg->pesan }}</p>
                    <span style="font-size: 10px; color: #999; display:block; margin-top:5px;">
                        {{ $isMe ? 'Anda' : $msg->user->nama }} • {{ $msg->created_at->format('H:i') }}
                    </span>
                </div>

                @if($isMe)
                    <div class="accent-bar bar-black"></div>
                    <div class="msg-avatar">
                         <img src="{{ Auth::user()->foto_profil_url ? asset(Auth::user()->foto_profil_url) : asset('image/avatar/avatar-default.jpg') }}" 
                             onerror="this.src='{{ asset('image/avatar/avatar-default.jpg') }}'" alt="Me">
                    </div>
                @endif
            </div>
        @empty
            <div style="text-align: center; color: #888; margin-top: 50px;">Belum ada pesan.</div>
        @endforelse

    </main>
    
    {{-- MENU MODERATOR (KICK/PROFILE) --}}
    <div id="userActionMenu" class="action-menu">
        <div class="menu-item" onclick="actionLihatProfil()">Lihat Profil</div>
        <div class="menu-item kick" onclick="actionKick()">Kick Member</div>
    </div>

    {{-- FORM INPUT TEXT --}}
    <div class="chat-footer-wrapper">
        <div class="input-container">
            <form action="{{ route('grup.chat.send', $grup->id) }}" method="POST" style="display: flex; width: 100%; gap: 10px;">
                @csrf
                <input type="text" name="pesan" placeholder="Ketik pesan sebagai moderator..." autocomplete="off">
                <div class="input-icons">
                    <button type="button"><i class="fa-solid fa-paperclip"></i></button>
                    <button type="submit"><i class="fa-regular fa-paper-plane"></i></button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const menu = document.getElementById('userActionMenu');
        let selectedUser = '';
        let selectedUserId = null;

        // Scroll ke bawah saat load
        window.onload = function() {
            const chatArea = document.getElementById("chatContainer");
            chatArea.scrollTop = chatArea.scrollHeight;
        };

        function openMenu(e, name, id) {
            e.stopPropagation();
            selectedUser = name;
            selectedUserId = id;
            
            menu.style.top = e.clientY + 'px';
            menu.style.left = e.clientX + 'px';
            menu.style.display = 'block';
        }

        function actionLihatProfil() {
            alert("Melihat profil: " + selectedUser);
            menu.style.display = 'none';
        }

        function actionKick() {
            if(confirm("Yakin ingin kick " + selectedUser + " dari komunitas?")) {
                alert("Fitur Kick akan segera hadir (Backend Logic Needed)");
            }
            menu.style.display = 'none';
        }

        document.addEventListener('click', () => {
            menu.style.display = 'none';
        });
    </script>

</body>
</html>