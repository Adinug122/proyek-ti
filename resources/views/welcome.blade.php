<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white min-h-screen flex items-center justify-center">

<div class="w-full max-w-md px-6 text-center">
    <h1 class="text-xl font-bold mb-8">
        How to Use the Order System
    </h1>

    <div class="flex justify-between mb-10">
        <div class="flex flex-col items-center">
            <img src="{{ asset('images/order.png') }}" class="w-16 mb-2">
            <span class="text-sm font-medium">Order</span>
        </div>

        <div class="flex flex-col items-center">
            <img src="{{ asset('images/payment.png') }}" class="w-16 mb-2">
            <span class="text-sm font-medium">Payment</span>
        </div>

        <div class="flex flex-col items-center">
            <img src="{{ asset('images/serve.png') }}" class="w-16 mb-2">
            <span class="text-sm font-medium">Serve</span>
        </div>
    </div>

    <p class="text-gray-700 mb-6">
        Choose how you enjoy your food
    </p>

    <div class="space-y-4">

        <a href="{{ url('/menu/food?type=dine-in&table=15') }}"
           class="block bg-teal-600 text-white py-3 rounded-xl font-semibold hover:bg-teal-700 transition">
            Dine In
        </a>

        <a href="{{ url('/menu/food?type=take-away') }}"
           class="block bg-teal-600 text-white py-3 rounded-xl font-semibold hover:bg-teal-700 transition">
            Take Away
        </a>

    </div>
</div>

</body>
</html>
