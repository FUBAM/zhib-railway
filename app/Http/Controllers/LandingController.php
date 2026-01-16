<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Events;
use App\Models\Berita;

class LandingController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | HALL OF FAME
        |--------------------------------------------------------------------------
        | User member dengan XP tertinggi
        */
        $hallOfFame = User::where('role', 'member')
            ->orderByDesc('xp_terkini')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | PILIHAN EVENT (LOMBA)
        |--------------------------------------------------------------------------
        | Event lomba terbaru yang sudah dipublish
        */
        $events = Events::where('type', 'lomba')
            ->where('status', 'published')
            ->orderByDesc('created_at')
            ->limit(10  )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | REKOMENDASI BERITA
        |--------------------------------------------------------------------------
        | Berita terbaru yang sudah dipublish
        */
        $berita = Berita::where('status', 'published')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        return view('landing.index', compact(
            'hallOfFame',
            'events',
            'berita'
        ));
    }
}