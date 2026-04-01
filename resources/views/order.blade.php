<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@600;800&display=swap" rel="stylesheet">

    <style>
        body { background-color: #ffffff; -webkit-tap-highlight-color: transparent; }
        .font-main { font-family: 'Poppins', sans-serif; }
        .font-secondary { font-family: 'Inter', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-white font-secondary">

@php
$cart = session('cart', []);
$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['qty'];
}
$biayaLayanan = 3000;
$totalBayar = $subtotal > 0 ? $subtotal + $biayaLayanan : 0;

$type = request('type');
$table = request('table');
@endphp

<div class="flex justify-center bg-gray-100 min-h-screen">
    <div class="w-full max-w-md bg-[#FBFAF8] min-h-screen relative shadow-lg flex flex-col">

        <div class="sticky top-0 z-30 bg-orange-400 text-white p-4 flex items-center shadow-md">
            <a href="/menu?type={{ $type }}&table={{ $table }}" class="mr-4 text-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="font-main text-lg uppercase tracking-wide">Detail Pesanan</h1>
        </div>

        <div class="flex-1 pb-40">
            <div class="p-4">
                <div class="bg-teal-600 text-white px-4 py-2 rounded-full inline-flex items-center text-xs shadow-sm">
                    <span class="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></span>
                    {{ $type == 'take-away' ? 'Take Away' : 'Dine In - Meja ' . ($table ?? '-') }}
                </div>
            </div>

            <div id="cartContainer" class="px-4 space-y-3">
                @forelse($cart as $id => $item)
                    <div class="bg-white p-3 rounded-2xl border border-gray-50 shadow-sm flex gap-4 items-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-xl overflow-hidden flex items-center justify-center">
                            @if(!empty($item['image']))
                                <img src="{{ asset('storage/' . $item['image']) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-[10px] text-gray-400 font-bold uppercase">{{ substr($item['name'], 0, 2) }}</span>
                            @endif
                        </div>

                        <div class="flex-1">
                            <h3 class="font-main text-gray-800 text-xs">{{ $item['name'] ?? 'Produk' }}</h3>
                            <div class="flex justify-between items-center mt-3">
                                <span id="subtotal-{{ $id }}" class="font-main text-xs text-gray-800">
                                    Rp{{ number_format(($item['price'] ?? 0) * ($item['qty'] ?? 0), 0, ',', '.') }}
                                </span>

                                <div class="flex items-center gap-2 bg-gray-100 px-2 py-1 rounded-xl">
                                    <button onclick="updateQty('{{ $id }}','minus')" class="w-5 h-5 flex items-center justify-center text-xs bg-white rounded-md shadow">-</button>
                                    <span id="qty-{{$id}}" class="text-xs font-semibold w-4 text-center">{{ $item['qty'] ?? 0 }}</span>
                                    <button onclick="updateQty('{{ $id }}','plus')" class="w-5 h-5 flex items-center justify-center text-xs bg-white rounded-md shadow">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-400">Keranjang kosong</div>
                @endforelse
            </div>

            <div class="bg-white mx-4 mt-4 p-5 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="font-main text-gray-800 mb-4 text-[10px] uppercase tracking-widest">Informasi Pelanggan</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] text-gray-400 uppercase mb-1 ml-1">Nama Lengkap</label>
                        <input type="text" id="customer_name" placeholder="Masukkan nama Anda" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-400 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-400 uppercase mb-1 ml-1">Nomor Telepon</label>
                        <input type="tel" id="customer_phone" placeholder="Contoh: 0812345678" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-400 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-400 uppercase mb-2 ml-1">Metode Pembayaran</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="payment_method" value="tunai" class="peer hidden" checked>
                                <div class="text-center py-3 border border-gray-100 rounded-xl text-xs font-secondary peer-checked:border-orange-400 peer-checked:bg-orange-50 peer-checked:text-orange-500 transition-all">💵 Tunai</div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="payment_method" value="qris" class="peer hidden">
                                <div class="text-center py-3 border border-gray-100 rounded-xl text-xs font-secondary peer-checked:border-orange-400 peer-checked:bg-orange-50 peer-checked:text-orange-500 transition-all">📱 QRIS</div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white mx-4 mt-6 p-5 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="font-main text-gray-800 mb-4 text-[10px] uppercase tracking-widest">Ringkasan Pembayaran</h2>
                <div class="space-y-3">
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Subtotal</span>
                        <span id="summary-subtotal" class="text-gray-800">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Biaya Layanan</span>
                        <span class="text-gray-800">Rp3.000</span>
                    </div>
                    <hr class="border-dashed border-gray-200 my-2">
                    <div class="flex justify-between font-main text-sm text-gray-900">
                        <span>Total Bayar</span>
                        <span id="summary-total" class="text-orange-500 font-bold">Rp{{ number_format($totalBayar, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md bg-white p-4 border-t border-gray-100 rounded-t-[2.5rem] shadow-[0_-10px_20px_rgba(0,0,0,0.05)] z-40">
            <div class="flex justify-between items-center mb-4 px-2">
                <div class="flex flex-col">
                    <span class="text-[10px] text-gray-400 uppercase tracking-widest">Total Tagihan</span>
                    <span id="bottom-total" class="font-main text-xl text-gray-800 leading-none font-bold">Rp{{ number_format($totalBayar, 0, ',', '.') }}</span>
                </div>
                <div class="text-[10px] bg-orange-50 text-orange-500 px-2 py-1 rounded-lg">Pajak Termasuk</div>
            </div>

            <button onclick="payNow()" class="w-full bg-orange-400 hover:bg-orange-500 active:scale-[0.98] text-white py-4 rounded-2xl font-main text-sm shadow-lg shadow-orange-200 transition-all duration-200 uppercase tracking-wider font-bold">
                BAYAR SEKARANG
            </button>
        </div>
    </div>
</div>

<script>
async function updateQty(id, action) {
    try {
        let response = await fetch("{{ route('cart.update') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ id: id, action: action })
        });

        let data = await response.json();

        if (data.success) {
            const formatter = new Intl.NumberFormat('id-ID');

            if (!data.items[id]) {
                const element = document.getElementById("qty-" + id).closest('.bg-white');
                element.remove();
                if (Object.keys(data.items).length === 0) {
                    document.getElementById('cartContainer').innerHTML = '<div class="text-center py-10 text-gray-400">Keranjang kosong</div>';
                }
            } else {
                const item = data.items[id];
                document.getElementById("qty-" + id).innerText = item.qty;
                document.getElementById("subtotal-" + id).innerText = "Rp" + formatter.format(item.price * item.qty);
            }

            // Update Ringkasan & Tagihan Bawah
            updateTotalDisplay(data.subtotal, data.total_bayar);
        }
    } catch (error) {
        console.error("Gagal update qty:", error);
    }
}

function updateTotalDisplay(subtotal, totalBayar) {
    const formatter = new Intl.NumberFormat('id-ID');
    
    // Update Subtotal di Ringkasan
    const subtotalEl = document.getElementById('summary-subtotal');
    if (subtotalEl) subtotalEl.innerText = "Rp" + formatter.format(subtotal);
    
    // Update Total Bayar di Ringkasan
    const summaryTotalEl = document.getElementById('summary-total');
    if (summaryTotalEl) summaryTotalEl.innerText = "Rp" + formatter.format(totalBayar);
    
    // Update Total Tagihan di Bar Bawah
    const bottomTotalEl = document.getElementById('bottom-total');
    if (bottomTotalEl) bottomTotalEl.innerText = "Rp" + formatter.format(totalBayar);
}

async function cancelOrder() {
    try {
        await fetch("{{ route('cart.cancel_order') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        });
    } catch (error) {
        console.error("Gagal membatalkan pesanan:", error);
    }
}

async function payNow() {
    @if(empty($cart)) return alert("Keranjang masih kosong!"); @endif

    const name = document.getElementById('customer_name').value;
    const phone = document.getElementById('customer_phone').value;
    const payment = document.querySelector('input[name="payment_method"]:checked').value;

    if (!name || !phone) return alert("Mohon lengkapi Nama dan Nomor Telepon Anda.");

    const urlParams = new URLSearchParams(window.location.search);
    const type = urlParams.get('type');
    const table = urlParams.get('table');

    // Ambil referensi tombol untuk loading state
    const btn = document.querySelector('button[onclick="payNow()"]');

    try {
        btn.disabled = true;
        btn.innerText = "PROSES...";

        const response = await fetch("{{ route('cart.checkout') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                type: type,
                table: table,
                customer_name: name,
                customer_phone: phone,
                payment_method: payment
            })
        });

        const result = await response.json();

        if (result.success) {
            if (result.payment_type === 'qris' && result.snap_token) {
                window.snap.pay(result.snap_token, {
                    onSuccess: function(result) {
                        alert("Pembayaran Berhasil!"); 
                        window.location.href = "{{ route('cart.clear_and_finish') }}"; 
                    },
                    onPending: function(result) { 
                        alert("Menunggu pembayaran..."); 
                        window.location.href = "{{ route('cart.clear_and_finish') }}"; 
                    },
                    // PERBAIKAN: Tambahkan async di depan function
                    onError: async function() { 
                        await cancelOrder();
                        alert("Pembayaran gagal!"); 
                        btn.disabled = false; 
                        btn.innerText = "BAYAR SEKARANG"; 
                    },
                    // PERBAIKAN: Tambahkan async di depan function
                    onClose: async function() { 
                        await cancelOrder();
                        alert('Pembayaran dibatalkan. Pesanan Anda telah dihapus.'); 
                        btn.disabled = false; 
                        btn.innerText = "BAYAR SEKARANG"; 
                    }
                });
            } else {
                alert("Pesanan Berhasil! Silakan bayar di Kasir.");
                window.location.href = "{{ route('cart.clear_and_finish') }}";
            }
        } else {
            alert("Gagal: " + (result.error || "Kesalahan."));
            btn.disabled = false;
            btn.innerText = "BAYAR SEKARANG";
        }
    } catch (error) {
        console.error(error);
        alert("Terjadi kesalahan koneksi.");
        btn.disabled = false;
        btn.innerText = "BAYAR SEKARANG";
    }
}
</script>

<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
</body>
</html>