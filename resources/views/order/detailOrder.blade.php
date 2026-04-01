<div class="p-4 bg-white dark:bg-gray-900 border-2 border-dashed border-gray-300 rounded-lg shadow-sm">
    <div class="text-center border-b border-dashed border-gray-300 pb-3 mb-4">
        <h2 class="text-xl font-bold tracking-tight">CAFE ID</h2>
        <p class="text-xs text-gray-500 uppercase">Struk Digital</p>
    </div>

    <div class="space-y-1 mb-4 text-sm">
        <div class="flex justify-between">
            <span class="text-gray-500">Nama:</span>
            <span class="font-bold">{{ $order->customer_name }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-500">Tipe:</span>
            <span class="font-bold capitalize">{{ str_replace('_', ' ', $order->order_type) }}</span>
        </div>
        @if($order->table)
            <div class="flex justify-between">
                <span class="text-gray-500">Meja:</span>
                <span class="font-bold text-primary-600">#{{ $order->table->number }}</span>
            </div>
        @endif
    </div>

    <div class="border-t border-b border-dashed border-gray-300 py-3 mb-4">
        <p class="text-[10px] text-gray-400 font-bold mb-2">DETAIL PRODUK</p>
        <div class="space-y-2">
            @foreach($order->items as $item)
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <p class="text-sm font-medium">{{ $item->product_name }}</p>
                        <p class="text-[10px] text-gray-400">Rp{{ number_format($item->price, 0, ',', '.') }} x {{ $item->qty }}</p>
                    </div>
                    <p class="text-sm font-bold text-gray-700 dark:text-gray-300">
                        Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-800 p-2 rounded">
        <span class="text-sm font-bold">TOTAL HARGA</span>
        <span class="text-lg font-black text-primary-600">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
    </div>

    <div class="text-center mt-6">
        <p class="text-[10px] text-gray-300 italic italic">Terima kasih atas pesanan Anda</p>
    </div>
</div>