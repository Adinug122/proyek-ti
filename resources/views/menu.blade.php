@php
$menus = [
    'food' => [
        ['name' => 'Fried Rice', 'price' => 20000, 'img' => 'food1.png'],
        ['name' => 'Beef Teriyaki', 'price' => 20000, 'img' => 'food2.png'],
        ['name' => 'Crushed Chicken Rice', 'price' => 20000, 'img' => 'food3.png'],
        ['name' => 'Grilled Chicken Rice', 'price' => 20000, 'img' => 'food4.png'],
    ],
    'drinks' => [
        ['name' => 'Moccacino', 'price' => 20000, 'img' => 'drinks1.png'],
        ['name' => 'Latte Coffee', 'price' => 20000, 'img' => 'drinks2.png'],
        ['name' => 'Americano', 'price' => 20000, 'img' => 'drinks3.png'],
        ['name' => 'Matcha', 'price' => 20000, 'img' => 'drinks4.png'],
    ],
    'snack' => [
        ['name' => 'Snack Platter', 'price' => 25000, 'img' => 'snack1.png'],
        ['name' => 'French Fries', 'price' => 16000, 'img' => 'snack2.png'],
        ['name' => 'Onion Ring', 'price' => 15000, 'img' => 'snack3.png'],
        ['name' => 'Choco Lava Cake', 'price' => 20000, 'img' => 'snack4.png'],
    ]
];
@endphp

<!DOCTYPE html>
<html>
<head>
    <title>Menu</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { background-color: #FBFAF8; }

        .font-main {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            font-size: 14px;
        }

        .font-secondary {
            font-family: 'Inter', sans-serif;
            font-size: 12px;
        }

        .smooth { transition: all 0.25s ease; }

        .fade-in {
            animation: fadeIn 0.4s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="font-secondary">

<div class="max-w-md mx-auto min-h-screen pb-32 fade-in">

    <!-- HEADER -->
    <div class="p-4">

        <div class="flex justify-between mb-4">
            <a href="/" class="text-lg smooth hover:scale-110">←</a>
            <div class="text-lg">☰</div>
        </div>

        <h1 class="font-main leading-snug text-black">
            Find the Menu <br>
            of Your <span class="text-orange-400">Choice!</span>
        </h1>

        <!-- CATEGORY -->
        <div class="mt-6 overflow-x-auto">
            <div class="flex gap-3 min-w-max pb-2">

                @foreach(['food'=>'🍽 Food','drinks'=>'🥤 Drinks','snack'=>'🍪 Snack'] as $key => $label)
                    <a href="/menu/{{ $key }}?type={{ $type }}&table={{ $table }}"
                       class="px-6 py-3 rounded-xl whitespace-nowrap font-secondary smooth
                       {{ $category == $key 
                            ? 'bg-orange-400 text-white shadow-md' 
                            : 'bg-white border text-gray-700 hover:bg-orange-400 hover:text-white hover:border-orange-400 hover:shadow-md hover:-translate-y-1' }}">
                        {{ $label }}
                    </a>
                @endforeach

            </div>
        </div>

    </div>

    <!-- PRODUCT LIST -->
    <div class="px-4 mt-4 grid grid-cols-2 gap-4">

        @foreach ($menus[$category] as $item)
        <div class="bg-white rounded-2xl shadow p-3 flex flex-col smooth hover:shadow-lg hover:-translate-y-1">

            <img src="{{ asset('images/'.$item['img']) }}"
                 class="w-full h-28 object-cover rounded-xl">

            <h3 class="font-main mt-2 text-black">
                {{ $item['name'] }}
            </h3>

            <p class="font-secondary text-teal-600 mt-1">
                Rp.{{ number_format($item['price'],0,',','.') }}
            </p>

            <a href="/menu/{{ $category }}/{{ $loop->index }}?type={{ $type }}&table={{ $table }}"
               class="mt-3 text-center rounded-xl py-2 font-secondary
               border border-gray-300 text-teal-600 smooth
               hover:bg-teal-600 hover:text-white hover:border-teal-600 hover:scale-95">
                Add
            </a>

        </div>
        @endforeach

    </div>

</div>

<!-- POPUP CART -->
<div id="cartPopup"
     class="hidden fixed bottom-4 left-0 right-0 max-w-md mx-auto px-4 transition-all duration-300">

    <div class="bg-orange-400 text-white rounded-2xl p-4 flex justify-between items-center shadow-lg animate-bounce">

        <div>
            <div class="font-secondary">Total</div>
            <div id="cartTotal" class="font-main">Rp.0</div>
        </div>

        <a href="/order?type={{ $type }}&table={{ $table }}"
           class="bg-white text-orange-500 px-5 py-2 rounded-xl font-main smooth hover:scale-95">
           Checkout
        </a>

    </div>
</div>

<script>
function loadCart() {

    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    if (cart.length === 0) {
        document.getElementById('cartPopup').classList.add('hidden');
        return;
    }

    let total = 0;

    cart.forEach(item => {
        total += parseInt(item.price) * item.qty;
    });

    document.getElementById('cartTotal').innerText =
        "Rp." + total.toLocaleString('id-ID');

    document.getElementById('cartPopup').classList.remove('hidden');
}

loadCart();
</script>

</body>
</html>
