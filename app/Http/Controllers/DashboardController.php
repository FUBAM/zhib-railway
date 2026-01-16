<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Events;
use App\Models\Pembayaran;
use App\Models\Komunitas;
use App\Models\Laporan;
use App\Models\AnggotaKomunitas;
use App\Models\Grup;
use App\Models\PesanGrup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX UTAMA (AUTO REDIRECT BERDASARKAN ROLE)
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $user = Auth::user();
        if (!$user) return redirect('/');

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard.member.index');
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ADMIN PUSAT (ADVANCED METRICS)
    |--------------------------------------------------------------------------
    */
    public function adminIndex()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

        // --- 1. Live Metrics (Statistik Utama) ---
        $totalAnggota = User::where('role', 'member')->count();
        $pembayaranTertunda = Pembayaran::where('status', 'pending')->count();
        $lombaAktif = Events::where('type', 'lomba')->where('status', 'published')->count(); 
        $laporanTerbuka = Laporan::where('status', 'pending')->count();

        // --- 2. Aktivitas Terkini (Gabungan 4 Model) ---
        $latestUsers = User::latest()->take(4)->get()->map(function($u) {
            return (object)['type' => 'user', 'text' => "{$u->nama} mendaftar sebagai anggota baru.", 'time' => $u->created_at];
        });

        $latestPayments = Pembayaran::with('user')->latest()->take(4)->get()->map(function($p) {
            $name = $p->user ? $p->user->nama : 'Pengguna';
            return (object)['type' => 'payment', 'text' => "Pembayaran baru diterima dari <strong>{$name}</strong>", 'time' => $p->created_at];
        });

        $latestEvents = Events::latest()->take(4)->get()->map(function($e) {
            return (object)['type' => 'event', 'text' => "Lomba baru dipublikasikan: {$e->judul}", 'time' => $e->created_at];
        });

        $latestReports = Laporan::with('pelapor')->latest()->take(4)->get()->map(function($r) {
            $name = $r->pelapor ? $r->pelapor->nama : 'Pengguna';
            return (object)['type' => 'report', 'text' => "Laporan kendala baru dari <strong>{$name}</strong>", 'time' => $r->created_at];
        });

        $activities = collect()
            ->concat($latestUsers)->concat($latestPayments)->concat($latestEvents)->concat($latestReports)
            ->sortByDesc('time')->take(6);

        // --- 3. Komunitas Paling Aktif (Ranking Anggota) ---
        $komunitasAktif = Komunitas::withCount('anggota')
            ->orderBy('anggota_count', 'desc')
            ->take(5)
            ->get();

        // --- 4. Logika Notifikasi Lonceng (Item Pending) ---
        $notifications = collect();
        
        $pendingPays = Pembayaran::with('user')->where('status', 'pending')->latest()->take(3)->get();
        foreach($pendingPays as $p) {
            $notifications->push((object)[
                'id' => $p->id,
                'type' => 'payment',
                'icon_class' => 'fa-regular fa-credit-card',
                'color_class' => 'green',
                'title' => 'Pembayaran Tertunda',
                'description' => "Dari " . ($p->user->nama ?? 'User'),
                'time' => $p->created_at->diffForHumans(),
                'is_unread' => true
            ]);
        }

        $pendingReps = Laporan::with('pelapor')->where('status', 'pending')->latest()->take(2)->get();
        foreach($pendingReps as $r) {
            $notifications->push((object)[
                'id' => $r->id,
                'type' => 'report',
                'icon_class' => 'fa-solid fa-triangle-exclamation',
                'color_class' => 'red',
                'title' => 'Laporan Baru',
                'description' => $r->alasan ?? 'Keluhan pengguna baru',
                'time' => $r->created_at->diffForHumans(),
                'is_unread' => true
            ]);
        }

        $unreadCount = $notifications->count();

        return view('admin.dashboard', compact(
            'totalAnggota', 'pembayaranTertunda', 'lombaAktif', 'laporanTerbuka',
            'activities', 'komunitasAktif', 'notifications', 'unreadCount'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER & LOGIKA MODERATOR KOMUNITAS
    |--------------------------------------------------------------------------
    */

    private function checkAccess($komunitasId)
    {
        $user = Auth::user();
        $isModerator = AnggotaKomunitas::where('user_id', $user->id)
            ->where('komunitas_id', $komunitasId)
            ->where('role', 'moderator')
            ->exists();

        if (!$isModerator && $user->role !== 'admin') {
            abort(403, 'Akses Ditolak. Anda bukan moderator komunitas ini.');
        }
        return $user;
    }

    public function moderatorIndex($komunitasId)
    {
        $this->checkAccess($komunitasId);
        $komunitas = Komunitas::withCount('anggota')->findOrFail($komunitasId);

        $kegiatan_internal = Events::where('komunitas_id', $komunitasId)
            ->where('type', 'kegiatan')
            ->latest()
            ->get();

        return view('dashboard.moderator.index', compact('komunitas', 'kegiatan_internal'));
    }

    public function moderatorChat($komunitasId)
    {
        $this->checkAccess($komunitasId);
        
        $komunitas = Komunitas::with('grup')->findOrFail($komunitasId);
        $grup = $komunitas->grup->first();
        
        if(!$grup) return back()->with('error', 'Grup chat belum dibuat.');

        $messages = PesanGrup::with('user')
            ->where('grup_id', $grup->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('moderator.chat', compact('komunitas', 'grup', 'messages'));
    }

    public function moderatorEvents($komunitasId)
    {
        $this->checkAccess($komunitasId);
        $komunitas = Komunitas::findOrFail($komunitasId);

        $kegiatan = Events::where('komunitas_id', $komunitasId)
            ->where('type', 'kegiatan')
            ->orderBy('start_date', 'asc')
            ->get();
            
        $lomba = Events::where('type', 'lomba')
            ->where('kategori_id', $komunitas->kategori_id)
            ->orderBy('start_date', 'asc')
            ->get();

        return view('moderator.events', compact('komunitas', 'kegiatan', 'lomba'));
    }
}