<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use App\Models\Order;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class UserStatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        // Pastikan class ini sesuai dengan Page List kamu
        return ListUsers::class;
    }

    // Helper untuk bikin grafik trend pendaftaran siswa 30 hari terakhir
    protected function getRegistrationTrend()
    {
        return User::where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count')
            ->toArray();
    }

    protected function getStats(): array
    {
        // Ambil query dari Tabel (sudah kena filter search/filter table)
        $query = $this->getPageTableQuery();

        // 1. Hitung Total Siswa (Sesuai Filter)
        $totalSiswa = (clone $query)->count();

        // 2. Hitung Siswa Aktif
        $siswaAktif = (clone $query)->where('is_blocked', false)->count();

        // 3. Hitung Siswa Diblokir
        $siswaBlokir = (clone $query)->where('is_blocked', true)->count();

        // 4. Hitung Total Transaksi dari Siswa yang tampil
        // Kita cari ID siswa yang ada di query saat ini, lalu hitung order mereka
        $userIds = (clone $query)->pluck('id');
        $totalTransaksi = Order::whereIn('user_id', $userIds)
            ->where('payment_status', '!=', 'cancelled') // Hanya hitung pesanan valid
            ->count();

        return [
            Stat::make('Total Siswa', $totalSiswa)
                ->description('Siswa terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->chart($this->getRegistrationTrend()) // Grafik pendaftaran
                ->color('primary'),

            Stat::make('Siswa Aktif', $siswaAktif)
                ->description('Bisa login & belanja')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Siswa Diblokir', $siswaBlokir)
                ->description('Akun ditangguhkan')
                ->descriptionIcon('heroicon-m-lock-closed')
                ->color('danger'),

            Stat::make('Total Pesanan', $totalTransaksi)
                ->description('Akumulasi pesanan siswa')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning'),
        ];
    }
}