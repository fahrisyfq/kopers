<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function kirim(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        $data = [
            'nama' => $request->name,
            'email' => $request->email,
            'telepon' => $request->phone,
            'pesan' => $request->message,
        ];

        Mail::send('emails.kontak', $data, function($mail) use ($data) {
            $mail->to('fahriahmad2768@gmail.com')
                 ->subject('Pesan Baru dari Formulir Kontak')
                 ->from($data['email'], $data['nama']);
        });

        return back()->with('success', 'Pesan kamu berhasil dikirim! 🎉');
    }
}
