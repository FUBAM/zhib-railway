<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN (POPUP VIA LANDING)
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->login;

        // Cari user berdasarkan email ATAU nama
        $user = User::where('email', $login)
            ->orWhere('nama', $login)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return redirect('/?login=1')
                ->withErrors(['login' => 'Username / Email atau password salah'])
                ->withInput();
        }

        Auth::login($user);
        $request->session()->regenerate();

        // Redirect sesuai role (TANPA method custom)
        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('home'));
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER (POPUP)
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100|unique:users,nama',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'terms'    => 'accepted',
        ], [
            'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan.',
        ]);

        User::create([
            'nama'          => $request->username,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'role'          => 'member',
            'xp_terkini'    => 0,
            'level_terkini' => 1,
        ]);

        return redirect('/?login=1')
            ->with('status', 'Akun berhasil dibuat. Silakan login.');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD DISPATCHER
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect('/');
        }

        if ($user->role === 'admin') {
            return view('admin.index');
        }

        return view('dashboard.member.index');
    }

    /*
    |--------------------------------------------------------------------------
    | BACKWARD COMPATIBILITY
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        return redirect('/?login=1');
    }

    public function showRegister()
    {
        return redirect('/?register=1');
    }
}
