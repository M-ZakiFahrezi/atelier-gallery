<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kolektor;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // FORM REGISTER KOLEKTOR
    public function showRegister()
    {
        return view('auth.register');
    }

    // PROSES REGISTER KOLEKTOR
    public function register(Request $request)
    {
$request->validate([
    'nama_kolektor' => 'required|string|max:100',
    'alamat' => 'required|string|max:255',
    'kontak' => 'required|string|max:30',
    'email' => 'required|email|unique:kolektor,email', // <--- tambahkan
    'jenis_kolektor' => 'required|in:individu,institusi',
    'username' => 'required|unique:kolektor,username',
    'password' => 'required|min:5|confirmed',
]);

$kolektor = Kolektor::create([
    'nama_kolektor' => $request->nama_kolektor,
    'alamat' => $request->alamat,
    'kontak' => $request->kontak,
    'email' => $request->email, // <--- tambahkan
    'jenis_kolektor' => $request->jenis_kolektor,
    'username' => $request->username,
    'password' => Hash::make($request->password),
]);


        // Langsung login setelah register
        Session::put('kolektor', $kolektor);
        Session::put('role', 'user');

        return redirect()->route('kolektor.profil')
            ->with('success', 'Registrasi berhasil! Selamat datang, ' . $kolektor->nama_kolektor);
    }

    // PROSES LOGIN
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $role = $request->role;

        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($role === 'admin') {
            $user = Admin::where('username', $request->username)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                session(['admin' => $user]);
                return redirect()->route('admin.dashboard');
            }
        } else {
            $user = Kolektor::where('username', $request->username)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                session(['kolektor' => $user]);
                return redirect()->route('kolektor.dashboard');
            }
        }

        return back()->withErrors(['loginError' => 'Username atau password salah.']);
    }

    public function logout(Request $request)
    {
        session()->flush();
        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }

    // UPDATE PROFILE KOLEKTOR
    public function updateProfile(Request $request)
    {
        if (!session()->has('kolektor')) {
            return redirect()->route('login')->withErrors(['loginError' => 'Silakan login terlebih dahulu.']);
        }

        $kolektor = session('kolektor');

        $request->validate([
            'nama_kolektor' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'kontak' => 'required|string|max:30',
            'username' => 'required|string|max:50',
            'password' => 'nullable|string|min:5|confirmed'
        ]);

        $model = Kolektor::find($kolektor->id_kolektor);
        $model->nama_kolektor = $request->nama_kolektor;
        $model->alamat = $request->alamat;
        $model->kontak = $request->kontak;
        $model->username = $request->username;

        if ($request->filled('password')) {
            $model->password = Hash::make($request->password);
        }

        $model->save();

        // Update session
        session(['kolektor' => $model]);

        return redirect()->route('kolektor.profil')->with('success', 'Profil berhasil diperbarui!');
    }
}
