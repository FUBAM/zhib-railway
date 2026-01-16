<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Pembayaran - ZHIB Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-pembayaran.css') }}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* --- STYLING TAMBAHAN KHUSUS HALAMAN INI --- */

        /* Notifikasi Popup */
        .notif-wrapper {
            position: relative;
        }

        .notif-popup {
            display: none;
            position: absolute;
            top: 50px;
            right: 0;
            width: 320px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            overflow: hidden;
        }

        .notif-popup.active {
            display: block;
        }

        .popup-header {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            background: #f8fafc;
            color: #334155;
        }

        .popup-body {
            max-height: 300px;
            overflow-y: auto;
        }

        .notif-item {
            padding: 12px 15px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            gap: 12px;
            align-items: start;
        }

        .notif-item:hover {
            background: #f8fafc;
        }

        .notif-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 14px;
        }

        .notif-icon.orange {
            background: #FFF7ED;
            color: #EA580C;
        }

        .notif-icon.green {
            background: #DCFCE7;
            color: #166534;
        }

        .notif-text h4 {
            margin: 0;
            font-size: 13px;
            color: #1e293b;
            font-weight: 600;
        }

        .notif-text p {
            margin: 2px 0 0;
            font-size: 12px;
            color: #64748b;
        }

        .notif-text small {
            font-size: 10px;
            color: #94a3b8;
            display: block;
            margin-top: 4px;
        }

        /* Status Badge */
        .status-pill {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
            display: inline-block;
        }

        .status-pill.pending {
            background: #FEF9C3;
            color: #854D0E;
            border: 1px solid #FDE047;
        }

        .status-pill.lunas {
            background: #DCFCE7;
            color: #166534;
            border: 1px solid #86EFAC;
        }

        .status-pill.ditolak {
            background: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FCA5A5;
        }

        /* Tombol Aksi */
        .action-group {
            display: flex;
            gap: 8px;
        }

        .btn-view {
            background: #EFF6FF;
            color: #3B82F6;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-view:hover {
            background: #DBEAFE;
        }

        .btn-icon-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 1px solid #E2E8F0;
            background: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
        }

        .btn-icon-circle.check {
            color: #166534;
        }

        .btn-icon-circle.check:hover {
            background: #DCFCE7;
            border-color: #86EFAC;
            transform: scale(1.05);
        }

        .btn-icon-circle.cross {
            color: #991B1B;
        }

        .btn-icon-circle.cross:hover {
            background: #FEE2E2;
            border-color: #FCA5A5;
            transform: scale(1.05);
        }

        /* User Avatar */
        .user-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar-circle {
            width: 32px;
            height: 32px;
            background: #E0E7FF;
            color: #4F46E5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 12px;
        }

        /* Modal Info */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            background: #F8FAFC;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            border: 1px solid #F1F5F9;
        }

        .info-item small {
            color: #64748B;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-item p {
            font-weight: 600;
            color: #334155;
            margin: 2px 0 0 0;
            font-size: 14px;
        }

        .info-item p.amount {
            color: #4F46E5;
            font-size: 16px;
        }
    </style>
</head>

<body>

    <div class="admin-container">
        @include('admin.partials.sidebar')

        <main class="main-content">
            <header class="topbar">
                <h1>Kelola Pembayaran</h1>
                <div class="topbar-right">

                    <div class="notif-wrapper">
                        <button class="btn-icon" onclick="toggleNotif()">
                            <i class="fa-regular fa-bell"></i>
                            @if(count($notifications ?? []) > 0) <span class="badge-dot"></span> @endif
                        </button>

                        <div class="notif-popup" id="notifPopup">
                            <div class="popup-header">
                                <span>Notifikasi</span>
                                <button onclick="toggleNotif()" style="background:none; border:none; cursor:pointer;"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="popup-body">
                                @forelse($notifications ?? [] as $notif)
                                <div class="notif-item">
                                    <div class="notif-icon {{ $notif->color ?? 'green' }}">
                                        <i class="{{ $notif->icon ?? 'fa-regular fa-bell' }}"></i>
                                    </div>
                                    <div class="notif-text">
                                        <h4>{{ $notif->title }}</h4>
                                        <p>{{ $notif->desc }}</p>
                                        <small>{{ $notif->time }}</small>
                                    </div>
                                </div>
                                @empty
                                <div style="padding:20px; text-align:center; color:#94a3b8; font-size:12px;">
                                    Tidak ada notifikasi baru
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>
            </header>

            <div class="content-wrapper">

                @if(session('success'))
                <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #86efac; display:flex; align-items:center; gap:10px;">
                    <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fca5a5; display:flex; align-items:center; gap:10px;">
                    <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
                </div>
                @endif

                <div class="table-card">
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pengguna</th>
                                <th>Acara</th>
                                <th>Jumlah</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $item)
                            <tr>
                                <td class="tx-id">#{{ $item->id }}</td>
                                <td>
                                    <div class="user-cell">
                                        <div class="avatar-circle">
                                            {{ strtoupper(substr($item->user->nama ?? 'U', 0, 2)) }}
                                        </div>
                                        <span>{{ $item->user->nama ?? 'User Terhapus' }}</span>
                                    </div>
                                </td>
                                <td>{{ Str::limit($item->event->judul ?? 'Event Terhapus', 30) }}</td>
                                <td class="nominal">Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                                <td>{{ $item->created_at->format('d M Y') }}</td>
                                <td>
                                    <span class="status-pill {{ $item->status }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-group">
                                        {{-- LOGIKA DATA & FOTO (Di PHP agar tidak bentrok kutip) --}}
                                        @php
                                        $dummyPath = asset('image/bukti_pembayaran/dummy_proof_1.jpg');

                                        if (empty($item->bukti_url)) {
                                        $imgUrl = $dummyPath;
                                        }
                                        elseif (Str::startsWith($item->bukti_url, 'bukti_bayar')) {
                                        $imgUrl = asset('storage/' . $item->bukti_url);
                                        }
                                        else {
                                        $imgUrl = asset($item->bukti_url);
                                        }

                                        // Simpan format ke variabel
                                        $tgl = $item->created_at->format('d M Y');
                                        $amount = 'Rp ' . number_format($item->jumlah_bayar, 0, ',', '.');
                                        @endphp

                                        <button class="btn-view" onclick="openModalBukti(
                                            '{{ $item->id }}',
                                            '{{ $imgUrl }}', 
                                            '{{ $item->user->nama ?? '-' }}', 
                                            '{{ $item->event->judul ?? '-' }}', 
                                            '{{ $amount }}', 
                                            '{{ $tgl }}' 
                                        )">
                                            <i class="fa-regular fa-eye"></i> Bukti
                                        </button>

                                        @if($item->status == 'pending')
                                        <button class="btn-icon-circle check" title="Setujui" onclick="openModalApprove(
                                                '{{ $item->id }}',
                                                '{{ $item->user->nama ?? '-' }}', 
                                                '{{ $item->event->judul ?? '-' }}', 
                                                '{{ $amount }}', 
                                                '{{ $tgl }}'
                                            )">
                                            <i class="fa-solid fa-check"></i>
                                        </button>

                                        <button class="btn-icon-circle cross" title="Tolak" onclick="openModalReject(
                                                '{{ $item->id }}',
                                                '{{ $item->user->nama ?? '-' }}', 
                                                '{{ $tgl }}'
                                            )">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align:center; padding: 50px; color: #94A3B8;">
                                    <i class="fa-regular fa-folder-open" style="font-size: 32px; margin-bottom: 10px; display:block;"></i>
                                    <span>Belum ada data pembayaran masuk.</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div class="modal-overlay" id="modalBukti">
        <div class="modal-card modal-lg">
            <div class="modal-header">
                <h3>Verifikasi Bukti Pembayaran</h3>
                <button class="close-btn" onclick="closeModal('modalBukti')"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div class="info-grid">
                    <div class="info-item">
                        <small>Anggota</small>
                        <p id="viewName">-</p>
                    </div>
                    <div class="info-item">
                        <small>Acara</small>
                        <p id="viewEvent">-</p>
                    </div>
                    <div class="info-item">
                        <small>Jumlah</small>
                        <p id="viewAmount" class="amount">-</p>
                    </div>
                    <div class="info-item">
                        <small>Tanggal</small>
                        <p id="viewDate">-</p>
                    </div>
                </div>

                <h4 style="margin-bottom: 10px; font-size: 14px; color: #334155;">Foto Bukti Transfer:</h4>
                <div style="text-align: center; background: #0F172A; padding: 10px; border-radius: 8px; min-height: 200px; display: flex; align-items: center; justify-content: center;">
                    <img id="viewImg" src="" alt="Bukti Transfer" style="max-width: 100%; max-height: 400px; object-fit: contain;">
                </div>
            </div>
            <div class="modal-footer" style="margin-top: 15px; display: flex; gap: 10px;">
                <button class="btn-full-green" style="flex:1; background:#16a34a; color:white; padding:12px; border:none; border-radius:8px; cursor:pointer; font-weight:600;" onclick="transferToApprove()">
                    <i class="fa-solid fa-check"></i> Verifikasi Sekarang
                </button>
                <button class="btn-full-red" style="flex:1; background:#dc2626; color:white; padding:12px; border:none; border-radius:8px; cursor:pointer; font-weight:600;" onclick="transferToReject()">
                    <i class="fa-solid fa-circle-xmark"></i> Tolak Pembayaran
                </button>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="modalApprove">
        <div class="modal-card modal-sm">
            <div class="modal-header centered">
                <h3>Konfirmasi Pembayaran</h3>
                <button class="close-absolute" onclick="closeModal('modalApprove')"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <p class="text-center" style="margin-bottom: 15px;">Apakah Anda yakin ingin <strong style="color:#16a34a">menyetujui</strong> pembayaran ini?</p>
                <div style="background:#F0FDF4; padding:15px; border-radius:8px; text-align:center; border: 1px dashed #86EFAC;">
                    <span id="confEvent" style="display:block; font-size:12px; color:#166534; margin-bottom: 5px;">-</span>
                    <strong id="confAmount" style="font-size:20px; color:#15803d;">-</strong>
                    <div id="confName" style="font-size:14px; color:#334155; margin-top:5px; font-weight:500;">-</div>
                </div>
            </div>
            <div class="modal-footer split">
                <form id="formApprove" action="" method="POST" style="width:100%">
                    @csrf
                    <input type="hidden" name="action" value="approve">
                    <div style="display: flex; gap: 10px; width: 100%;">
                        <button type="button" onclick="closeModal('modalApprove')" style="flex:1; background:white; border:1px solid #ddd; padding:10px; border-radius:6px; cursor:pointer;">Batal</button>
                        <button type="submit" style="flex:1; background:#16a34a; color:white; padding:10px; border:none; border-radius:6px; cursor:pointer;">Ya, Setujui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="modalReject">
        <div class="modal-card modal-sm">
            <div class="modal-header centered">
                <h3>Tolak Pembayaran</h3>
                <button class="close-absolute" onclick="closeModal('modalReject')"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="formReject" action="" method="POST">
                @csrf
                <input type="hidden" name="action" value="reject">
                <div class="modal-body">
                    <p class="text-center">Tolak pembayaran dari <strong id="rejName"></strong>?</p>
                    <p style="font-size:12px; font-weight:600; margin-top:15px; color:#334155;">Pilih Alasan Penolakan *</p>
                    <div style="display:flex; flex-direction:column; gap:10px; margin-top:8px;">
                        <label style="cursor:pointer; font-size:14px;"><input type="radio" name="alasan" value="Bukti tidak valid/buram" required> Bukti tidak valid / buram</label>
                        <label style="cursor:pointer; font-size:14px;"><input type="radio" name="alasan" value="Nominal tidak sesuai"> Nominal tidak sesuai</label>
                        <label style="cursor:pointer; font-size:14px;"><input type="radio" name="alasan" value="Salah rekening tujuan"> Salah rekening tujuan</label>
                        <label style="cursor:pointer; font-size:14px;"><input type="radio" name="alasan" value="Indikasi bukti palsu"> Indikasi bukti palsu</label>
                    </div>
                </div>
                <div class="modal-footer split" style="margin-top:20px;">
                    <div style="display: flex; gap: 10px; width: 100%;">
                        <button type="button" onclick="closeModal('modalReject')" style="flex:1; background:white; border:1px solid #ddd; padding:10px; border-radius:6px; cursor:pointer;">Batal</button>
                        <button type="submit" style="flex:1; background:#dc2626; color:white; padding:10px; border:none; border-radius:6px; cursor:pointer;">Tolak Pembayaran</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // 1. Fungsi Toggle Notifikasi
        function toggleNotif() {
            const popup = document.getElementById('notifPopup');
            popup.classList.toggle('active');
        }

        let currentData = {};

        // 2. Fungsi Buka Modal Bukti
        function openModalBukti(id, img, name, event, amount, date) {
            currentData = {
                id,
                name,
                event,
                amount,
                date
            };
            document.getElementById('viewImg').src = img;
            document.getElementById('viewName').innerText = name;
            document.getElementById('viewEvent').innerText = event;
            document.getElementById('viewAmount').innerText = amount;
            document.getElementById('viewDate').innerText = date;
            document.getElementById('modalBukti').classList.add('active');
        }

        // 3. Transfer Fungsi (Dari Modal Bukti ke Action)
        function transferToApprove() {
            closeModal('modalBukti');
            setTimeout(() => {
                openModalApprove(currentData.id, currentData.name, currentData.event, currentData.amount, currentData.date);
            }, 200);
        }

        function transferToReject() {
            closeModal('modalBukti');
            setTimeout(() => {
                openModalReject(currentData.id, currentData.name, currentData.date);
            }, 200);
        }

        // 4. Modal Approve
        function openModalApprove(id, name, event, amount, date) {
            document.getElementById('confName').innerText = name;
            document.getElementById('confEvent').innerText = event;
            document.getElementById('confAmount').innerText = amount;

            // Set Form Action URL Dinamis
            let url = "{{ url('/admin/pembayaran') }}/" + id + "/verifikasi";
            document.getElementById('formApprove').action = url;

            document.getElementById('modalApprove').classList.add('active');
        }

        // 5. Modal Reject
        function openModalReject(id, name, date) {
            document.getElementById('rejName').innerText = name;

            // Set Form Action URL Dinamis
            let url = "{{ url('/admin/pembayaran') }}/" + id + "/verifikasi";
            document.getElementById('formReject').action = url;

            document.getElementById('modalReject').classList.add('active');
        }

        // 6. Utility Close
        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        // 7. Klik Luar Modal / Notif untuk Menutup
        window.onclick = function(event) {
            // Tutup Modal
            if (event.target.classList.contains('modal-overlay')) {
                event.target.classList.remove('active');
            }

            // Tutup Notifikasi
            const notifPopup = document.getElementById('notifPopup');
            const notifBtn = document.querySelector('.notif-wrapper .btn-icon');

            if (notifPopup && notifPopup.classList.contains('active')) {
                if (!notifPopup.contains(event.target) && !notifBtn.contains(event.target)) {
                    notifPopup.classList.remove('active');
                }
            }
        }
    </script>

</body>

</html>