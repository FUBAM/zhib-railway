@extends('layouts.app')
@extends('styles.berita')

@section('content')

<section class="news-page">
    <div class="article-container">

        <h1 class="article-title">{{ $berita->judul }}</h1>

            @if($berita->gambar_url)
            <img class="article-img" src="{{ asset($berita->gambar_url) }}">
            @endif

        <div class="article-content">
            <p>{!! nl2br(e($berita->konten)) !!}</p>
        </div>

    </div>
</section>
@endsection