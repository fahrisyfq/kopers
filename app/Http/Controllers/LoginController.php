<?php

namespace App\Http\Controllers;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function redirectToGoogle()
{
    return Socialite::driver('google')->redirect();
}

public function handleGoogleCallback()
{
    try {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Cek apakah user sudah ada
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            // Jika belum ada, buat user baru
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(uniqid()), // password dummy
            ]);
        }

        // Login user
        Auth::login($user, true);

        return redirect('/')->with('success', 'Berhasil login dengan akun Google!');
    } catch (\Exception $e) {
        return redirect('/')->with('error', 'Gagal login dengan Google: ' . $e->getMessage());
    }
}

public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email'    => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return response()->json([
                    'message' => 'Login successful',
                    'user' => Auth::user()->only('id', 'name', 'email'),
                ], 200);
            }

            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => [
            'required',
            'email',
            'max:255',
            'unique:users,email',
            // Validasi hanya email Google/gmail
            function ($attribute, $value, $fail) {
                if (!preg_match('/@gmail.com$/', $value)) {
                    $fail('Email harus menggunakan akun Gmail.');
                }
            }
        ],
        'password' => 'required|string|min:8|confirmed',
    ]);

    // (Opsional) Untuk validasi lebih kuat, gunakan Google API untuk cek apakah email benar-benar akun Google.

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);

    Auth::login($user);

    return response()->json(['success' => true]);
}


public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/'); // Redirect ke halaman utama
}
}
