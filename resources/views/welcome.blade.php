<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order System</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@800&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #FBFAF8;
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

<body class="min-h-screen flex items-center justify-center">

<!-- Container Mobile -->
<div class="w-full max-w-md min-h-screen px-6 py-10 text-center flex flex-col justify-center">

    <!-- Title -->
    <h1 class="font-main text-lg mb-10 smooth">
        How to Use the Order System
    </h1>

    <!-- Steps -->
    <div class="flex justify-between mb-12">

        <div class="flex flex-col items-center smooth hover:-translate-y-1">
            <div class="bg-white p-4 rounded-2xl shadow-sm smooth hover:shadow-md">
                <img src="{{ asset('images/order.png') }}" class="w-14">
            </div>
            <span class="font-secondary mt-3">Order</span>
        </div>

        <div class="flex flex-col items-center smooth hover:-translate-y-1">
            <div class="bg-white p-4 rounded-2xl shadow-sm smooth hover:shadow-md">
                <img src="{{ asset('images/payment.png') }}" class="w-14">
            </div>
            <span class="font-secondary mt-3">Payment</span>
        </div>

        <div class="flex flex-col items-center smooth hover:-translate-y-1">
            <div class="bg-white p-4 rounded-2xl shadow-sm smooth hover:shadow-md">
                <img src="{{ asset('images/serve.png') }}" class="w-14">
            </div>
            <span class="font-secondary mt-3">Serve</span>
        </div>

    </div>

    <!-- Subtitle -->
    <p class="font-secondary text-gray-600 mb-8">
        Choose how you enjoy your food
    </p>

    <!-- Buttons (WARNA TIDAK DIUBAH) -->
    <div class="space-y-4">

        <a href="{{ url('/menu/food?type=dine-in&table=15') }}"
           class="block bg-teal-600 text-white py-3 rounded-2xl font-main smooth hover:bg-teal-700 active:scale-95">
            Dine In
        </a>

        <a href="{{ url('/menu/food?type=take-away') }}"
           class="block bg-teal-600 text-white py-3 rounded-2xl font-main smooth hover:bg-teal-700 active:scale-95">
            Take Away
        </a>

    </div>

</div>

</body>
</html>
