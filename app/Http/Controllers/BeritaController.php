<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    /**
     * Helper untuk cek apakah user adalah admin.
     */
    private function checkAdmin()
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'admin') {
            abort(403);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | LIST BERITA (ADMIN)
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->checkAdmin();

        $berita = Berita::latest()->get();

        // Fitur dari Teman: Statistik Berita untuk Dashboard Admin
        $countAll = $berita->count();
        $countPublished = $berita->where('status', 'published')->count();
        $countDraft = $berita->where('status', 'draft')->count();

        return view('admin.berita', compact('berita', 'countAll', 'countPublished', 'countDraft'));
    }

    /*
    |--------------------------------------------------------------------------
    | FORM TAMBAH BERITA
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $this->checkAdmin();
        return view('admin.berita.create');
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN BERITA
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'judul'   => 'required|string|max:255',
            'konten'  => 'required|string',
            'gambar'  => 'nullable|image|max:2048',
            'status'  => 'required|in:draft,published',
        ]);

        // Gabungan: Slug unik dengan timestamp
        $slug = Str::slug($request->judul) . '-' . time();

        $data = [
            'user_id' => Auth::id(),
            'judul'   => $request->judul,
            'slug'    => $slug,
            'konten'  => $request->konten,
            'status'  => $request->status,
        ];

        if ($request->hasFile('gambar')) {
            $data['gambar_url'] = $request->file('gambar')->store('berita', 'public');
        }

        Berita::create($data);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil disimpan.');
    }

    /*
    |--------------------------------------------------------------------------
    | FORM EDIT BERITA
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $this->checkAdmin();
        $berita = Berita::findOrFail($id);
        return view('admin.berita.edit', compact('berita'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE BERITA
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $this->checkAdmin();
        $berita = Berita::findOrFail($id);

        $request->validate([
            'judul'   => 'required|string|max:255',
            'konten'  => 'required|string',
            'gambar_url'  => 'nullable|image|max:2048', // Sesuaikan dengan nama di Blade
            'status'  => 'required|in:draft,published',
        ]);

        $berita->judul  = $request->judul;
        $berita->konten = $request->konten;
        $berita->status = $request->status;
        $berita->slug   = Str::slug($request->judul) . '-' . time();

        // Periksa input 'gambar_url' sesuai dengan file Blade
        if ($request->hasFile('gambar_url')) {
            // Hapus gambar lama agar tidak menumpuk di storage
            if ($berita->gambar_url && Storage::disk('public')->exists($berita->gambar_url)) {
                Storage::disk('public')->delete($berita->gambar_url);
            }
            // Simpan file baru ke folder 'berita'
            $berita->gambar_url = $request->file('gambar_url')->store('image/berita', 'public');
        }

        $berita->save();

        return redirect()->route('admin.berita')->with('success', 'Berita berhasil diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS BERITA
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $this->checkAdmin();
        $berita = Berita::findOrFail($id);

        // Fitur dari Teman: Hapus file gambar fisik dari storage sebelum hapus record database
        if ($berita->gambar_url && Storage::disk('public')->exists($berita->gambar_url)) {
            Storage::disk('public')->delete($berita->gambar_url);
        }

        $berita->delete();

        return back()->with('success', 'Berita berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL BERITA (PUBLIC)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $berita = Berita::where('status', 'published')->findOrFail($id);
        return view('detail-berita', compact('berita'));
    }
}