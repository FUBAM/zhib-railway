<?php

namespace App\Http\Controllers;

use App\Models\Komunitas;
use App\Models\Events;
use App\Models\User;
use App\Models\AnggotaKomunitas;
use App\Models\Laporan;
use App\Models\Kota;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class KomunitasController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | BAGIAN PUBLIK (USER)
    |--------------------------------------------------------------------------
    */

    /**
     * Tampilkan List "Cari Komunitas" dengan Filter
     */
    public function index()
    {
        $kota_list = Kota::all();
        $kategori_list = Kategori::all();

        $query = Komunitas::withCount(['anggota as jumlah_anggota'])
            ->with(['kota', 'kategori']);

        // Logika: Sembunyikan komunitas yang user SUDAH join agar tidak double
        if (Auth::check()) {
            $userId = Auth::id();
            $query->whereDoesntHave('anggota', function($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        }

        $komunitas = $query->get();

        return view('komunitas.cari-komunitas', compact('komunitas', 'kota_list', 'kategori_list'));
    }

    /**
     * Halaman Detail Komunitas
     */
    public function show($id)
    {
        $komunitas = Komunitas::with(['kota', 'kategori'])->findOrFail($id);

        $isMember = false;
        if (Auth::check()) {
            $isMember = AnggotaKomunitas::where('komunitas_id', $komunitas->id)
                ->where('user_id', Auth::id())
                ->exists();
        }

        return view('komunitas.show', compact('komunitas', 'isMember'));
    }

    /**
     * Proses Gabung Komunitas & Tambah XP
     */
    public function join(Request $request)
    {
        $request->validate([
            'komunitas_id' => 'required|exists:komunitas,id',
        ]);

        $userId = Auth::id();
        if (! $userId) {
            return redirect('/?login=1');
        }

        $exists = AnggotaKomunitas::where('user_id', $userId)
            ->where('komunitas_id', $request->komunitas_id)
            ->exists();

        if ($exists) {
            return back()->with('info', 'Anda sudah menjadi anggota komunitas ini.');
        }

        AnggotaKomunitas::create([
            'user_id'      => $userId,
            'komunitas_id' => $request->komunitas_id,
            'role'         => 'member',
        ]);

        // Tambah XP join komunitas (+20 XP)
        User::where('id', $userId)->increment('xp_terkini', 20);

        return back()->with('success', 'Selamat bergabung dengan komunitas!');
    }

    /*
    |--------------------------------------------------------------------------
    | BAGIAN ANGGOTA (USER DASHBOARD)
    |--------------------------------------------------------------------------
    */

    /**
     * List Komunitas yang diikuti User (Komunitas Saya)
     */
    public function myCommunities(Request $request)
    {
        $userId = Auth::id();
        if (! $userId) {
            return redirect('/?login=1');
        }

        $q = trim($request->get('q', ''));

        // Eager load 'grup' agar bisa langsung buka chat dari kartu komunitas
        $query = Komunitas::with('grup')->whereHas('anggota', function ($q2) use ($userId) {
            $q2->where('user_id', $userId);
        });

        if ($q !== '') {
            foreach (preg_split('/\s+/', $q) as $token) {
                $query->where(function ($qq) use ($token) {
                    $qq->where('nama', 'like', "%{$token}%")
                        ->orWhere('deskripsi', 'like', "%{$token}%");
                });
            }
        }

        $komunitas = $query->get();

        return view('komunitas.komunitas-saya', compact('komunitas', 'q'));
    }

    /**
     * List Event per Komunitas (Internal + Global)
     */
    public function events($komunitasId)
    {
        $komunitas = Komunitas::with('grup')->findOrFail($komunitasId);

        $kegiatan = Events::where('type', 'kegiatan')
            ->where('komunitas_id', $komunitasId)
            ->where('status', 'published')
            ->orderBy('start_date', 'asc')
            ->get();

        $lomba = Events::where('type', 'lomba')
            ->where('kategori_id', $komunitas->kategori_id)
            ->where('status', 'published')
            ->orderBy('start_date', 'asc')
            ->get();

        return view('komunitas.grup-event', compact('komunitas', 'kegiatan', 'lomba'));
    }

    /*
    |--------------------------------------------------------------------------
    | BAGIAN ADMIN (MANAJEMEN PLATFORM)
    |--------------------------------------------------------------------------
    */

    /**
     * List Manajemen Komunitas (Dashboard Admin)
     */
    public function adminList()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') abort(403);

        $komunitas_list = Komunitas::with(['kota', 'kategori'])
            ->withCount('anggota')
            ->withCount(['anggota as moderator_count' => function ($query) {
                $query->where('anggota_komunitas.role', 'moderator');
            }])
            ->latest()
            ->get();

        $stats = (object)[
            'total_komunitas' => Komunitas::count(),
            'moderator_aktif' => AnggotaKomunitas::where('role', 'moderator')->count(),
            'total_anggota'   => AnggotaKomunitas::count(),
        ];

        return view('admin.komunitas', compact('komunitas_list', 'stats'));
    }

    /**
     * Simpan Komunitas Baru oleh Admin
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $request->validate([
            'nama'        => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'kota_id'     => 'required|exists:kota,id',
            'deskripsi'   => 'nullable|string',
        ]);

        Komunitas::create([
            'nama'        => $request->nama,
            'slug'        => Str::slug($request->nama),
            'kategori_id' => $request->kategori_id,
            'kota_id'     => $request->kota_id,
            'deskripsi'   => $request->deskripsi,
        ]);

        return back()->with('success', 'Komunitas berhasil ditambahkan');
    }

    /**
     * List Laporan Pending (Admin)
     */
    public function listLaporan()
    {
        $laporan = Laporan::where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.laporan', compact('laporan'));
    }
}