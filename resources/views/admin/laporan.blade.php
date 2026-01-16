<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pusat Pengelolaan Laporan - ZHIB Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- LOAD CSS EXTERNAL --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lomba.css') }}">
    <link rel="stylesheet" href="{{ asset('css/laporan.css') }}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- CSS TAMBAHAN UNTUK MEMPERBAIKI KOLOM AKSI --}}
    <style>
        /* Mengatur kolom aksi agar tombol tersusun rapi */
        .action-column-fix {
            display: flex;
            flex-direction: column;
            gap: 8px;
            /* Jarak antar elemen */
            align-items: center;
            padding: 5px 0;
        }

        /* Tombol Tinjau Bukti */
        .btn-tinjau-fix {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background-color: transparent;
            border: 1px solid #6366f1;
            color: #6366f1;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-tinjau-fix:hover {
            background-color: #6366f1;
            color: white;
        }

        /* Baris untuk tombol kecil (Peringatan, Blokir, Tolak) */
        .action-row-small-fix {
            display: flex;
            gap: 5px;
            width: 100%;
            justify-content: center;
        }

        /* Tombol Kecil */
        .btn-mini-fix {
            flex: 1;
            /* Lebar sama rata */
            padding: 6px 2px;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 11px;
            cursor: pointer;
            text-align: center;
            color: white;
        }

        /* Warna Tombol Kecil */
        .btn-mini-fix.yellow {
            background-color: #f59e0b;
            color: #fff;
        }

        .btn-mini-fix.red {
            background-color: #ef4444;
            color: #fff;
        }

        .btn-mini-fix.gray {
            background-color: #9ca3af;
            color: #fff;
        }

        .btn-mini-fix:hover {
            opacity: 0.9;
        }

        /* Memastikan sel tabel cukup lega */
        table.table-laporan td {
            vertical-align: middle !important;
        }
    </style>
</head>

<body>

    <div class="admin-container">

        {{-- SIDEBAR (Pastikan file ini ada) --}}
        @include('admin.partials.sidebar')

        <main class="main-content">

            {{-- HEADER ATAS --}}
            <header class="topbar">
                <h1>Pusat Pengelolaan Laporan</h1>
                <div class="topbar-right">
                    
                    <div class="notif-wrapper">
                        <button class="btn-icon"><i class="fa-regular fa-bell"></i></button>
                    </div>
                </div>
            </header>

            {{-- TABS NAVIGATION --}}
            <div class="laporan-tabs">
                <button class="tab-btn active" onclick="switchTab('pengguna', this)">
                    Laporan Pengguna <span class="badge-num red">{{ $laporanPengguna->count() }}</span>
                </button>
                <button class="tab-btn" onclick="switchTab('pesan', this)">
                    Laporan Pesan <span class="badge-num pink">{{ $laporanPesan->count() }}</span>
                </button>
                <button class="tab-btn" onclick="switchTab('acara', this)">
                    Laporan Acara <span class="badge-num pink">{{ $laporanAcara->count() }}</span>
                </button>
            </div>

            {{-- TAB 1: PENGGUNA --}}
            <div id="tab-pengguna" class="tab-content active">
                <div class="table-container-dark">
                    <table class="table-laporan">
                        <thead>
                            <tr>
                                <th>PELAPOR</th>
                                <th>TERTUDUH/TARGET</th>
                                <th>ALASAN</th>
                                <th>BUKTI</th>
                                <th>STATUS</th>
                                <th style="width: 180px;">AKSI</th> {{-- Lebarkan kolom aksi --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporanPengguna as $item)
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="avatar-circle purple">
                                            {{ substr($item->pelapor->nama ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <strong>{{ $item->pelapor->nama ?? 'User Tidak Ada' }}</strong><br>
                                            <small>{{ $item->pelapor->wilayah ?? 'DIY' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="target-info">
                                        <div class="alert-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                                        <div>
                                            {{-- Panggil Accessor dari Model --}}
                                            <strong>{{ $item->target_nama }}</strong><br>
                                            <small>{{ $item->target_tipe }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge-reason">{{ $item->alasan }}</span></td>
                                <td>
                                    <div class="bukti-thumb" onclick="siapkanModal('{{ $item->id }}', '{{ $item->target_nama }}', '{{ $item->url }}', '{{ $item->alasan }}')">
                                        <img src="{{ $item->url }}" alt="Bukti">
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-status {{ $item->status == 'pending' ? 'new' : ($item->status == 'resolved' ? 'resolved' : 'rejected') }}">
                                        {{ $item->status_label }}
                                    </span>
                                </td>
                                <td>
                                    {{-- KOLOM AKSI YANG SUDAH DIPERBAIKI --}}
                                    <div class="action-column-fix">
                                        <button class="btn-tinjau-fix" onclick="siapkanModal('{{ $item->id }}', '{{ $item->target_nama }}', '{{ $item->url }}', '{{ $item->alasan }}')">
                                            <i class="fa-regular fa-eye"></i> Tinjau Bukti
                                        </button>
                                        <div class="action-row-small-fix">
                                            <button class="btn-mini-fix yellow" onclick="kirimAksi('warn', '{{ $item->id }}', '{{ $item->target_nama }}')">Peringatan</button>
                                            <button class="btn-mini-fix red" onclick="kirimAksi('block', '{{ $item->id }}', '{{ $item->target_nama }}')">Blokir</button>
                                            <button class="btn-mini-fix gray" onclick="kirimAksi('reject', '{{ $item->id }}', '{{ $item->target_nama }}')">Tolak</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding:30px; color:#888;">Belum ada laporan pengguna.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TAB 2: PESAN --}}
            <div id="tab-pesan" class="tab-content" style="display:none;">
                <div class="table-container-dark">
                    <table class="table-laporan">
                        <thead>
                            <tr>
                                <th>PELAPOR</th>
                                <th>TARGET</th>
                                <th>ALASAN</th>
                                <th>BUKTI</th>
                                <th>STATUS</th>
                                <th style="width: 180px;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporanPesan as $item)
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="avatar-circle blue">{{ substr($item->pelapor->nama ?? '?', 0, 1) }}</div>
                                        <div><strong>{{ $item->pelapor->nama ?? 'User Tidak Ada' }}</strong></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="target-info">
                                        <div class="alert-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                                        <div>
                                            <strong>{{ $item->target_nama }}</strong><br>
                                            <small>{{ $item->target_tipe }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge-reason">{{ $item->alasan }}</span></td>
                                <td>
                                    <div class="bukti-thumb" onclick="siapkanModal('{{ $item->id }}', '{{ $item->target_nama }}', '{{ $item->url }}', '{{ $item->alasan }}')">
                                        <img src="{{ $item->url }}" alt="Bukti">
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-status {{ $item->status == 'pending' ? 'new' : ($item->status == 'resolved' ? 'resolved' : 'rejected') }}">
                                        {{ $item->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-column-fix">
                                        <button class="btn-tinjau-fix" onclick="siapkanModal('{{ $item->id }}', '{{ $item->target_nama }}', '{{ $item->url }}', '{{ $item->alasan }}')">
                                            <i class="fa-regular fa-eye"></i> Tinjau Bukti
                                        </button>
                                        <div class="action-row-small-fix">
                                            <button class="btn-mini-fix yellow" onclick="kirimAksi('warn', '{{ $item->id }}', '{{ $item->target_nama }}')">Peringatan</button>
                                            <button class="btn-mini-fix red" onclick="kirimAksi('block', '{{ $item->id }}', '{{ $item->target_nama }}')">Blokir</button>
                                            <button class="btn-mini-fix gray" onclick="kirimAksi('reject', '{{ $item->id }}', '{{ $item->target_nama }}')">Tolak</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding:30px; color:#888;">Belum ada laporan pesan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TAB 3: ACARA --}}
            <div id="tab-acara" class="tab-content" style="display:none;">
                <div class="table-container-dark">
                    <table class="table-laporan">
                        <thead>
                            <tr>
                                <th>PELAPOR</th>
                                <th>TARGET</th>
                                <th>ALASAN</th>
                                <th>BUKTI</th>
                                <th>STATUS</th>
                                <th style="width: 180px;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporanAcara as $item)
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="avatar-circle green">{{ substr($item->pelapor->nama ?? '?', 0, 1) }}</div>
                                        <div><strong>{{ $item->pelapor->nama ?? 'User Tidak Ada' }}</strong></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="target-info">
                                        <div class="alert-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                                        <div>
                                            <strong>{{ $item->target_nama }}</strong><br>
                                            <small>{{ $item->target_tipe }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge-reason">{{ $item->alasan }}</span></td>
                                <td>
                                    <div class="bukti-thumb" onclick="siapkanModal('{{ $item->id }}', '{{ $item->target_nama }}', '{{ $item->url }}', '{{ $item->alasan }}')">
                                        <img src="{{ $item->url }}" alt="Bukti">
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-status {{ $item->status == 'pending' ? 'new' : ($item->status == 'resolved' ? 'resolved' : 'rejected') }}">
                                        {{ $item->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-column-fix">
                                        <button class="btn-tinjau-fix" onclick="siapkanModal('{{ $item->id }}', '{{ $item->target_nama }}', '{{ $item->url }}', '{{ $item->alasan }}')">
                                            <i class="fa-regular fa-eye"></i> Tinjau Bukti
                                        </button>
                                        <div class="action-row-small-fix">
                                            <button class="btn-mini-fix yellow" onclick="kirimAksi('warn', '{{ $item->id }}', '{{ $item->target_nama }}')">Peringatan</button>
                                            <button class="btn-mini-fix red" onclick="kirimAksi('block', '{{ $item->id }}', '{{ $item->target_nama }}')">Blokir</button>
                                            <button class="btn-mini-fix gray" onclick="kirimAksi('reject', '{{ $item->id }}', '{{ $item->target_nama }}')">Tolak</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding:30px; color:#888;">Belum ada laporan acara.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- STATISTIK REALTIME --}}
            <div class="laporan-stats-grid">
                <div class="ls-card">
                    <div class="ls-icon red"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <div class="ls-info"><span>Total Laporan</span>
                        <h3>{{ $laporanPengguna->count() + $laporanPesan->count() + $laporanAcara->count() }}</h3>
                    </div>
                </div>
                <div class="ls-card">
                    <div class="ls-icon pink"><i class="fa-solid fa-file-circle-exclamation"></i></div>
                    <div class="ls-info"><span>Laporan Baru</span>
                        <h3>{{
                            $laporanPengguna->where('status', 'pending')->count() +
                            $laporanPesan->where('status', 'pending')->count() +
                            $laporanAcara->where('status', 'pending')->count()
                        }}</h3>
                    </div>
                </div>
                <div class="ls-card">
                    <div class="ls-icon yellow"><i class="fa-regular fa-eye"></i></div>
                    <div class="ls-info"><span>Dalam Tinjauan</span>
                        <h3>0</h3>
                    </div>
                </div>
                <div class="ls-card">
                    <div class="ls-icon green"><i class="fa-solid fa-clipboard-check"></i></div>
                    <div class="ls-info"><span>Terselesaikan</span>
                        <h3>{{
                            $laporanPengguna->where('status', 'resolved')->count() +
                            $laporanPesan->where('status', 'resolved')->count() +
                            $laporanAcara->where('status', 'resolved')->count()
                        }}</h3>
                    </div>
                </div>
            </div>

        </main>
    </div>

    {{-- MODAL BUKTI --}}
    <div class="modal-overlay" id="modalBukti">
        <div class="modal-box medium-box">
            <div class="modal-header">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div class="danger-icon-circle"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <div>
                        <h3 style="margin:0;">Tinjau Bukti</h3>
                        <span style="font-size:12px; color:#888;">ID Laporan: <span id="modal-id-text">#</span></span>
                    </div>
                </div>
                <span class="close-icon" onclick="tutupModal('modalBukti')">&times;</span>
            </div>

            <div class="modal-body">
                <div class="desc-box">
                    <small>Target yang dilaporkan</small>
                    <p><strong id="modal-target-text">-</strong></p>
                    <small>Alasan / Deskripsi</small>
                    <p id="modal-desc-text">-</p>
                </div>

                <div class="evidence-box">
                    <small>Bukti Lampiran</small>
                    <div class="evidence-img-wrapper" style="margin-top:10px;">
                        <img id="modal-img-bukti" src="" alt="Bukti Full Size" style="width:100%; border-radius:8px;">
                    </div>
                </div>
            </div>

            <div class="modal-footer footer-grid">
                <button class="btn-action-large yellow" id="modal-btn-warn">
                    <i class="fa-solid fa-triangle-exclamation"></i> Peringatan
                </button>
                <button class="btn-action-large red" id="modal-btn-block">
                    <i class="fa-solid fa-ban"></i> Blokir
                </button>
                <button class="btn-action-large gray" id="modal-btn-reject">
                    <i class="fa-solid fa-xmark"></i> Tolak
                </button>
            </div>
        </div>
    </div>

    <script>
        // === VARIABEL GLOBAL ===
        let currentReportId = null;
        let currentTargetName = '';

        function bukaModal(id) {
            document.getElementById(id).style.display = 'flex';
        }

        function tutupModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        // 1. Siapkan Modal
        function siapkanModal(id, targetName, imgUrl, alasan) {
            currentReportId = id;
            currentTargetName = targetName;

            document.getElementById('modal-id-text').innerText = '#' + id;
            document.getElementById('modal-target-text').innerText = targetName;
            document.getElementById('modal-desc-text').innerText = alasan;
            // Gunakan placeholder jika URL gambar kosong atau null
            document.getElementById('modal-img-bukti').src = imgUrl && imgUrl !== 'null' ? imgUrl : 'https://via.placeholder.com/400x300?text=Tidak+Ada+Bukti';

            // Set OnClick pada tombol Modal agar ID dinamis
            document.getElementById('modal-btn-warn').onclick = function() {
                kirimAksi('warn', id, targetName);
            };
            document.getElementById('modal-btn-block').onclick = function() {
                kirimAksi('block', id, targetName);
            };
            document.getElementById('modal-btn-reject').onclick = function() {
                kirimAksi('reject', id, targetName);
            };

            bukaModal('modalBukti');
        }

        // 2. Kirim Aksi ke Backend (AJAX FETCH)
        function kirimAksi(actionType, id, targetName) {
            tutupModal('modalBukti');

            let titleText = (actionType === 'warn') ? 'Kirim Peringatan?' :
                (actionType === 'block') ? 'Blokir Target?' : 'Tolak Laporan?';

            let confirmText = (actionType === 'block') ? 'Ya, Blokir' : 'Ya, Lanjutkan';
            let btnColor = (actionType === 'block') ? '#dc2626' : '#3085d6';

            Swal.fire({
                title: titleText,
                text: `Tindakan untuk target: ${targetName}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: btnColor,
                confirmButtonText: confirmText
            }).then((result) => {
                if (result.isConfirmed) {

                    // URL Route Backend
                    const url = `/admin/laporan/${id}/action`;

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                action: actionType
                            })
                        })
                        .then(response => {
                            if (response.ok) return response.json();
                            throw new Error('Gagal memproses');
                        })
                        .then(data => {
                            Swal.fire('Berhasil!', data.message, 'success').then(() => {
                                location.reload(); // Refresh halaman agar status berubah di tabel
                            });
                        })
                        .catch(error => {
                            console.error(error);
                            Swal.fire('Gagal!', 'Terjadi kesalahan sistem atau ID tidak ditemukan.', 'error');
                        });
                }
            });
        }

        // === TAB SWITCHER ===
        function switchTab(tabName, btn) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = 'none');
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('tab-' + tabName).style.display = 'block';
            btn.classList.add('active');
        }

        window.onclick = function(e) {
            if (e.target.classList.contains('modal-overlay')) e.target.style.display = 'none';
        }
    </script>

</body>

</html> 