<section class="pilihan-event">
    <div class="section-container">

        <div class="section-header">
            <h2 class="section-title">PILIHAN EVENT</h2>
            <p class="section-subtitle">
                Jelajahi event menarik dan menangkan pengalaman berharga
            </p>
        </div>

        <div class="event-carousel-wrapper">

            {{-- Carousel --}}
            <div class="event-carousel" id="eventCarousel">

                @forelse ($events as $event)
                <div class="event-card">

                    @guest
                    <a href="#" onclick="openLogin(); return false;" class="event-link">
                        @else
                        <a href="{{ route('events.show', $event->id) }}" class="event-link">
                            @endguest

                            <div class="event-poster">
                                <img src="{{ asset($event->poster ?? 'image/event/default.jpg') }}"
                                    alt="{{ $event->judul }}">
                            </div>

                            <div class="event-info">
                                <h3 class="event-title">
                                    {{ \Illuminate\Support\Str::limit($event->judul, 40) }}
                                </h3>

                                <p class="event-meta">
                                    {{ $event->is_berbayar ? 'Berbayar' : 'Gratis' }}
                                    • {{ \Illuminate\Support\Str::upper($event->jenis) }}
                                </p>

                                @if(!empty($event->harga))
                                <div class="event-price">
                                    Rp {{ number_format($event->harga, 0, ',', '.') }}
                                </div>
                                @endif
                            </div>

                        </a>
                </div>
                @empty
                {{-- Placeholder jika belum ada event --}}
                @for ($i = 0; $i < 6; $i++) <div class="event-card placeholder">
                    <div class="event-poster skeleton"></div>
                    <div class="event-info">
                        <div class="skeleton title"></div>
                        <div class="skeleton text"></div>
                    </div>
            </div>
            @endfor
            @endforelse

        </div>

        {{-- Button Right --}}


    </div>

    </div>
</section>

@push('scripts')
<script>
function scrollEventCarousel(direction) {
    const carousel = document.getElementById('eventCarousel');
    if (!carousel) return;

    const cardWidth = carousel.querySelector('.event-card')?.offsetWidth || 260;
    carousel.scrollBy({
        left: direction * cardWidth * 2,
        behavior: 'smooth'
    });
}
</script>
@endpush