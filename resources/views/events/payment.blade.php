@extends('layouts.app')

@section('title', 'Pembayaran - ' . $event->judul)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        /* Fix Navbar tertutup */
        .payment-page {
            padding-top: 120px;
            min-height: 80vh;
            background-color: #f9f9f9;
            padding-bottom: 50px;
        }

        /* Styling tambahan untuk area upload agar lebih rapi */
        .upload-area {
            border: 2px dashed #ccc;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            position: relative;
            background: #fff;
            transition: all 0.3s;
        }
        
        .upload-area:hover {
            border-color: #000;
            background: #fdfdfd;
        }

        .file-input {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            opacity: 0;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
<main class="payment-page">
    <div class="payment-container">
        
        {{-- BAGIAN KIRI: FORMULIR --}}
        <div class="payment-form">
            <h2 style="margin-bottom: 20px; font-size: 20px;">Konfirmasi Transfer</h2>

            {{-- 🔥 ACTION FORM MENGARAH KE ROUTE YANG BENAR 🔥 --}}
            <form id="payment-form" action="{{ route('pembayaran.store', $event->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                {{-- INPUT HIDDEN UNTUK VALIDASI CONTROLLER --}}
                <input type="hidden" name="jumlah_bayar" value="{{ $event->harga }}">

                <div class="form-group">
                    <label>Nama Pemilik Rekening</label>
                    <input type="text" name="nama_rekening" placeholder="Nama pengirim di bukti transfer" required value="{{ old('nama_rekening', Auth::user()->nama) }}">
                </div>

                <div class="form-group">
                    <label>Tanggal Transfer</label>
                    <input type="date" name="tanggal_transfer" required value="{{ date('Y-m-d') }}">
                </div>

                <div class="form-group">
                    <label>Unggah Bukti Transfer</label>
                    <div class="upload-area">
                        <div class="upload-content">
                            <i class="fa-regular fa-image upload-icon" style="font-size: 32px; color: #666; margin-bottom: 10px;"></i>
                            <p class="upload-text" id="fileName">Klik atau Drag file bukti transfer di sini</p>
                            <p class="upload-sub" style="font-size: 12px; color: #999;">JPG, PNG, PDF (Max 2MB)</p>
                        </div>
                        {{-- Input File --}}
                        <input type="file" name="bukti_transfer" class="file-input" required onchange="updateFileName(this)">
                    </div>
                </div>
            </form>
        </div>

        {{-- BAGIAN KANAN: RINGKASAN --}}
        <div class="payment-summary">
            <div class="summary-card">
                <h3>RINGKASAN PEMBAYARAN</h3>
                <p class="event-name">{{ $event->judul }}</p>
                <div class="price-tag">Rp {{ number_format($event->harga, 0, ',', '.') }}</div>
            </div>

            <div class="instruction-box">
                <h4>Instruksi Transfer:</h4>
                <p>Silakan transfer nominal di atas ke salah satu rekening berikut:</p>
                
                {{-- Data Rekening (Bisa dibuat dinamis nanti) --}}
                <div class="bank-list">
                    <div class="bank-row">
                        <span class="bank-label">BCA:</span>
                        <span class="bank-value">1234 567 890 <br><small>(a.n ZHIB JOGJA)</small></span>
                    </div>
                    <div class="bank-row" style="margin-top: 10px;">
                        <span class="bank-label">DANA / GOPAY:</span>
                        <span class="bank-value">0812 3456 7890 <br><small>(a.n Admin Zhib)</small></span>
                    </div>
                </div>
            </div>

            <button class="btn-confirm" onclick="document.getElementById('payment-form').submit()">
                Kirim Bukti Pembayaran
            </button>

            <div class="security-note">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Bukti akan diverifikasi manual oleh Admin (1x24 Jam).</span>
            </div>
        </div>

    </div>
</main>
@endsection

@push('scripts')
<script>
    // Script sederhana untuk mengubah teks saat file dipilih
    function updateFileName(input) {
        const fileNameElement = document.getElementById('fileName');
        if (input.files && input.files.length > 0) {
            fileNameElement.innerText = "File Terpilih: " + input.files[0].name;
            fileNameElement.style.fontWeight = "bold";
            fileNameElement.style.color = "#000";
        } else {
            fileNameElement.innerText = "Klik atau Drag file bukti transfer di sini";
            fileNameElement.style.fontWeight = "normal";
        }
    }
</script>
@endpush