<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Lomba: {{ $event->judul }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- GLOBAL & LAYOUT --- */
        body {
            background-color: #F8F9FD;
            font-family: 'Inter', sans-serif;
        }

        .main-content {
            padding: 30px;
        }

        /* Header Halaman */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #4F46E5;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        /* Grid Utama (Kiri Form, Kanan Peserta) */
        .kelola-grid {
            display: grid;
            grid-template-columns: 1.6fr 1fr;
            /* Rasio 60:40 */
            gap: 24px;
            align-items: start;
        }

        /* Kartu Putih Pembungkus */
        .white-card {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 5px 0;
        }

        .card-subtitle {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 24px;
            display: block;
        }

        /* --- FORM & INPUT (DIPERBAIKI) --- */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }

        /* FIX UTAMA: 
           Memaksa semua input punya tinggi dan box-model yang sama 
        */
        .form-control {
            width: 100%;
            height: 48px;
            /* Tinggi fix agar seragam */
            padding: 0 16px;
            /* Padding kiri-kanan */
            background-color: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            font-size: 14px;
            color: #334155;
            transition: all 0.2s;
            box-sizing: border-box;
            /* Pastikan padding tidak menambah lebar */
            appearance: none;
            /* Reset style browser */
            display: flex;
            align-items: center;
        }

        /* Styling khusus input date/time agar teks vertikal center */
        input[type="date"],
        input[type="time"] {
            line-height: 46px;
        }

        /* Dropdown arrow custom (opsional, agar panah dropdown rapi) */
        select.form-control {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 16px;
        }

        .form-control:focus {
            border-color: #4F46E5;
            outline: none;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        /* --- NOTIFIKASI SUKSES (BARU) --- */
        .alert-success {
            background-color: #DCFCE7;
            /* Hijau muda */
            border: 1px solid #86EFAC;
            color: #166534;
            /* Hijau teks */
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* --- UPLOAD POSTER --- */
        .poster-upload {
            border: 2px dashed #E2E8F0;
            background: #F8FAFC;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: 0.2s;
            position: relative;
        }

        .poster-upload:hover {
            border-color: #4F46E5;
            background: #EEF2FF;
        }

        .upload-icon {
            font-size: 24px;
            color: #94A3B8;
            margin-bottom: 10px;
        }

        .upload-text {
            font-size: 14px;
            color: #4F46E5;
            font-weight: 600;
        }

        .upload-sub {
            font-size: 12px;
            color: #94A3B8;
            margin-top: 4px;
        }

        .poster-preview {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
            display: none;
        }

        .poster-preview.active {
            display: block;
        }

        /* --- TOMBOL AKSI --- */
        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }

        .btn {
            padding: 0 20px;
            /* Padding kiri kanan */
            height: 44px;
            /* Tinggi tombol */
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-save {
            background: #4F46E5;
            color: white;
            flex: 1;
        }

        .btn-save:hover {
            background: #4338ca;
        }

        .btn-danger {
            background: #EF4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-outline {
            background: white;
            border: 1px solid #E2E8F0;
            color: #334155;
        }

        .btn-outline:hover {
            background: #F8FAFC;
            border-color: #CBD5E1;
        }

        /* --- TABEL PESERTA (KANAN) --- */
        .peserta-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .total-badge {
            font-size: 14px;
            color: #64748b;
        }

        .list-header {
            display: grid;
            grid-template-columns: 2fr 1.5fr;
            background: #1E293B;
            /* Header Hitam */
            color: white;
            padding: 12px 16px;
            border-radius: 8px 8px 0 0;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .peserta-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .peserta-item {
            display: grid;
            grid-template-columns: 2fr 1.5fr;
            padding: 16px;
            border-bottom: 1px solid #F1F5F9;
            align-items: center;
        }

        .peserta-item:last-child {
            border-bottom: none;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            background: #E0E7FF;
            color: #4F46E5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
        }

        .user-details h4 {
            margin: 0;
            font-size: 14px;
            color: #1e293b;
            font-weight: 600;
        }

        .user-details p {
            margin: 2px 0 0;
            font-size: 12px;
            color: #64748b;
        }

        /* Badge Status */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-confirmed {
            background: #DCFCE7;
            color: #166534;
            border: 1px solid #86EFAC;
        }

        .status-pending {
            background: #FEF9C3;
            color: #854D0E;
            border: 1px solid #FDE047;
        }

        .status-rejected {
            background: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FCA5A5;
        }

        /* Responsive Mobile */
        @media (max-width: 900px) {
            .kelola-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="admin-container">
        @include('admin.partials.sidebar')
        <main class="main-content">
            <div class="page-header">
                <div>
                    <h2 style="color: #4F46E5;">ZHIB Admin</h2>
                    <span style="color: #94A3B8; font-size: 14px;">Regional DIY</span>
                </div>

                <div style="display: flex; gap: 15px; align-items: center;">
                    <input type="text" placeholder="Cari anggota, acara..." style="padding: 10px 15px; border-radius: 20px; border: 1px solid #E2E8F0; width: 250px; background: #fff;">
                    <i class="fa-regular fa-bell" style="font-size: 20px; color: #64748b; cursor: pointer;"></i>
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid #E2E8F0; margin-bottom: 20px;">

            <div style="margin-bottom: 20px;">
                <h1 style="font-size: 28px; font-weight: 800; color: #1e293b; margin-bottom: 5px;">Kelola Lomba: {{ $event->judul }}</h1>
                <a href="{{ route('admin.lomba') }}" class="back-link">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Lomba
                </a>
            </div>

            <div class="kelola-grid">

                <div class="white-card">
                    <h3 class="card-title">Pengaturan Lomba</h3>
                    <span class="card-subtitle">Edit detail dan konfigurasi lomba</span>

                    @if(session('success'))
                    <div class="alert-success">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    @endif

                    @if($errors->any())
                    <div style="background: #FEE2E2; color: #991B1B; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('admin.lomba.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Judul Lomba</label>
                            <input type="text" name="judul" class="form-control" value="{{ old('judul', $event->judul) }}" required>
                        </div>

                        <div class="form-group">
                            <label>Harga Pendaftaran (IDR)</label>
                            <input type="text" name="harga" id="hargaInput" class="form-control"
                                value="{{ old('harga', number_format($event->harga, 0, ',', '.')) }}" placeholder="0">
                        </div>

                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="kategori_id" class="form-control" style="appearance: auto;">
                                @foreach($kategori as $kat)
                                <option value="{{ $kat->id }}" {{ $event->kategori_id == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($event->start_date)->format('Y-m-d') }}">
                            </div>
                            <div class="form-group">
                                <label>Jam Mulai</label>
                                <input type="time" name="jam_mulai" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Lokasi (Wilayah)</label>
                            <select name="wilayah" class="form-control" style="appearance: auto;">
                                @foreach($kota as $k)
                                <option value="{{ $k->id }}" {{ $event->kota_id == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Lokasi Detail (Opsional)</label>
                            <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $event->lokasi ?? '') }}" placeholder="Contoh: Gedung Serbaguna Lt. 2">
                        </div>

                        <div class="form-group">
                            <label>Poster Lomba</label>
                            @if($event->url)
                            <img src="{{ $event->url }}" class="poster-preview active" id="imgPreview">
                            @endif

                            <div class="poster-upload" onclick="document.getElementById('filePoster').click()">
                                <i class="fa-solid fa-arrow-up-from-bracket upload-icon"></i>
                                <div class="upload-text">Klik untuk unggah</div>
                                <div class="upload-sub">PNG, JPG hingga 2MB</div>
                                <input type="file" name="poster" id="filePoster" style="display:none" onchange="previewImage(this)">
                            </div>
                        </div>

                        <div class="action-buttons">
                            <button type="submit" class="btn btn-save">
                                <i class="fa-regular fa-floppy-disk"></i> Simpan Perubahan
                            </button>

                            <button type="button" class="btn btn-danger" onclick="confirmFinish()">
                                <i class="fa-solid fa-ban"></i> Akhiri Lomba
                            </button>

                        </div>
                    </form>
                </div>

                <div class="white-card">
                    <div class="peserta-header">
                        <h3 class="card-title" style="margin:0;">Daftar Peserta</h3>
                        <div class="total-badge">Total peserta: {{ $event->participants->count() }}</div>
                    </div>

                    <div class="list-header">
                        <div>PESERTA</div>
                        <div>PEMBAYARAN</div>
                    </div>

                    <ul class="peserta-list">
                        @forelse($event->participants as $peserta)
                        <li class="peserta-item">
                            <div class="user-info">
                                <div class="avatar">
                                    {{ strtoupper(substr($peserta->nama, 0, 2)) }}
                                </div>
                                <div class="user-details">
                                    <h4>{{ $peserta->nama }}</h4>
                                    <p>{{ $peserta->asal_instansi ?? 'Umum' }}</p>
                                </div>
                            </div>

                            <div>
                                @php
                                // Ambil status dari pivot table
                                $status = $peserta->pivot->status ?? 'pending';
                                $badgeClass = 'status-pending';
                                $label = 'Tertunda';
                                $icon = 'fa-clock';

                                if($status == 'confirmed' || $status == 'success') {
                                $badgeClass = 'status-confirmed';
                                $label = 'Terkonfirmasi';
                                $icon = 'fa-check-circle';
                                } elseif($status == 'rejected') {
                                $badgeClass = 'status-rejected';
                                $label = 'Ditolak';
                                $icon = 'fa-times-circle';
                                }
                                @endphp

                                <span class="status-badge {{ $badgeClass }}">
                                    <i class="fa-regular {{ $icon }}"></i> {{ $label }}
                                </span>
                            </div>
                        </li>
                        @empty
                        <li style="padding: 20px; text-align: center; color: #94A3B8;">
                            Belum ada peserta yang mendaftar.
                        </li>
                        @endforelse
                    </ul>
                </div>

            </div>
        </main>
    </div>

    <form id="formFinish" action="{{ route('admin.lomba.finish', $event->id) }}" method="POST" style="display: none;">
        @csrf
        @method('PUT')
    </form>

    <script>
        // Fungsi Konfirmasi Akhiri Lomba
        function confirmFinish() {
            if (confirm('Apakah Anda yakin ingin mengakhiri lomba ini? Status akan berubah menjadi selesai.')) {
                document.getElementById('formFinish').submit();
            }
        }

        function previewImage(input) {
            const preview = document.getElementById('imgPreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Format Rupiah saat mengetik
        const hargaInput = document.getElementById('hargaInput');
        hargaInput.addEventListener('keyup', function(e) {
            let value = this.value.replace(/[^,\d]/g, '').toString();
            let split = value.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            this.value = rupiah;
        });
    </script>
</body>

</html>