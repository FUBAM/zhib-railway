<div class="hof-grid">
    @forelse ($users as $user)
    <a href="{{ url('/profile') }}?user={{ \Illuminate\Support\Str::slug($user->nama) }}" class="hof-card">
        <img class="avatar" src="{{ asset($user->foto_profil_url ?? 'image/avatar/avatar-default.jpg') }}"
            alt="{{ $user->nama }}">

        <h4>{{ $user->nama }}</h4>
        <h6>Level {{ $user->level_terkini }}</h6>

        <div class="badges">
            @forelse ($user->badges as $badge)
            <img src="{{ asset($badge->image_url ?? 'image/badges/badge (1).png') }}"
                alt="Badge {{ $badge->nama ?? '' }}">
            @empty
            {{-- Jika user belum punya badge, biarkan kosong --}}
            @endforelse
        </div>
    </a>
    @empty
    <p style="padding-left: 482.5px;">Tidak ada data Hall of Fame</p>
    @endforelse
</div>