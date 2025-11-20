<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Resources\Pages\Page; 
use App\Filament\Resources\UserResource;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Contracts\View\View;
use Livewire\WithPagination;

class ViewProductOrders extends Page
{
    use WithPagination;

    protected static string $resource = UserResource::class;
    protected static string $view = 'filament.resources.user-resource.pages.view-product-orders';
    protected static ?string $title = 'Produk-Pesanan';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    // === Properti Filter ===
    public ?string $filterKelas = null;
    public ?string $filterJurusan = null;
    public ?string $searchNama = '';
    public ?string $filterProduk = null;
    public ?string $filterUkuran = null;
    public ?string $filterStatus = null;
    public ?string $filterWaktu = null;

    // === Opsi Filter ===
    public array $opsiKelas = ['10' => '10', '11' => '11', '12' => '12'];
    public array $opsiJurusan = [
        'AKL 1' => 'AKL 1', 'AKL 2' => 'AKL 2', 'AKL 3' => 'AKL 3',
        'MP 1' => 'MP 1', 'Manlog' => 'Manlog', 'BR 1' => 'BR 1',
        'BR 2' => 'BR 2', 'BD' => 'BD', 'UPW' => 'UPW', 'RPL' => 'RPL',
    ];
    public array $opsiProduk = [];
    public array $opsiUkuran = [];
    public array $opsiStatus = [
        'pending' => 'Pending',
        'paid' => 'Paid (Lunas)',
        'cash' => 'Cash (Bayar di Tempat)',
    ];
    public array $opsiWaktu = [
        'hari_ini' => 'Hari Ini',
        'bulan_ini' => 'Bulan Ini',
        'tahun_ini' => 'Tahun Ini',
    ];

    public function mount(): void
    {
        $this->opsiProduk = Product::pluck('title', 'id')->all();
        $this->opsiUkuran = ProductSize::distinct()->pluck('size', 'size')->all();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, [
            'filterKelas', 'filterJurusan', 'searchNama',
            'filterProduk', 'filterUkuran', 'filterStatus', 'filterWaktu'
        ])) {
            $this->resetPage();
        }
    }

    /** 🔹 Tambahan fungsi bantu untuk status produk */
    private function getStatusProduk($product)
    {
        if ($product->is_preorder && $product->stock <= 0) {
            return 'Pre-Order';
        }
        return 'Ready Stock';
    }

    public function render(): View
    {
        $query = User::query()
            ->with(['orders.items.product', 'orders.items.size'])
            ->whereHas('orders');

        // === Filter siswa ===
        if (!blank($this->filterKelas)) {
            $query->where('kelas', $this->filterKelas);
        }

        if (!blank($this->filterJurusan)) {
            $query->where('jurusan', $this->filterJurusan);
        }

        if (!blank($this->searchNama)) {
            $query->where('nama_lengkap', 'like', '%' . $this->searchNama . '%');
        }

        // === Filter pesanan ===
        if (!blank($this->filterStatus)) {
            $query->whereHas('orders', fn($q) => 
                $q->where('payment_status', $this->filterStatus)
            );
        }

        if (!blank($this->filterProduk)) {
            $query->whereHas('orders.items', fn($q) => 
                $q->where('product_id', $this->filterProduk)
            );
        }

        if (!blank($this->filterUkuran)) {
            $query->whereHas('orders.items.size', fn($q) => 
                $q->where('size', $this->filterUkuran)
            );
        }

        // === Filter waktu ===
        if (!blank($this->filterWaktu)) {
            $query->whereHas('orders', function ($q) {
                $now = now('Asia/Jakarta');

                if ($this->filterWaktu === 'hari_ini') {
                    $q->whereDate('created_at', $now->toDateString());
                } elseif ($this->filterWaktu === 'bulan_ini') {
                    $q->whereMonth('created_at', $now->month)
                      ->whereYear('created_at', $now->year);
                } elseif ($this->filterWaktu === 'tahun_ini') {
                    $q->whereYear('created_at', $now->year);
                }
            });
        }

        $totalSiswa = $query->count();
        $users = $query->orderBy('nama_lengkap')->paginate(10);

        $filteredStatus = $this->filterStatus;
        $filteredProduk = $this->filterProduk;
        $filteredUkuran = $this->filterUkuran;

        // === Filter item di kartu + tambahkan status produk
        $users->getCollection()->transform(function ($user) use ($filteredStatus, $filteredProduk, $filteredUkuran) {
            if ($filteredStatus || $filteredProduk || $filteredUkuran) {
                $user->orders = $user->orders->filter(function ($order) use ($filteredStatus, $filteredProduk, $filteredUkuran) {
                    $statusMatch = !$filteredStatus || $order->payment_status == $filteredStatus;
                    if (!$statusMatch) return false;

                    if ($filteredProduk || $filteredUkuran) {
                        return $order->items->contains(function ($item) use ($filteredProduk, $filteredUkuran) {
                            $productMatch = !$filteredProduk || $item->product_id == $filteredProduk;
                            $sizeMatch = !$filteredUkuran || $item->size?->size == $filteredUkuran;
                            return $productMatch && $sizeMatch;
                        });
                    }

                    return true;
                });

                foreach ($user->orders as $order) {
    $order->items = $order->items->filter(function ($item) use ($filteredProduk, $filteredUkuran) {
        $productMatch = !$filteredProduk || $item->product_id == $filteredProduk;
        $sizeMatch = !$filteredUkuran || $item->size?->size == $filteredUkuran;
        return $productMatch && $sizeMatch;
    })->map(function ($item) {
        // 🔹 Tentukan status produk
        if ($item->product) {
            if ($item->product->is_preorder) {
                $item->product_status = 'Pre-Order';
            } elseif ($item->product->stock > 0) {
                $item->product_status = 'Ready Stock';
            } else {
                $item->product_status = 'Pre-Order'; // stok habis
            }
        } else {
            $item->product_status = '-';
        }
        return $item;
    });
}

            }

            // 🔹 Tambahkan status produk untuk tiap item
            foreach ($user->orders as $order) {
                foreach ($order->items as $item) {
                    $item->product_status = $item->product
                        ? $this->getStatusProduk($item->product)
                        : '-';
                }
            }

            return $user;
        });

        return view($this->getView(), [
            'users' => $users,
            'totalSiswa' => $totalSiswa,
        ])->layout(static::getLayout(), $this->getLayoutData());
    }
}
