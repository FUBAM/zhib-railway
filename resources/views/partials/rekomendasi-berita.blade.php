<section class="rekomendasi-berita">
    <div class="section-container">

        <h2 class="section-title">REKOMENDASI BERITA</h2>

        <div class="berita-grid">

            @forelse ($berita as $item)
            <article class="berita-card">
                <a href="{{ route('berita.detail', $item->id) }}" class="berita-link">

                    <div class="berita-thumbnail">
                        <img src="{{ asset($item->thumbnail ?? 'image/berita/default.jpg') }}" alt="{{ $item->judul }}">
                    </div>

                    <div class="berita-content">
                        <h3 class="berita-title">
                            {{ \Illuminate\Support\Str::limit($item->judul, 60) }}
                        </h3>

                        <p class="berita-excerpt">
                            {{ \Illuminate\Support\Str::limit($item->ringkasan, 80) }}
                        </p>
                    </div>

                </a>
            </article>
            @empty
            {{-- fallback jika belum ada berita --}}
            @for ($i = 0; $i < 6; $i++) <div class="berita-card placeholder">
                <div class="berita-thumbnail skeleton"></div>
                <div class="berita-content">
                    <div class="skeleton title"></div>
                    <div class="skeleton text"></div>
                </div>
        </div>
        @endfor
        @endforelse

    </div>
    </div>
</section>