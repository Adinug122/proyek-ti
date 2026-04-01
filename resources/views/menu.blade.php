    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title>Menu - Order App</title>

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@600;800&display=swap" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>

        <style>
            body { background-color: #FBFAF8; -webkit-tap-highlight-color: transparent; }
            .font-main { font-family: 'Poppins', sans-serif; }
            .font-secondary { font-family: 'Inter', sans-serif; }
            
            /* Hilangkan scrollbar kategori tapi tetap bisa di-scroll */
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

            .smooth { transition: all 0.2s ease-in-out; }
            
            .active-scale:active { transform: scale(0.95); }

            @keyframes slideUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-menu { animation: slideUp 0.4s ease-out forwards; }
        </style>
    </head>

    <body class="font-secondary antialiased text-gray-900">

    <div class="max-w-md mx-auto min-h-screen pb-32 relative shadow-2xl bg-[#FBFAF8]">

        <div class="p-5"> 
    <div class="flex justify-between items-center mb-10">
        <a href="{{ route('order.start') }}" class="w-10 h-10 flex items-center justify-center bg-white rounded-full active-scale shadow-sm">
            <span class="text-xl text-gray-700">←</span>
        </a>
        <div class="w-10 h-10 flex items-center justify-center bg-white rounded-full active-scale shadow-sm">
            <span class="text-lg text-gray-700">☰</span>
        </div>
    </div>

    <div class="relative inline-block mb-6">
        <h1 class="font-main text-4xl leading-tight text-gray-900 tracking-tight">
            Find the Menu <br>
            of Your <span class="text-orange-400">Choice!</span>
        </h1>
        <div class="absolute -bottom-1 left-0 w-24 h-1.5 bg-red-400 rounded-full opacity-80"></div>
    </div>

    <div class="mt-8 flex justify-center">
        <div class="bg-orange-50 px-10 py-3 rounded-full border border-orange-100 shadow-sm">
            <p class="font-main text-sm text-gray-700">
                Table Number : <span class="text-orange-500 font-extrabold">{{ request('table') ?? '-' }}</span>
            </p>
        </div>
    </div>
</div>


        <div class="sticky top-0 z-20 bg-[#FBFAF8]/80 backdrop-blur-md pt-4 pb-2">
            <div class="overflow-x-auto no-scrollbar px-5">
            <div class="flex gap-3 min-w-max">
        @foreach(['food' => '🍽 Food', 'drink' => '🥤 Drinks', 'snack' => '🍪 Snack'] as $key => $label)
            <a href="/menu?category={{ $key }}&type={{ request('type') }}&table={{ request('table') }}"
            class="px-6 py-3 rounded-2xl whitespace-nowrap font-secondary text-sm smooth active-scale
            {{ (request('category', 'food') == $key)
                    ? 'bg-orange-400 text-white shadow-lg shadow-orange-200'
                    : 'bg-white border-none text-gray-500 shadow-sm' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
            </div>
        </div>

        <div class="px-5 mt-4 grid grid-cols-2 gap-4">
            @foreach ($products as $item)
                <div class="bg-white rounded-3xl p-3 flex flex-col border border-gray-50 shadow-sm animate-menu">

                    <div class="relative group">
                        <img src="{{ asset('storage/' . $item->image) }}"
                            class="w-full h-32 object-cover rounded-2xl shadow-inner group-hover:scale-105 transition-transform duration-300">
                    </div>

                    <div class="mt-3 px-1">
                        <h3 class="font-main text-[13px] text-gray-800 line-clamp-1">
                            {{ $item->name_product }}
                        </h3>

                        <p class="font-secondary text-orange-500 font-bold text-sm mt-0.5">
                            Rp{{ number_format($item->price, 0, ',', '.') }}
                        </p>

                        <button onclick="addToCart({{ $item->id }}, '{{ $item->name_product }}', {{ $item->price }})" class="mt-3 w-full inline-flex items-center justify-center rounded-xl py-2.5 font-main text-[11px]
                        bg-teal-600 text-white active-scale shadow-md shadow-teal-100 transition-all">
                            ADD TO CART
                        </button>

                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <div id="cartPopup"
        class="hidden fixed bottom-6 left-0 right-0 max-w-md mx-auto px-6 z-50">

        <div class="bg-gray-900 text-white rounded-[2rem] p-4 flex justify-between items-center shadow-2xl ring-4 ring-white/10 overflow-hidden relative">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-orange-400 rounded-full blur-2xl opacity-40"></div>

            <div class="flex items-center gap-4 relative z-10">
                <div class="bg-orange-400 p-2.5 rounded-xl shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <div class="text-[10px] text-gray-400 uppercase tracking-widest font-secondary">Subtotal</div>
                    <div id="cartTotal" class="font-main text-lg leading-none">Rp0</div>
                </div>
            </div>
<a id="checkoutLink" href="#"
   class="bg-orange-400 text-white px-6 py-3 rounded-2xl font-main text-xs active-scale shadow-lg shadow-orange-900/20 relative z-10">
    CHECKOUT
</a>
        </div>
    </div>



 <script>
// 1. Fungsi untuk mengambil status keranjang saat halaman pertama kali dibuka
async function loadCartStatus() {
    try {
        const response = await fetch("{{ route('cart.index') }}");
        const data = await response.json();
        
        // Cek data.total sesuai return dari controller $this->total()
        if (data.total > 0) {
            updatePopupUI(data.total);
        }
    } catch (error) {
        console.error("Gagal memuat status keranjang");
    }
}

// 2. Fungsi untuk menambah item ke database via AJAX
async function addToCart(id, name, price) {
    try {
        const response = await fetch("{{ route('cart.add') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ id: id })
        });

        if (!response.ok) throw new Error('Gagal menambah item');

        const data = await response.json();
        updatePopupUI(data.total);
        
    } catch (error) {
        alert("Terjadi kesalahan saat menambah ke keranjang");
    }
}


function updatePopupUI(total) {
    const popup = document.getElementById('cartPopup');
    const totalElement = document.getElementById('cartTotal');
    const checkoutLink = document.getElementById('checkoutLink');
    
    totalElement.innerText = "Rp" + total.toLocaleString('id-ID');
    
    // Ambil parameter dari URL (misal: ?type=dine%20in&table=5)
    const urlParams = new URLSearchParams(window.location.search);
    const type = urlParams.get('type') || 'dine in';
    const table = urlParams.get('table') || '';
    
    // Set link ke halaman checkout dengan parameter tersebut
    checkoutLink.href = `/order?type=${type}&table=${table}`;

    popup.classList.remove('hidden');
    popup.classList.add('animate-menu');
}
document.addEventListener('DOMContentLoaded', loadCartStatus);
</script>
    </body>
    </html>