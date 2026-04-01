<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Welcome - Order System</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@600;800&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #FBFAF8;
            -webkit-tap-highlight-color: transparent;
        }

        .font-main { font-family: 'Poppins', sans-serif; }
        .font-secondary { font-family: 'Inter', sans-serif; }

        .smooth { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in { animation: fadeUp 0.6s ease-out forwards; }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md min-h-[80vh] flex flex-col justify-between py-10 animate-in">

    <div class="text-center">
        <h1 class="font-main text-xl text-gray-800 leading-tight mb-2 uppercase tracking-wide">
            How to Use <br>
            <span class="text-orange-400">The Order System</span>
        </h1>
        <div class="h-1 w-12 bg-orange-400 mx-auto rounded-full"></div>
    </div>

    <div class="relative px-4">
        <div class="absolute top-1/2 left-0 right-0 h-[2px] bg-gray-100 -translate-y-8 z-0"></div>
        
        <div class="flex justify-between relative z-10">
            <div class="flex flex-col items-center group">
                <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-gray-50 group-hover:shadow-lg smooth group-hover:-translate-y-2">
                    <img src="{{ asset('images/order.png') }}" class="w-12 h-12 object-contain" alt="Order">
                </div>
                <span class="font-main text-[10px] uppercase mt-4 text-gray-400 tracking-tighter">1. Order</span>
            </div>

            <div class="flex flex-col items-center group">
                <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-gray-50 group-hover:shadow-lg smooth group-hover:-translate-y-2">
                    <img src="{{ asset('images/payment.png') }}" class="w-12 h-12 object-contain" alt="Payment">
                </div>
                <span class="font-main text-[10px] uppercase mt-4 text-gray-400 tracking-tighter">2. Payment</span>
            </div>

            <div class="flex flex-col items-center group">
                <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-gray-50 group-hover:shadow-lg smooth group-hover:-translate-y-2">
                    <img src="{{ asset('images/serve.png') }}" class="w-12 h-12 object-contain" alt="Serve">
                </div>
                <span class="font-main text-[10px] uppercase mt-4 text-gray-400 tracking-tighter">3. Serve</span>
            </div>
        </div>
    </div>

    <div class="text-center px-4">
        <p class="font-secondary text-gray-400 text-sm mb-8 italic">
            "Choose how you enjoy your food"
        </p>
<div class="space-y-4">
    @if(request('table'))
        <form action="{{ route('menu.index') }}" method="GET">
            <input type="hidden" name="type" value="dine-in">
            <input type="hidden" name="table" value="{{ request('table') }}">
            
            <button type="submit"
                    class="w-full flex items-center justify-between bg-teal-600 text-white px-8 py-5 rounded-[1.5rem] font-main text-sm smooth shadow-lg shadow-teal-100 active:scale-95">
                <span>🍽️ DINE IN MEJA {{ request('table') }}</span>
                <span class="opacity-50">→</span>
            </button>
        </form>
    @else
        <div class="p-4 bg-orange-50 text-orange-600 rounded-xl text-xs font-secondary">
            ⚠️ Mohon scan QR Code yang ada di meja Anda.
        </div>
    @endif

    <form action="{{ route('menu.index') }}" method="GET">
        <input type="hidden" name="type" value="take-away">
        <input type="hidden" name="table" value="">
        
        <button type="submit"
                class="w-full flex items-center justify-between bg-gray-800 text-white px-8 py-5 rounded-[1.5rem] font-main text-sm smooth shadow-lg shadow-gray-200 active:scale-95">
            <span>🥡 TAKE AWAY</span>
            <span class="opacity-50">→</span>
        </button>
    </form>
</div>
        <p class="mt-8 text-[10px] font-secondary text-gray-300 uppercase tracking-widest">
            Powered by Cafe id
        </p>
    </div>

</div>

</body>
</html>