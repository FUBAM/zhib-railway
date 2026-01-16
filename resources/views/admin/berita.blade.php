<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Manajemen Berita - ZHIB Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lomba.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-berita.css') }}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

    <div class="admin-container">

        {{-- Menggunakan Partial Sidebar agar sesuai struktur folder Anda --}}
        @include('admin.partials.sidebar')

        <main class="main-content">

            <header class="topbar">
                <h1>Manajemen Berita</h1>
                {{-- Search bar & Notif sesuai desain --}}
                <div class="topbar-right">
                    <div class="notif-wrapper">
                        <button class="btn-icon"><i class="fa-regular fa-bell"></i></button>
                    </div>
                </div>
            </header>

            <div class="berita-header-sec">
                <div>
                    <h2>Manajemen Berita</h2>
                    <p>Kelola berita dan pengumuman untuk komunitas DIY</p>
                </div>
                <button class="btn-primary" onclick="bukaModal('modalBuatBerita')">
                    <i class="fa-solid fa-plus"></i> Buat Berita Baru
                </button>
            </div>

            {{-- Tabs Filter --}}
            <div class="berita-tabs">
                <button class="tab-pill active" onclick="filterBerita('all', this)">Semua ({{ $countAll }})</button>
                <button class="tab-pill" onclick="filterBerita('published', this)">Dipublikasi ({{ $countPublished }})</button>
                <button class="tab-pill" onclick="filterBerita('draft', this)">Draf ({{ $countDraft }})</button>
            </div>

            {{-- Flash Message --}}
            @if(session('success'))
            <div style="background: #dcfce7; color: #166534; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
            @endif

            {{-- CONTAINER LIST --}}
            <div class="berita-list-container">
                @forelse($berita as $item)
                {{-- CARD ITEM --}}
                <div class="news-card" data-status="{{ $item->status }}">

                    {{-- Bagian Gambar (Kiri) --}}
                    <div class="nc-image-wrapper">
                        @if($item->gambar_url)
                        <img src="{{ asset( $item->gambar_url ?? 'image/berita/berita-default.jpg') }}" alt="Thumbnail">
                        @else
                        <img src="https://via.placeholder.com/300x200?text=No+Image" alt="No Image">
                        @endif

                        {{-- Badge Status --}}
                        <span class="nc-badge {{ $item->status }}">
                            {{ $item->status == 'published' ? 'Dipublikasi' : 'Draf' }}
                        </span>
                    </div>

                    {{-- Bagian Konten (Kanan) --}}
                    <div class="nc-content">
                        <h3>{{ $item->judul }}</h3>
                        <p>{{ Str::limit(strip_tags($item->konten), 150) }}</p>

                        <div class="nc-meta">
                            <span><i class="fa-regular fa-calendar"></i> {{ $item->created_at->format('Y-m-d') }}</span>
                            {{-- Jika ada kategori/lokasi statis --}}
                            <span><i class="fa-solid fa-location-dot"></i> DIY</span>
                        </div>

                        <div class="nc-actions">
                            {{-- TOMBOL EDIT (FIXED: Pakai data attribute biar tidak merah) --}}
                            <button class="btn-outline-blue"
                                data-id="{{ $item->id }}"
                                data-judul="{{ $item->judul }}"
                                data-konten="{{ $item->konten }}"
                                onclick="editBerita(this)">
                                <i class="fa-regular fa-pen-to-square"></i> Edit
                            </button>

                            {{-- TOMBOL HAPUS --}}
                            <button class="btn-outline-red"
                                data-id="{{ $item->id }}"
                                onclick="hapusBerita(this)">
                                <i class="fa-regular fa-trash-can"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div style="text-align:center; width:100%; padding: 40px; color: #666;">
                    <p>Belum ada berita yang dibuat.</p>
                </div>
                @endforelse
            </div>

        </main>
    </div>

    {{-- MODAL BUAT BERITA --}}
    <div class="modal-overlay" id="modalBuatBerita" style="display: none;">
        <div class="modal-box medium-box">
            <div class="modal-header">
                <h3>Buat Berita Baru</h3>
                <span class="close-icon" onclick="tutupModal('modalBuatBerita')">&times;</span>
            </div>
            <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Judul Berita *</label>
                        <input type="text" name="judul" placeholder="Masukkan judul..." required>
                    </div>
                    <div class="form-group">
                        <label>Konten Berita *</label>
                        <textarea name="konten" rows="5" placeholder="Tulis isi berita..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Gambar</label>
                        <div class="upload-dashed-area" onclick="document.getElementById('file-upload-new').click()">
                            <i class="fa-solid fa-cloud-arrow-up" id="icon-upload-new"></i>
                            <p class="upload-text-main" id="text-upload-new">Klik untuk unggah</p>
                            <img id="preview-new" src="" style="display:none; max-width:100%; border-radius:8px; margin-top:10px;">
                            <input type="file" id="file-upload-new" name="gambar_url" style="display:none" onchange="previewImage(this, 'new')">
                        </div>
                    </div>
                </div>
                <div class="modal-footer-between">
                    <button type="button" class="btn-cancel" onclick="tutupModal('modalBuatBerita')">Batal</button>
                    <div class="right-btns">
                        <button type="submit" name="status" value="draft" class="btn-outline-purple">Simpan Draf</button>
                        <button type="submit" name="status" value="published" class="btn-submit">Publikasikan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT BERITA --}}
    <div class="modal-overlay" id="modalEditBerita" style="display: none;">
        <div class="modal-box medium-box">
            <div class="modal-header">
                <h3>Edit Berita</h3>
                <span class="close-icon" onclick="tutupModal('modalEditBerita')">&times;</span>
            </div>
            <form id="formEditBerita" action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Judul Berita *</label>
                        <input type="text" name="judul" id="edit_judul" required>
                    </div>
                    <div class="form-group">
                        <label>Konten Berita *</label>
                        <textarea name="konten" id="edit_konten" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Ganti Gambar</label>
                        <div class="upload-dashed-area" onclick="document.getElementById('file-upload-edit').click()">
                            <i class="fa-regular fa-image" id="icon-upload-edit"></i>
                            <p class="upload-text-main" id="text-upload-edit">Upload Gambar Baru</p>
                            <img id="preview-edit" src="" style="display:none; max-width:100%; border-radius:8px; margin-top:10px;">
                            <input type="file" id="file-upload-edit" name="gambar_url" style="display:none" onchange="previewImage(this, 'edit')">
                        </div>
                    </div>
                </div>
                <div class="modal-footer-between">
                    <button type="button" class="btn-cancel" onclick="tutupModal('modalEditBerita')">Batal</button>
                    <div class="right-btns">
                        <button type="submit" name="status" value="draft" class="btn-outline-purple">Simpan Draf</button>
                        <button type="submit" name="status" value="published" class="btn-submit">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL HAPUS BERITA --}}
    <div class="modal-overlay" id="modalHapusBerita" style="display: none;">
        <div class="modal-box small-box">
            <div class="modal-header-simple">
                <h3>Hapus Berita?</h3>
                <span class="close-icon" onclick="tutupModal('modalHapusBerita')">&times;</span>
            </div>
            <div class="modal-body">
                <p>Berita ini akan dihapus permanen.</p>
            </div>
            <div class="modal-footer-simple">
                <button type="button" class="btn-cancel" onclick="tutupModal('modalHapusBerita')">Batal</button>
                <form id="formHapusBerita" action="" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-submit" style="background-color: #ef4444;">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT --}}
    <script>
        function bukaModal(id) {
            document.getElementById(id).style.display = 'flex';
        }

        function tutupModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        // Filter Tabs
        function filterBerita(status, btn) {
            document.querySelectorAll('.tab-pill').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('.news-card').forEach(card => {
                if (status === 'all' || card.getAttribute('data-status') === status) {
                    card.style.display = 'flex'; // Kembali ke Flex agar layout card benar
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Logic Edit (Mengambil data dari tombol yang diklik)
        function editBerita(element) {
            let id = element.getAttribute('data-id');
            let judul = element.getAttribute('data-judul');
            let konten = element.getAttribute('data-konten');

            // Set URL Action Form
            let url = "{{ url('/admin/berita') }}/" + id;
            document.getElementById('formEditBerita').action = url;

            // Isi Input
            document.getElementById('edit_judul').value = judul;
            document.getElementById('edit_konten').value = konten;

            bukaModal('modalEditBerita');
        }

        // Logic Hapus
        function hapusBerita(element) {
            let id = element.getAttribute('data-id');
            let url = "{{ url('/admin/berita') }}/" + id;
            document.getElementById('formHapusBerita').action = url;
            bukaModal('modalHapusBerita');
        }

        // Tutup modal jika klik di luar
        window.onclick = function(e) {
            if (e.target.classList.contains('modal-overlay')) e.target.style.display = 'none';
        }

        function previewImage(input, type) {
    const preview = document.getElementById('preview-' + type);
    const icon = document.getElementById('icon-upload-' + type);
    const text = document.getElementById('text-upload-' + type);

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block'; // Tampilkan gambar
            if(icon) icon.style.display = 'none'; // Sembunyikan ikon
            if(text) text.style.display = 'none'; // Sembunyikan teks
        }

        reader.readAsDataURL(input.files[0]);
    }
}

    // Reset preview saat modal ditutup
    function tutupModal(id) {
        document.getElementById(id).style.display = 'none';
        // Opsional: Reset form jika diperlukan
        // document.querySelector(`#${id} form`).reset();
    }
    </script>
</body>

</html>