<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Events;
use App\Models\PesanGrup;
use App\Models\PesertaKegiatan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read \App\Models\User $pelapor
 * @property-read mixed $target
 * @property-read string $target_nama
 * @property-read string $target_tipe
 * @property-read string $status_label
 */
class Laporan extends Model
{
    use HasFactory;
    protected $table = 'laporan';

    // Menggunakan fillable agar lebih aman (Mass Assignment Protection)
    protected $fillable = [
        'pelapor_id',
        'tipe_target', // user, kegiatan, pesan, peserta
        'target_id',
        'alasan',
        'bukti_path', // Untuk menyimpan foto bukti pelanggaran
        'status',     // pending, resolved, rejected
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS (GABUNGAN LOGIKA DINAMIS & UI)
    |--------------------------------------------------------------------------
    */

    /**
     * Mendapatkan Object asli dari target yang dilaporkan
     * Digunakan untuk logika backend (misal: hapus pesan otomatis)
     */
    public function getTargetAttribute()
    {
        switch ($this->tipe_target) {
            case 'user':
                return User::find($this->target_id);
            case 'kegiatan':
            case 'events': // Mendukung alias dari file teman
                return Events::find($this->target_id);
            case 'pesan':
                return PesanGrup::find($this->target_id);
            case 'peserta':
                return PesertaKegiatan::find($this->target_id);
            default:
                return null;
        }
    }

    /**
     * Mendapatkan Nama/Judul dari target yang dilaporkan
     * Digunakan di View: $item->target_nama
     */
    public function getTargetNamaAttribute()
    {
        $obj = $this->target;

        if (!$obj) return 'Data Tidak Ditemukan';

        switch ($this->tipe_target) {
            case 'user':
                return $obj->nama; // Pastikan kolom di tabel users adalah 'nama'
            case 'kegiatan':
            case 'events':
                return $obj->judul; // Mengikuti model Events yang sudah dimerge sebelumnya
            case 'pesan':
                return 'Pesan Grup (ID: ' . $this->target_id . ')';
            case 'peserta':
                return 'Partisipan: ' . ($obj->user->nama ?? 'User');
            default:
                return '-';
        }
    }

    /**
     * Label manusiawi untuk tipe target
     * Digunakan di View: $item->target_tipe
     */
    public function getTargetTipeAttribute()
    {
        $map = [
            'user'     => 'Pengguna',
            'events'   => 'Acara/Lomba',
            'kegiatan' => 'Kegiatan Internal',
            'pesan'    => 'Pesan Chat',
            'peserta'  => 'Peserta Event',
        ];

        return $map[$this->tipe_target] ?? ucfirst($this->tipe_target);
    }

    /**
     * Mengambil URL Bukti Foto (Jika ada)
     * Digunakan di View: $item->url
     */
    public function getUrlAttribute()
    {
        return $this->bukti_path 
            ? asset('storage/' . $this->bukti_path) 
            : 'https://via.placeholder.com/150?text=No+Bukti';
    }
    
    /**
     * Label status yang rapi untuk Dashboard Admin
     * Digunakan di View: $item->status_label
     */
    public function getStatusLabelAttribute()
    {
        $map = [
            'pending'  => 'Baru',
            'resolved' => 'Diselesaikan',
            'rejected' => 'Ditolak',
        ];

        return $map[$this->status] ?? $this->status;
    }
}