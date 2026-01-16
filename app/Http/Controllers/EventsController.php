<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Events;
use App\Models\Kota;
use App\Models\Kategori;
use App\Models\PesertaKegiatan;
use App\Models\User;
use App\Models\AnggotaKomunitas;
use App\Models\Komunitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | BAGIAN PUBLIK (USER)
    |--------------------------------------------------------------------------
    */

    /**
     * Cari Event / Lomba (Public)
     */
    public function index(Request $request)
    {
        $kategori_list = Kategori::all();
        $kota_list = Kota::all();

        $query = Events::with(['kategori', 'kota'])
            ->where('status', 'published')
            ->where('type', 'lomba');

        if ($request->has('kategori') && !empty($request->kategori)) {
            $query->whereIn('kategori_id', $request->kategori);
        }

        if ($request->has('kota') && !empty($request->kota)) {
            $query->whereIn('kota_id', $request->kota);
        }

        $events = $query->orderBy('start_date', 'asc')->get();

        return view('events.event', compact('events', 'kategori_list', 'kota_list'));
    }

    /**
     * Detail Event (Public)
     */
    public function show($id)
    {
        $event = Events::with(['pengusul', 'komunitas', 'kategori', 'kota'])
            ->where('status', 'published')
            ->findOrFail($id);

        return view('events.detail-lomba', compact('event'));
    }

    /**
     * Riwayat Event User
     */
    public function riwayat()
    {
        $user = Auth::user();
        if (!$user) return redirect('/?login=1');

        $upcomingEvents = Events::whereHas('participants', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status', 'published')
            ->orderBy('start_date', 'asc')
            ->get();

        $pastEvents = Events::whereHas('participants', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status', 'finished')
            ->orderBy('start_date', 'desc')
            ->get();

        return view('events.riwayat-event', compact('upcomingEvents', 'pastEvents'));
    }

    /*
    |--------------------------------------------------------------------------
    | PENDAFTARAN & KLAIM XP
    |--------------------------------------------------------------------------
    */

    public function showRegisterForm($id)
    {
        $event = Events::where('status', 'published')->findOrFail($id);
        
        $exists = PesertaKegiatan::where('user_id', Auth::id())
            ->where('events_id', $id)
            ->exists();

        if ($exists) {
            return redirect()->route('events.show', $id)->with('error', 'Anda sudah terdaftar.');
        }

        return view('events.form-lomba', compact('event'));
    }

    public function storeRegistration(Request $request, $id)
    {
        $request->validate([
            'no_wa' => 'required|string|max:20',
        ]);

        $event = Events::findOrFail($id);
        $user = Auth::user();

        if (!$event->berbayar) {
            PesertaKegiatan::firstOrCreate([
                'user_id'   => $user->id,
                'events_id' => $id,
            ]);
            return redirect()->route('events.show', $id)->with('success', 'Pendaftaran berhasil!');
        }

        // Paid Event: Redirect to Payment
        return redirect()->route('pembayaran.create', $id);
    }

    public function klaimXP(Request $request, $id)
    {
        $user = Auth::user();
        $event = Events::findOrFail($id);
        
        $peserta = PesertaKegiatan::where('user_id', $user->id)
            ->where('events_id', $event->id)
            ->firstOrFail();

        if ($peserta->status !== null) {
            return back()->with('error', 'XP sudah diklaim.');
        }

        $request->validate(['bukti_foto' => 'nullable|image|max:2048']);

        $xpGained = $request->hasFile('bukti_foto') ? 50 : 10;
        
        if ($request->hasFile('bukti_foto')) {
            $peserta->bukti_url = $request->file('bukti_foto')->store('bukti_event', 'public');
        }

        $peserta->status = 'hadir';
        $peserta->review_text = $request->review;
        $peserta->save();

        $user->increment('xp_terkini', $xpGained);

        return back()->with('success', "XP Berhasil diklaim! +$xpGained XP");
    }

    /*
    |--------------------------------------------------------------------------
    | MANAJEMEN ADMIN (DASHBOARD)
    |--------------------------------------------------------------------------
    */

    public function adminIndex()
    {
        // 1. Ambil Data Lomba
        $lombaList = Events::with(['kategori', 'kota'])
            ->where('type', 'lomba')
            ->latest()
            ->get();

        // 2. Tambahkan Data Notifikasi (Agar tidak error di Blade)
        // Anda bisa mengosongkannya [] atau memberi data dummy
        $notifications = []; 
        $unreadCount = 0;

        // 3. Kirim ke View dengan compact
        return view('admin.lomba', compact('lombaList', 'notifications', 'unreadCount'));
    }

    public function adminShow($id)
    {
        $event = Events::with(['participants', 'kategori', 'kota'])->findOrFail($id);
        $kategori = Kategori::all();
        $kota = Kota::all();
        $totalPeserta = $event->participants->count();

        return view('admin.kelola-lomba', compact('event', 'kategori', 'kota', 'totalPeserta'));
    }

    public function adminUpdate(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:150',
            'start_date' => 'required',
        ]);

        $event = Events::findOrFail($id);
        
        // Clean price format
        $hargaClean = $request->harga ? preg_replace('/[^0-9]/', '', $request->harga) : 0;
        $event->harga = (float) $hargaClean;
        $event->berbayar = ($event->harga > 0);

        $event->judul = $request->judul;
        $event->kategori_id = $request->kategori_id;
        $event->kota_id = $request->wilayah;
        $event->start_date = $request->start_date;

        if ($request->hasFile('poster')) {
            $event->poster_url = $request->file('poster')->store('posters', 'public');
        }

        $event->save();
        return back()->with('success', 'Data lomba diperbarui.');
    }

    public function adminFinish($id)
    {
        $event = Events::findOrFail($id);
        $event->status = 'finished';
        $event->save();

        return back()->with('success', 'Lomba berhasil diakhiri.');
    }

    /*
    |--------------------------------------------------------------------------
    | FORM BUAT EVENT (MODERATOR/ADMIN)
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $user = Auth::user();
        $isModerator = AnggotaKomunitas::where('user_id', $user->id)->where('role', 'moderator')->exists();

        if ($user->role !== 'admin' && !$isModerator) abort(403);

        $kategori = Kategori::all();
        $komunitas_moderated = Komunitas::whereIn('id', function ($query) use ($user) {
            $query->select('komunitas_id')->from('anggota_komunitas')
                ->where('user_id', $user->id)->where('role', 'moderator');
        })->get();

        return view('events.create', compact('kategori', 'komunitas_moderated'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'judul' => 'required|string|max:150',
            'type' => 'required|in:lomba,kegiatan',
            'start_date' => 'required|date',
        ]);

        $hargaClean = $request->harga ? preg_replace('/[^0-9]/', '', $request->harga) : 0;

        $event = new Events();
        $event->judul = $request->judul;
        $event->deskripsi = $request->deskripsi;
        $event->type = $request->type;
        $event->kategori_id = $request->kategori_id;
        $event->diusulkan_oleh = $user->id;
        $event->start_date = $request->start_date;
        $event->harga = (float) $hargaClean;
        $event->berbayar = ($event->harga > 0);
        $event->status = 'published';

        if ($request->hasFile('poster')) {
            $event->poster_url = $request->file('poster')->store('posters', 'public');
        }

        $event->save();

        return redirect()->route('events.show', $event->id)->with('success', 'Event berhasil dibuat.');
    }
}