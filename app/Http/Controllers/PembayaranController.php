<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Events;
use App\Models\PesertaKegiatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN ADMIN: LIST PEMBAYARAN
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') abort(403);

        // Sorting: Status 'pending' muncul paling atas, sisanya urut tanggal terbaru
        $payments = Pembayaran::with(['user', 'event'])
            ->orderByRaw("FIELD(status, 'pending') DESC")
            ->orderBy('created_at', 'desc')
            ->get();

        // Fitur Notifikasi untuk Admin
        $pendingCount = $payments->where('status', 'pending')->count();
        $notifications = [];
        if ($pendingCount > 0) {
            $notifications[] = (object)[
                'title' => 'Verifikasi Tertunda',
                'desc' => "Ada $pendingCount pembayaran menunggu konfirmasi.",
                'time' => 'Hari ini',
                'is_unread' => true,
                'color' => 'orange',
                'icon' => 'fa-regular fa-clock'
            ];
        }

        return view('admin.pembayaran', compact('payments', 'notifications'));
    }

    /*
    |--------------------------------------------------------------------------
    | FORM PEMBAYARAN (MEMBER)
    |--------------------------------------------------------------------------
    */
    public function create($eventId)
    {
        if (!Auth::check()) return redirect('/?login=1');

        $event = Events::where('berbayar', true)
            ->where('status', 'published')
            ->findOrFail($eventId);

        return view('events.payment', compact('event'));
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN BUKTI PEMBAYARAN (MEMBER)
    |--------------------------------------------------------------------------
    */
    public function store(Request $request, $eventId)
    {
        if (!Auth::check()) return redirect('/?login=1');

        $request->validate([
            'bukti_transfer' => 'required|image|max:2048',
            'jumlah_bayar'   => 'required|numeric|min:0',
        ]);

        // 🔥 FITUR KEAMANAN: Cegah pembayaran ganda untuk event yang sama
        $exists = Pembayaran::where('user_id', Auth::id())
            ->where('events_id', $eventId)
            ->whereIn('status', ['pending', 'lunas'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah mengirim pembayaran untuk event ini.');
        }

        $path = $request->file('bukti_transfer')->store('bukti_bayar', 'public');

        Pembayaran::create([
            'user_id'       => Auth::id(),
            'events_id'     => $eventId,
            'jumlah_bayar'  => $request->jumlah_bayar,
            'bukti_url'     => $path,
            'status'        => 'pending',
        ]);

        return redirect()
            ->route('events.show', $eventId)
            ->with('success', 'Bukti pembayaran terkirim. Menunggu verifikasi admin.');
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFIKASI PEMBAYARAN (ADMIN: APPROVE & REJECT)
    |--------------------------------------------------------------------------
    */
    public function verify(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') abort(403);

        $pembayaran = Pembayaran::findOrFail($id);

        if ($pembayaran->status !== 'pending') {
            return back()->with('error', 'Pembayaran sudah diproses.');
        }

        // 1. LOGIKA APPROVE (SETUJUI)
        if ($request->action === 'approve') {
            $pembayaran->update([
                'status'            => 'lunas',
                'diverifikasi_oleh' => $user->id,
            ]);

            // Daftarkan ke tabel peserta jika belum ada
            $exists = PesertaKegiatan::where('user_id', $pembayaran->user_id)
                ->where('events_id', $pembayaran->events_id)
                ->exists();

            if (!$exists) {
                // Gunakan status 'hadir' sesuai struktur database Anda
                PesertaKegiatan::create([
                    'user_id'   => $pembayaran->user_id,
                    'events_id' => $pembayaran->events_id,
                    'status'    => 'hadir',
                ]);
            }

            return back()->with('success', 'Pembayaran berhasil disetujui dan peserta terdaftar.');
        }

        // 2. LOGIKA REJECT (TOLAK)
        if ($request->action === 'reject') {
            $request->validate([
                'alasan' => 'required|string|max:255',
            ]);

            $pembayaran->update([
                'status'             => 'ditolak',
                'alasan_penolakan'   => $request->alasan,
                'diverifikasi_oleh'  => $user->id,
            ]);

            return back()->with('error', 'Pembayaran ditolak.');
        }

        return back();
    }
}