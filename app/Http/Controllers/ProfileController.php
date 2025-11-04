<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // 🌟 Halaman Lengkapi Profil (untuk user baru)
    public function complete()
    {
        $user = Auth::guard('web')->user();
        if (!$user) return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');

        // Jika sudah lengkap, arahkan ke edit
        if (!empty($user->nis) && !empty($user->kelas) && !empty($user->jurusan)) {
            return redirect()->route('profile.edit')->with('success', 'Profil kamu sudah lengkap.');
        }

        return view('profile.complete', compact('user'));
    }

    // 🌟 Simpan Lengkapi Profil
    public function storeComplete(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string|max:50',
            'nis' => 'required|string|max:50',
            'nama_lengkap' => 'required|string|max:255',
            'kelas' => 'required|in:10,11,12',
            'jurusan' => 'required|in:AKL 1,AKL 2,AKL 3,MP 1,Manlog,BR 1,BR 2,BD,UPW,RPL,Belum Ditentukan',
            'no_telp_siswa' => 'nullable|regex:/^[0-9]{9,15}$/',
            'no_telp_ortu' => 'nullable|regex:/^[0-9]{9,15}$/',
        ]);

        $user = Auth::guard('web')->user();

        $user->update([
            'nisn' => $request->nisn,
            'nis' => $request->nis,
            'nama_lengkap' => $request->nama_lengkap,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'no_telp_siswa' => $request->no_telp_siswa ? '+62' . ltrim($request->no_telp_siswa, '0') : null,
            'no_telp_ortu' => $request->no_telp_ortu ? '+62' . ltrim($request->no_telp_ortu, '0') : null,
        ]);

        return redirect()->route('produk.index')->with('success', 'Profil berhasil dilengkapi.');
    }

    // 🌟 Halaman Edit Profil
    public function edit()
    {
        $user = Auth::guard('web')->user();
        if (!$user) return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');

        return view('profile.edit', compact('user'));
    }

    // 🌟 Update Profil
    public function update(Request $request)
    {
    $request->validate([
        'nis' => 'nullable|string|max:20',
        'kelas' => 'nullable|string|max:10',
        'jurusan' => 'nullable|string|max:50',
        'no_telp_siswa' => 'nullable|string|max:20',
        'no_telp_ortu' => 'nullable|string|max:20',
    ]);

        $user = Auth::guard('web')->user();
    $user->update([
        'nis' => $request->nis,
        'kelas' => $request->kelas,
        'jurusan' => $request->jurusan,
        'no_telp_siswa' => '+62' . ltrim($request->no_telp_siswa, '+62'),
        'no_telp_ortu' => '+62' . ltrim($request->no_telp_ortu, '+62'),
    ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
