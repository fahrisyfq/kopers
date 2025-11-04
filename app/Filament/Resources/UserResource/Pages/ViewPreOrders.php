<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;

class ViewPreOrders extends Page
{
    protected static string $resource = UserResource::class;
    protected static string $view = 'filament.resources.user-resource.pages.view-preorders';
    protected static ?string $title = 'Daftar Pre-Order';

    public $usersWithPreOrders = [];
    public $statuses = [];
    public $selectedItems = [];
    public $selectedKelas = '';
    public $selectedJurusan = '';
    public $searchTerm = '';

    public function mount(): void
    {
        $this->loadPreOrders();
    }

    public function updatedSelectedKelas(): void
    {
        $this->loadPreOrders();
    }

    public function updatedSelectedJurusan(): void
    {
        $this->loadPreOrders();
    }

    public function updatedSearchTerm(): void
    {
        $this->loadPreOrders();
    }

    public function loadPreOrders(): void
    {
        $query = User::query()
            ->when($this->selectedKelas, fn($q) => $q->where('kelas', $this->selectedKelas))
            ->when($this->selectedJurusan, fn($q) => $q->where('jurusan', $this->selectedJurusan))
            ->when($this->searchTerm, function ($q) {
                $search = '%' . $this->searchTerm . '%';
                $q->where(function ($sub) use ($search) {
                    $sub->where('nama_lengkap', 'like', $search)
                        ->orWhere('nisn', 'like', $search)
                        ->orWhere('nis', 'like', $search);
                });
            })
            ->whereHas('orders.items', function ($q) {
                $q->where('is_preorder', true)
                  ->where('preorder_status', 'waiting');
            })
            ->with(['orders.items' => function ($q) {
                $q->where('is_preorder', true)
                  ->where('preorder_status', 'waiting')
                  ->with(['product', 'productSize']);
            }]);

        $this->usersWithPreOrders = $query->get();

        $this->statuses = [];
        foreach ($this->usersWithPreOrders as $user) {
            foreach ($user->orders as $order) {
                $this->statuses[$order->id] = $order->payment_status;
            }
        }
    }

    #[On('refreshPreOrders')]
    public function updateStatus($orderId): void
    {
        $order = Order::find($orderId);

        if ($order && isset($this->statuses[$orderId])) {
            $newStatus = $this->statuses[$orderId];
            $order->update(['payment_status' => $newStatus]);

            Notification::make()
                ->title('Status pembayaran diubah ke ' . ucfirst($newStatus))
                ->success()
                ->duration(2500)
                ->send();

            $this->loadPreOrders();
        }
    }

    /* ------------------------- 🔹 Hubungi Siswa ------------------------- */
    public function contactUser($userId): void
    {
        $this->sendWhatsAppMessage($userId, 'siswa');
    }

    /* ------------------------- 🔹 Hubungi Orang Tua ------------------------- */
    public function contactParent($userId): void
    {
        $this->sendWhatsAppMessage($userId, 'ortu');
    }

    /* ------------------------- 🔹 Fungsi Umum ------------------------- */
    private function sendWhatsAppMessage($userId, $target): void
    {
        $user = User::find($userId);
        if (!$user) return;

        // Hanya ambil item pre-order yang dipilih
        $selected = collect($this->selectedItems)
            ->filter(fn($v) => $v === true)
            ->keys()
            ->map(fn($id) => OrderItem::where('id', $id)
                ->where('is_preorder', true)
                ->where('preorder_status', 'waiting')
                ->first())
            ->filter(fn($item) => $item && $item->order->user_id === $user->id);

        if ($selected->isEmpty()) {
            Notification::make()
                ->title('Pilih minimal satu produk pre-order terlebih dahulu.')
                ->warning()
                ->send();

            $this->skipRender();
            return;
        }

        $productList = $selected->map(function ($item) {
            $title = $item->product->title ?? '-';
            $size = $item->productSize->size ?? '-';
            $price = number_format($item->price ?? 0, 0, ',', '.');
            return "- {$title} (Ukuran: {$size}) — Rp{$price}";
        })->implode("\n");

        $total = $selected->sum(fn($item) => $item->price ?? 0);
        $totalFormatted = number_format($total, 0, ',', '.');

        if ($target === 'siswa') {
            $phone = $user->no_telp_siswa;
            $message = "Halo {$user->nama_lengkap}, produk berikut sudah tersedia:\n\n{$productList}\n\nTotal harga: Rp{$totalFormatted}\n\nSilakan ambil produk di koperasi sekolah 😊";
            $notif = 'Membuka WhatsApp untuk siswa.';
        } else {
            $phone = $user->no_telp_ortu;
            $message = "Halo, orang tua/wali dari {$user->nama_lengkap}.\n\nProduk pre-order anak Anda sudah tersedia:\n\n{$productList}\n\nTotal harga: Rp{$totalFormatted}\n\nMohon informasikan kepada anak Anda untuk mengambil produk di koperasi sekolah. Terima kasih 🙏";
            $notif = 'Membuka WhatsApp untuk orang tua.';
        }

        if (!$phone) {
            Notification::make()
                ->title('Nomor telepon ' . ($target === 'siswa' ? 'siswa' : 'orang tua') . ' tidak ditemukan.')
                ->danger()
                ->send();
            return;
        }

        $num = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($num, '0')) {
            $num = '62' . substr($num, 1);
        }

        $url = 'https://wa.me/' . $num . '?text=' . urlencode($message);

        // 🔹 Gunakan JS Livewire untuk buka tab baru
        $this->js("window.open('{$url}', '_blank');");

        Notification::make()
            ->title($notif)
            ->success()
            ->send();

        $this->skipRender();
    }
}
