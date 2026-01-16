<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property-read \App\Models\User $pengusul
 * @property-read \App\Models\Komunitas|null $komunitas
 * @property-read \App\Models\Kota|null $kota
 * @property-read \App\Models\Kategori $kategori
 *
 * @property-read string $jenis
 * @property-read bool $is_berbayar
 * @property-read string|null $poster
 * @property-read string $url
 * @property-read string $harga_label
 * @property-read string $tanggal
 */
class Events extends Model
{
    use HasFactory;
    protected $table = 'events';

    protected $fillable = [
        'kategori_id',
        'komunitas_id',
        'kota_id',
        'diusulkan_oleh',
        'type',
        'judul',
        'deskripsi',
        'berbayar',
        'harga',
        'poster_url',
        'status',
        'start_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'berbayar'   => 'boolean',
        'harga'      => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS (GABUNGAN: ADAPTER & TAMPILAN BLADE)
    |--------------------------------------------------------------------------
    */

    // Dari Events.php asli: Untuk logika internal
    public function getJenisAttribute(): string { return $this->type; }
    public function getIsBerbayarAttribute(): bool { return (bool) $this->berbayar; }
    public function getPosterAttribute(): ?string { return $this->poster_url; }

    // Dari Events_Teman.php: Untuk mempercantik tampilan Blade
    
    // Mengambil URL Poster (Jika null pakai default placeholder)
    public function getUrlAttribute()
    {
        return $this->poster_url ? asset('storage/' . $this->poster_url) : 'https://via.placeholder.com/400x200?text=No+Poster';
    }

    // Mengambil Nama Kategori
    public function getKategoriLabelAttribute()
    {
        return $this->kategori->nama ?? 'Umum';
    }

    // Icon Kategori berdasarkan nama (FontAwesome)
    public function getIconKategoriAttribute()
    {
        $kat = strtolower($this->kategori_label);
        if (str_contains($kat, 'tech') || str_contains($kat, 'code')) return 'fa-code';
        if (str_contains($kat, 'art') || str_contains($kat, 'desain')) return 'fa-palette';
        if (str_contains($kat, 'sport')) return 'fa-person-running';
        return 'fa-trophy';
    }   

    // Lokasi Singkat
    public function getLokasiSingkatAttribute()
    {
        return $this->kota->nama ?? 'Online';
    }

    // Format Tanggal Indonesia (Contoh: 15 Desember 2025)
    public function getTanggalAttribute()
    {
        return $this->start_date ? $this->start_date->translatedFormat('d F Y') : '-';
    }

    // Format Harga Rupiah (Contoh: Rp 50.000 atau Gratis)
    public function getHargaLabelAttribute()
    {
        return $this->harga > 0 ? 'Rp ' . number_format($this->harga, 0, ',', '.') : 'Gratis';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS (MENGGUNAKAN STRUKTUR ASLI ANDA)
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kota_id');
    }

    public function community()
    {
        return $this->belongsTo(Komunitas::class, 'komunitas_id');
    }

    public function proposer()
    {
        return $this->belongsTo(User::class, 'diusulkan_oleh');
    }

    public function participants()
    {
        // Tetap menggunakan 'using' PesertaKegiatan agar fitur review & XP tidak rusak
        return $this->belongsToMany(User::class, 'peserta_kegiatan', 'events_id', 'user_id')
            ->using(PesertaKegiatan::class)
            ->withPivot('status', 'bukti_url', 'review_text')
            ->withTimestamps();
    }

    /* ALIAS UNTUK KOMPATIBILITAS ACCESSOR TEMAN */
    public function pengusul() { return $this->proposer(); }
    public function komunitas() { return $this->community(); }
    public function kategori() { return $this->category(); }
}