<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

// 🔥 TAMBAHKAN 2 BARIS INI (Fix Unknown Class) 🔥
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $viewedUser = null;
        $recentEvents = collect(); // default kosong

        // =========================
        // PROFIL ORANG LAIN
        // =========================
        if ($slug = $request->query('user')) {
            $viewedUser = User::whereRaw(
                'LOWER(REPLACE(nama, " ", "-")) = ?',
                [Str::slug($slug)]
            )->first();

            if ($viewedUser) {
                $recentEvents = $viewedUser->events()
                    ->orderByDesc('peserta_kegiatan.created_at')
                    ->limit(3)
                    ->get();
            }
        }

        // =========================
        // PROFIL SENDIRI
        // =========================
        else if (Auth::check()) { // Gunakan Auth::check() atau auth()->check()
            $recentEvents = Auth::user()
                ->events()
                ->orderByDesc('peserta_kegiatan.created_at')
                ->limit(3)
                ->get();
        }

        return view('profile', compact('viewedUser', 'recentEvents'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            // Validasi Username (Nama): Harus unik, kecuali milik user ini sendiri
            'nama' => 'required|string|max:100|unique:users,nama,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'bio' => 'nullable|string',
            'foto_profil' => 'nullable|image|max:2048', // Max 2MB
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Update Data (Tanpa No Telepon & Nama Lengkap terpisah)
        $user->nama = $request->nama; // Ini adalah Username
        $user->email = $request->email;
        $user->bio = $request->bio;

        // Update Foto
        if ($request->hasFile('foto_profil')) {
            // 1. Ambil filenya
            $file = $request->file('foto_profil');
            
            // 2. Buat nama file unik (gabungan waktu + nama asli) agar tidak bentrok
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // 3. Pindahkan fisik file ke folder 'public/image/avatar'
            $file->move(public_path('image/avatar'), $filename);
            
            // 4. Simpan path relatifnya ke database
            $user->foto_profil_url = 'image/avatar/' . $filename;
        }

        // Update Password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama salah']);
            }
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.show')->with('status', 'Profil berhasil diperbarui!');
    }
}