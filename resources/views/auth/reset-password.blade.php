@extends('layouts.app')

@section('title', 'Konfirmasi Pemulihan Sandi | ZHIB')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/reset.css') }}">
@endpush

@section('content')
<main class="reset-container">
  <h2>KONFIRMASI PEMULIHAN SANDI</h2>

  <img src="{{ asset('image/icon/reset.png') }}" class="reset-icon">

  <p class="reset-desc">
    Hore!! kamu sudah terverifikasi. Sekarang masukkan password baru kamu
    di bawah ini. Jangan sampai lupa lagi ya!
  </p>

  <form class="reset-form" onsubmit="handleReset(event)">
    <label>Password Baru</label>
    <input type="password" placeholder="Masukkan Sandi Baru">

    <label>Ulangi Password Baru</label>
    <input type="password" placeholder="Ulangi Sandi Baru">

    <button type="submit">Konfirmasi</button>
  </form>
</main>
@endsection

@section('scripts')
<script>
  function handleReset(e) {
    e.preventDefault(); // stop submit default
    // Redirect ke Landing Page dan buka Popup Login
    window.location.href = "{{ url('/') }}?login=1";
  }
</script>
@endsection