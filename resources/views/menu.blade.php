@php
$menus = [
    'food' => [
        ['name' => 'Fried Rice', 'price' => '20.000', 'img' => 'food1.png'],
        ['name' => 'Beef Teriyaki', 'price' => '20.000', 'img' => 'food2.png'],
        ['name' => 'Crushed Chicken Rice', 'price' => '20.000', 'img' => 'food3.png'],
        ['name' => 'Grilled Chicken Rice', 'price' => '20.000', 'img' => 'food4.png'],
    ],
    'drinks' => [
        ['name' => 'Moccacino', 'price' => '20.000', 'img' => 'drinks1.png'],
        ['name' => 'Latte Coffe', 'price' => '20.000', 'img' => 'drinks2.png'],
        ['name' => 'Americano', 'price' => '20.000', 'img' => 'drinks3.png'],
        ['name' => 'Matcha', 'price' => '20.000', 'img' => 'drinks4.png'],
    ],
    'snack' => [
        ['name' => 'Snack Platter', 'price' => '25.000', 'img' => 'snack1.png'],
        ['name' => 'French Fries', 'price' => '16.000', 'img' => 'snack2.png'],
        ['name' => 'Onion Ring', 'price' => '15.000', 'img' => 'snack3.png'],
        ['name' => 'Choco Lava Cake', 'price' => '20.000', 'img' => 'snack4.png'],
    ]
];
@endphp


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

<div class="max-w-md mx-auto min-h-screen p-4">

    <div class="flex items-center justify-between mb-6">
        <a href="/" class="text-xl">←</a>
        <div class="text-xl">☰</div>
    </div>

    <h1 class="text-3xl font-extrabold leading-tight mb-2">
        Find the Menu<br>
        of Your <span class="text-orange-400">Choice!</span>
    </h1>

    @if($type === 'dine-in')
        <div class="bg-orange-100 text-center py-2 rounded-full mb-4">
            Table Number : {{ $table }}
        </div>
    @endif

    <div class="relative mb-5">
        <input type="text"
               placeholder="Look for the Menu"
               class="w-full pl-10 py-3 rounded-xl bg-gray-100 outline-none">
        <span class="absolute left-3 top-3">🔍</span>
    </div>

    <div class="flex gap-3 mb-6">
    <a href="/menu/food?type={{ $type }}&table={{ $table }}"
       class="px-4 py-2 rounded-full text-sm font-semibold
       {{ $type == 'food' ? 'bg-orange-400 text-white' : 'border' }}">
       🍽 Food
    </a>

    <a href="/menu/drinks?type={{ $type }}&table={{ $table }}"
       class="px-4 py-2 rounded-full text-sm font-semibold
       {{ $type == 'drinks' ? 'bg-orange-400 text-white' : 'border' }}">
       🥤 Drinks
    </a>

    <a href="/menu/snack?type={{ $type }}&table={{ $table }}"
       class="px-4 py-2 rounded-full text-sm font-semibold
       {{ $type == 'snack' ? 'bg-orange-400 text-white' : 'border' }}">
       🍪 Snack
    </a>
    </div>

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold capitalize">{{ $category }}</h2>
        <a href="#" class="text-sm text-gray-500">See All</a>
    </div>

    <div class="grid grid-cols-2 gap-4">
    @foreach ($menus[$category] as $item)
        <div class="bg-white rounded-xl shadow p-2">
            <div class="aspect-square mb-2">
                <img src="{{ asset('images/'.$item['img']) }}"
                     class="w-full h-full object-cover rounded-lg">
            </div>

            <h3 class="font-bold text-sm">{{ $item['name'] }}</h3>
            <p class="font-semibold text-sm text-[#1D828E]">Rp.{{ $item['price'] }}</p>

            <button class="w-full mt-2 bg-orange-400 text-white rounded-lg py-1 text-sm">
                Add
            </button>
        </div>
    @endforeach
    </div>

    </div>

</div>
</body>
</html>
