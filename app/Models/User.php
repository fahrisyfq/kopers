<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'nisn',
    'nis',
    'nama_lengkap',
    'kelas',
    'jurusan',
    'no_telp_siswa',
    'no_telp_ortu',
    'is_blocked',
];

    

    public function products() // Nama relasi yang lebih spesifik
    {
        return $this->belongsToMany(Product::class, 'product_user', 'user_id', 'product_id');
    }

    public function orders()
    {   
        return $this->hasMany(Order::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

//     public function getNotificationsAttribute()
// {
//     return $this->orders()
//         ->latest()
//         ->get()
//         ->map(function ($order) {
//             switch ($order->payment_status) {
//                 case 'pending':
//                     $message = "Silakan ke koperasi sekolah untuk mengukur seragam.";
//                     break;
//                 case 'cash':
//                     $message = "Jika ukuran sesuai, silakan segera bayar melalui pembayaran yang tersedia.";
//                     break;
//                 case 'paid':
//                     $message = "Pesanan Anda telah diambil dan dibayar. Terima kasih telah membeli seragam di koperasi.";
//                     break;
//                 default:
//                     $message = null;
//             }

//             return [
//                 'id' => $order->id,
//                 'status' => $order->payment_status,
//                 'message' => $message,
//             ];
//         })
//         ->filter(fn ($notif) => $notif['message'] !== null)
//         ->values();
// }

}
