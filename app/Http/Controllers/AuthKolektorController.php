<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\Kolektor;
use App\Models\Admin;

class AuthKolektorController extends Controller
{
    // FORM LOGIN
    public function showLogin()
    {
        return view('auth.login');
    }

    // PROSES LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'role' => 'required|in:kolektor,admin',
            'username' => 'required_if:role,kolektor',
            'email' => 'required_if:role,admin|email',
            'password' => 'required',
        ]);

        if ($request->role === 'kolektor') {
            $user = Kolektor::where('username', $request->username)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                Session::put('kolektor', $user);
                Session::put('role', 'kolektor');
                return redirect()->route('home')
                    ->with('success', 'Selamat datang kembali, ' . $user->nama_kolektor . '!');
            }
        } else { // admin
            $admin = Admin::where('email', $request->email)->first();
            if ($admin && Hash::check($request->password, $admin->password)) {
                Session::put('admin', $admin);
                Session::put('role', 'admin');
                return redirect()->route('dashboard')
                    ->with('success', 'Selamat datang kembali, ' . $admin->nama_admin . '!');
            }
        }

        return back()->with('error', 'Login gagal: username/email atau password salah.');
    }

    // FORM REGISTER (hanya Kolektor)
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request) 
    {
        $request->validate([
            'nama_kolektor' => 'required',
            'kontak' => 'required',
            'alamat' => 'required',
            'jenis_kolektor' => 'required|in:individu,institusi',
            'username' => 'required|unique:kolektor,username',
            'password' => 'required|min:5'
        ]);

        $kolektor = Kolektor::create([
            'nama_kolektor' => $request->nama_kolektor,
            'kontak' => $request->kontak,
            'alamat' => $request->alamat,
            'jenis_kolektor' => $request->jenis_kolektor,
            'username' => $request->username,
            'password' => $request->password, // akan otomatis di-hash
        ]);

        Session::put('kolektor', $kolektor);
        Session::put('role', 'kolektor');

        return redirect()->route('home')
            ->with('success', 'Pendaftaran berhasil. Selamat datang, ' . $kolektor->nama_kolektor . '!');
    }

    // LOGOUT
    public function logout()
    {
        Session::forget('kolektor');
        Session::forget('admin');
        Session::forget('role');
        return redirect()->route('home')->with('success', 'Anda telah logout.');
    }
}
