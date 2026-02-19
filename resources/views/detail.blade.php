<!DOCTYPE html>
<html>
<head>
    <title>Detail</title>

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

        button:active {
            transform: scale(0.95);
        }
    </style>
</head>

<body class="font-secondary">

<div class="max-w-md mx-auto min-h-screen pb-32 fade-in">

    <!-- IMAGE -->
    <div class="relative">
        <img src="{{ asset('images/'.$item['img']) }}"
             class="w-full h-64 object-cover rounded-b-3xl">

        <a href="javascript:history.back()"
           class="absolute top-4 right-4 bg-white rounded-full w-9 h-9 flex items-center justify-center shadow smooth hover:scale-110">
            ✕
        </a>
    </div>

    <div class="p-4 space-y-4">

        <!-- INFO -->
        <div class="bg-white p-4 rounded-2xl shadow-sm smooth hover:shadow-md">
            <h2 class="font-main text-black">{{ $item['name'] }}</h2>
            <p class="font-secondary text-gray-600 mt-2">
                {{ $item['description'] ?? 'Delicious menu for you.' }}
            </p>
        </div>

        <!-- SPICY LEVEL -->
        <div class="bg-white p-4 rounded-2xl shadow-sm smooth hover:shadow-md">
            <h3 class="font-main mb-3">
                Spicy Level <span class="text-red-500">*</span>
            </h3>

            <div class="grid grid-cols-3 gap-3">

                @foreach(['Not Spicy','Medium','Spicy'] as $level)
                <label class="cursor-pointer">
                    <input type="radio" name="spicy" value="{{ $level }}" class="hidden peer">
                    <div class="border rounded-xl py-2 text-center font-secondary smooth
                                hover:bg-green-500 hover:text-white hover:-translate-y-1
                                peer-checked:bg-green-600
                                peer-checked:text-white
                                peer-checked:border-green-600">
                        {{ $level }}
                    </div>
                </label>
                @endforeach

            </div>
        </div>

        <!-- NOTES -->
        <div class="bg-white p-4 rounded-2xl shadow-sm smooth hover:shadow-md">
            <h3 class="font-main">Notes</h3>
            <textarea id="notes"
                      class="w-full mt-2 p-3 rounded-xl border font-secondary smooth focus:ring-2 focus:ring-green-300 focus:outline-none"
                      placeholder="Example: Make it extra spicy"></textarea>
        </div>

    </div>

    <!-- BOTTOM BAR -->
    <div class="fixed bottom-0 left-0 right-0 max-w-md mx-auto bg-white p-4 border-t shadow-lg">

        <div class="flex items-center justify-between mb-3">

            <div class="flex items-center gap-4">
                <button onclick="decrease()" 
                        class="w-9 h-9 bg-gray-200 rounded-full smooth hover:bg-gray-300 hover:scale-110">−</button>

                <span id="qty" class="font-main">1</span>

                <button onclick="increase()" 
                        class="w-9 h-9 bg-gray-200 rounded-full smooth hover:bg-gray-300 hover:scale-110">+</button>
            </div>

            <span class="font-main text-green-600">
                Rp.{{ number_format(str_replace('.', '', $item['price']),0,',','.') }}
            </span>

        </div>

        <button onclick="addToCart()"
                class="w-full bg-green-500 text-white py-3 rounded-2xl font-main
                       smooth hover:bg-green-600 hover:scale-95">
            Add to Order
        </button>
    </div>

</div>

<script>
let quantity = 1;

function increase() {
    quantity++;
    document.getElementById('qty').innerText = quantity;
}

function decrease() {
    if (quantity > 1) {
        quantity--;
        document.getElementById('qty').innerText = quantity;
    }
}

function addToCart() {

    const spicySelected = document.querySelector('input[name="spicy"]:checked');

    if (!spicySelected) {
        alert("Please select spicy level first!");
        return;
    }

    const spicy = spicySelected.value;
    const notes = document.getElementById('notes').value;

    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    cart.push({
        name: "{{ $item['name'] }}",
        price: "{{ $item['price'] }}",
        spicy: spicy,
        notes: notes,
        qty: quantity
    });

    localStorage.setItem('cart', JSON.stringify(cart));

    window.location.href = "/menu/{{ request('category') ?? 'food' }}?type={{ request('type') }}&table={{ request('table') }}";
}
</script>

</body>
</html>
