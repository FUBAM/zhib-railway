<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard DIY - ZHIB Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* Penyesuaian agar sesuai desain image_e93f43.png */
        .community-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .community-table th {
            text-align: left;
            padding: 15px 12px;
            color: #94a3b8;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #f1f5f9;
        }

        .community-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .com-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .com-logo {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            object-fit: cover;
            background: #f8fafc;
            border: 1px solid #f1f5f9;
        }

        .com-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 14px;
        }

        .badge-member {
            background: #EEF2FF;
            color: #4F46E5;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .card-custom {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            border: 1px solid #f1f5f9;
            height: 100%;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <div class="admin-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>ZHIB Admin</h2>
                <span>Regional DIY</span>
            </div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('admin.dashboard') }}" class="active"><i class="fa-solid fa-border-all"></i> Dashboard</a></li>
                <li><a href="{{ route('admin.pembayaran') }}"><i class="fa-regular fa-credit-card"></i> Pembayaran</a></li>
                <li><a href="{{ route('admin.lomba') }}"><i class="fa-solid fa-trophy"></i> Lomba</a></li>
                <li><a href="{{ route('admin.komunitas') }}"><i class="fa-solid fa-user-group"></i> Komunitas</a></li>
                <li><a href="{{ route('admin.laporan') }}"><i class="fa-solid fa-triangle-exclamation"></i> Laporan</a></li>
                <li><a href="{{ route('admin.berita') }}"><i class="fa-regular fa-newspaper"></i> Berita</a></li>
                <li class="spacer"></li>
                <li>
                    <form action="{{ url('/logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout" style="background:none; border:none; width:100%; text-align:left; padding:12px 15px; cursor:pointer; display:flex; align-items:center; gap:12px; font-weight:500; font-size:16px; color: #ef4444;">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <h1>Dashboard DIY</h1>
                <div class="topbar-right">
                    
                    <div class="notif-wrapper">
                        <button class="btn-icon" onclick="toggleNotif()">
                            <i class="fa-regular fa-bell"></i>
                            @if($unreadCount > 0) <span class="badge-dot"></span> @endif
                        </button>
                        <div class="notif-popup" id="notifPopup">
                            <div class="popup-header">
                                <div class="ph-content">
                                    <div class="ph-text">
                                        <h3>Notifikasi</h3><span>{{ $unreadCount }} pending verifikasi</span>
                                    </div>
                                </div>
                            </div>
                            <div class="popup-body">
                                @foreach($notifications as $notif)
                                <div class="p-item unread">
                                    <div class="p-icon-circle {{ $notif->color_class }}"><i class="{{ $notif->icon_class }}"></i></div>
                                    <div class="p-details">
                                        <h4>{{ $notif->title }}</h4>
                                        <small>{{ $notif->time }}</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-info">
                        <span>Total Anggota</span>
                        <h3>{{ number_format($totalAnggota) }}</h3>
                        <small class="green-text">+12% dari bulan lalu</small>
                    </div>
                    <div class="stat-icon-bg blue"><i class="fa-solid fa-users"></i></div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <span>Pembayaran Tertunda</span>
                        <h3>{{ $pembayaranTertunda }}</h3>
                        <small>{{ $pembayaranTertunda }} baru hari ini</small>
                    </div>
                    <div class="stat-icon-bg purple"><i class="fa-regular fa-clock"></i></div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <span>Lomba Aktif</span>
                        <h3>{{ $lombaAktif }}</h3>
                        <small class="green-text">3 segera berakhir</small>
                    </div>
                    <div class="stat-icon-bg blue"><i class="fa-solid fa-trophy"></i></div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <span>Laporan Terbuka</span>
                        <h3>{{ $laporanTerbuka }}</h3>
                        <small>2 prioritas</small>
                    </div>
                    <div class="stat-icon-bg purple"><i class="fa-solid fa-circle-exclamation"></i></div>
                </div>
            </div>

            <div class="dashboard-grid">
                <div class="card-custom">
                    <div class="card-title">
                        <i class="fa-solid fa-fire" style="color: #6366f1;"></i>
                        <span>Komunitas Paling Aktif</span>
                    </div>
                    <table class="community-table">
                        <thead>
                            <tr>
                                <th>Komunitas</th>
                                <th>Regional</th>
                                <th>Anggota</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($komunitasAktif as $kom)
                            @php
                            $logo = $kom->logo ? asset('storage/'.$kom->logo) : asset('image/default-com.png');
                            $fallback = asset('image/default-com.png');
                            @endphp
                            <tr>
                                <td>
                                    <div class="com-info">
                                        <img src="{{ $logo }}" class="com-logo" onerror="this.src='{{ $fallback }}'">
                                        <span class="com-name">{{ $kom->nama }}</span>
                                    </div>
                                </td>
                                <td><span style="color: #64748b; font-size: 13px;">{{ $kom->kota->nama ?? 'DIY' }}</span></td>
                                <td><span class="badge-member">{{ $kom->anggota_count }} Member</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-custom">
                    <div class="card-title">
                        <i class="fa-solid fa-bolt" style="color: #6366f1;"></i>
                        <span>Aktivitas Terkini</span>
                    </div>
                    <ul class="activity-list">
                        @foreach($activities as $act)
                        <li class="act-item {{ $act->type === 'payment' ? 'green-dot' : ($act->type === 'report' ? 'red-dot' : 'blue-dot') }}">
                            <p>{!! $act->text !!}</p>
                            <small>{{ $act->time->diffForHumans() }}</small>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleNotif() {
            document.getElementById('notifPopup').classList.toggle('active');
        }
        window.onclick = function(e) {
            const popup = document.getElementById('notifPopup');
            const btn = document.querySelector('.btn-icon');
            if (popup && !popup.contains(e.target) && btn && !btn.contains(e.target)) {
                popup.classList.remove('active');
            }
        }
    </script>
</body>

</html>