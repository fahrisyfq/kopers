<!-- <div>
    <table class="min-w-full divide-y divide-gray-700">
        <thead>
            <tr>
                <th class="px-4 py-2 text-left text-sm font-medium">Produk</th>
                <th class="px-4 py-2 text-left text-sm font-medium">Ukuran</th>
                <th class="px-4 py-2 text-left text-sm font-medium">Status Pembayaran</th>
                <th class="px-4 py-2 text-left text-sm font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orderItems as $item)
                <tr>
                    <td class="px-4 py-2">{{ $item->product->title ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $item->productSize->size ?? '-' }}</td>
                    <td class="px-4 py-2">
                        <form method="POST" action="{{ route('filament.update-payment-status', $item->id) }}">
                            @csrf
                            @method('PATCH')
                            <select name="payment_status" onchange="this.form.submit()" class="bg-gray-800 text-white rounded px-2 py-1">
                                <option value="pending" @selected($item->payment_status == 'pending')>Menunggu Pembayaran</option>
                                <option value="cash" @selected($item->payment_status == 'cash')>Tunai</option>
                                <option value="paid" @selected($item->payment_status == 'paid')>Sudah Dibayar</option>
                            </select>
                        </form>
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-400">#{{ $item->id }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div> -->
