<!DOCTYPE html>
<html>
<head>
    <title>Order</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@800&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #ffffff; /* luar tetap putih */
        }

        .font-main {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            font-size: 14px;
        }

        .font-secondary {
            font-family: 'Inter', sans-serif;
            font-size: 12px;
        }

        .smooth {
            transition: all 0.2s ease-in-out;
        }
    </style>
</head>

<body>

<!-- Wrapper Tengah -->
<div class="min-h-screen flex justify-center">

    <!-- Area Mobile -->
    <div class="w-full max-w-md min-h-screen bg-[#FBFAF8] pb-40">

        <!-- Header -->
        <div class="bg-orange-400 text-white p-4 flex items-center font-main smooth">
            <a href="/menu/food?type={{ $type }}&table={{ $table }}" 
               class="mr-4 hover:scale-110 smooth">←</a>
            <h1>Order</h1>
        </div>

        <!-- Dine In / Take Away -->
        <div class="p-4">
            <div class="bg-teal-600 text-white px-4 py-2 rounded-xl inline-block font-secondary smooth hover:scale-105">
                {{ $type == 'dine-in' ? 'Dine In - Table '.$table : 'Take Away' }}
            </div>
        </div>

        <!-- Cart Items -->
        <div id="cartContainer" class="px-4 space-y-4"></div>

        <!-- Payment Detail -->
        <div class="bg-white m-4 p-4 rounded-2xl shadow-sm smooth hover:shadow-md">
            <h2 class="font-main mb-3">Payment Details</h2>

            <div class="flex justify-between font-secondary">
                <span>Subtotal</span>
                <span id="subtotal">Rp.0</span>
            </div>

            <div class="flex justify-between font-secondary mt-2">
                <span>Other Costs</span>
                <span>Rp.3000</span>
            </div>

            <div class="flex justify-between font-main mt-3">
                <span>Total</span>
                <span id="total">Rp.0</span>
            </div>
        </div>

    </div>

</div>

<!-- Bottom Pay (Center Mobile Only) -->
<div class="fixed bottom-0 left-0 right-0 flex justify-center">
    <div class="w-full max-w-md bg-white p-4 border-t rounded-t-2xl smooth">

        <div class="flex justify-between mb-3 font-secondary">
            <span>Total Payment</span>
            <span id="bottomTotal" class="font-main">Rp.0</span>
        </div>

        <button onclick="payNow()"
            class="w-full bg-orange-400 hover:bg-orange-500 active:scale-95 text-white py-3 rounded-2xl font-main smooth">
            Pay Now
        </button>

    </div>
</div>

<script>
let cart = JSON.parse(localStorage.getItem('cart')) || [];

function renderCart() {
    const container = document.getElementById('cartContainer');
    container.innerHTML = '';

    let subtotal = 0;

    cart.forEach((item, index) => {
        subtotal += item.price * item.qty;

        container.innerHTML += `
            <div class="bg-white p-4 rounded-2xl shadow-sm flex gap-4 smooth hover:shadow-md hover:-translate-y-1">

                <img src="/images/${item.img}" 
                     class="w-20 h-20 object-cover rounded-xl">

                <div class="flex-1">
                    <div class="flex justify-between items-start">
                        <h3 class="font-main">${item.name}</h3>
                        <button onclick="removeItem(${index})" 
                            class="font-secondary text-gray-400 hover:text-red-500 smooth">
                            Hapus
                        </button>
                    </div>

                    <p class="font-secondary text-teal-600 mt-1">
                        Rp.${item.price}
                    </p>

                    <div class="flex items-center gap-3 mt-3">
                        <button onclick="decrease(${index})"
                            class="w-7 h-7 rounded-full bg-gray-100 hover:bg-orange-400 hover:text-white smooth active:scale-90">
                            −
                        </button>

                        <span class="font-main">${item.qty}</span>

                        <button onclick="increase(${index})"
                            class="w-7 h-7 rounded-full bg-gray-100 hover:bg-green-500 hover:text-white smooth active:scale-90">
                            +
                        </button>
                    </div>
                </div>
            </div>
        `;
    });

    document.getElementById('subtotal').innerText = "Rp." + subtotal;
    document.getElementById('total').innerText = "Rp." + (subtotal + 3000);
    document.getElementById('bottomTotal').innerText = "Rp." + (subtotal + 3000);
}

function increase(index) {
    cart[index].qty++;
    localStorage.setItem('cart', JSON.stringify(cart));
    renderCart();
}

function decrease(index) {
    if (cart[index].qty > 1) {
        cart[index].qty--;
    }
    localStorage.setItem('cart', JSON.stringify(cart));
    renderCart();
}

function removeItem(index) {
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    renderCart();
}

function payNow() {
    alert("Payment Success (Demo)");
    localStorage.removeItem('cart');
    window.location.href = "/";
}

renderCart();
</script>

</body>
</html>
