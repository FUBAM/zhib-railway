<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Helper untuk cek akses admin
     */
    private function isAdmin()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | LIST LAPORAN (ADMIN DASHBOARD)
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->isAdmin();

        // Mengambil data terkategorisasi agar tampilan dashboard rapi (Tabs)
        // Yang berstatus 'pending' akan diutamakan ke atas

        // 1. Tab Pengguna (Tipe 'user')
        $laporanPengguna = Laporan::where('tipe_target', 'user')
            ->orderByRaw("status = 'pending' DESC")
            ->latest()
            ->get();

        // 2. Tab Pesan (Tipe 'pesan')
        $laporanPesan = Laporan::where('tipe_target', 'pesan')
            ->orderByRaw("status = 'pending' DESC")
            ->latest()
            ->get();

        // 3. Tab Acara (Tipe 'events' atau 'kegiatan')
        $laporanAcara = Laporan::whereIn('tipe_target', ['events', 'kegiatan'])
            ->orderByRaw("status = 'pending' DESC")
            ->latest()
            ->get();

        // 4. Tab Peserta (Tipe 'peserta')
        $laporanPeserta = Laporan::where('tipe_target', 'peserta')
            ->orderByRaw("status = 'pending' DESC")
            ->latest()
            ->get();

        return view('admin.laporan', compact(
            'laporanPengguna', 
            'laporanPesan', 
            'laporanAcara', 
            'laporanPeserta'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | PROSES TINDAKAN LAPORAN
    |--------------------------------------------------------------------------
    */
    public function resolve(Request $request, $id)
    {
        $this->isAdmin();

        $laporan = Laporan::findOrFail($id);
        $action = $request->input('action', 'resolve'); // Default 'resolve'

        if ($laporan->status !== 'pending') {
            return back()->with('info', 'Laporan ini sudah diproses sebelumnya.');
        }

        if ($action === 'reject') {
            // Skenario: Laporan dianggap tidak valid (Ditolak)
            $laporan->status = 'rejected';
            $pesan = 'Laporan telah ditolak.';
        } else {
            // Skenario: Laporan valid (Diselesaikan)
            
            // 🔥 LOGIKA SANKSI: Jika target adalah peserta (klaim palsu), kurangi skor kepercayaan
            if ($laporan->tipe_target === 'peserta') {
                $targetUser = User::find($laporan->target_id);
                if ($targetUser) {
                    $targetUser->decrement('skor_kepercayaan', 5);
                }
            }

            $laporan->status = 'resolved';
            $pesan = 'Laporan berhasil diselesaikan.';
        }

        $laporan->save();

        return back()->with('success', $pesan);
    }
}