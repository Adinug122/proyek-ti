<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Pemesanan</title>
    <style>
        /* === RESET & GLOBAL VARIABLES === */
        :root {
            --primary: #27ae60;
            --primary-hover: #219150;
            --dark: #2c3e50;
            --bg-color: #f4f7f6;
            --text-main: #333;
            --text-muted: #7f8c8d;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            margin: 0;
            padding: 20px;
            padding-bottom: 100px; /* Ruang agar konten tidak tertutup cart */
        }

        .page-title {
            text-align: center;
            color: var(--dark);
            margin-bottom: 30px;
        }

        /* === PRODUCT GRID === */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .product-card {
            background: #fff;
            border: 1px solid #e1e8ed;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .image-wrapper {
            width: 100%;
            height: 180px;
            background-color: #eee; /* Placeholder warna jika gambar lambat */
        }

        .image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info {
            padding: 15px;
            text-align: center;
            display: flex;
            flex-direction: column;
            flex-grow: 1; /* Mendorong tombol ke bawah jika teks pendek */
        }

        .product-info h4 {
            margin: 0 0 8px;
            font-size: 1.1rem;
        }

        .price {
            font-weight: bold;
            color: #e67e22;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .btn-add {
            margin-top: auto;
            width: 100%;
            padding: 10px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.2s;
        }

        .btn-add:hover {
            background-color: var(--primary-hover);
        }

        /* === FLOATING CART BOX === */
        .cart-box {
            position: fixed;
            right: 20px;
            bottom: 20px;
            width: 300px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border: 1px solid #eee;
            overflow: hidden;
            z-index: 1000;
        }

        .cart-header {
            background: var(--dark);
            color: white;
            padding: 15px;
            margin: 0;
            text-align: center;
            font-size: 1.1rem;
        }

        .cart-items-container {
            padding: 15px;
            max-height: 200px;
            overflow-y: auto;
            font-size: 0.95rem;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #eee;
        }

        .cart-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .cart-footer {
            padding: 15px;
            background: #f8f9fa;
            border-top: 1px solid #eee;
        }

        .cart-total {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 15px;
        }

        .btn-checkout {
            width: 100%;
            padding: 12px;
            background: var(--dark);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-checkout:hover {
            background: #1a252f;
        }
        
        .empty-cart {
            text-align: center;
            color: var(--text-muted);
            font-style: italic;
        }
    </style>
</head>

<body>

    <h2 class="page-title">Pilih Menu Spesial Kami</h2>

    <div class="product-grid">
        @foreach($products as $p)
            <div class="product-card">
                <div class="image-wrapper">
                    <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name_product }}" onerror="this.src='https://via.placeholder.com/200x180?text=No+Image'">
                </div>

                <div class="product-info">
                    <h4>{{ $p->name_product }}</h4>
                    <p class="price">Rp {{ number_format($p->price, 0, ',', '.') }}</p>

                    <button class="btn-add" onclick="addToCart({{ $p->id }})">
                        + Tambah ke Keranjang
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <div id="cartBox" class="cart-box">
        <h3 class="cart-header">🛒 Keranjang (<span id="count">0</span>)</h3>
        
        <div class="cart-items-container" id="items">
            <div class="empty-cart">Belum ada pesanan</div>
        </div>

        <div class="cart-footer">
            <div class="cart-total">
                <span>Total:</span>
                <span>Rp <span id="total">0</span></span>
            </div>
            <button class="btn-checkout" onclick="checkout()">Checkout Pesanan</button>
        </div>
    </div>

    <script>
        const token = '{{ csrf_token() }}';

        // Mencegah error jika item keranjang belum di-load
        document.addEventListener('DOMContentLoaded', loadCart);

        async function addToCart(id) {
            try {
                let response = await fetch('/cart/add', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ id: id })
                });

                let data = await response.json();

                if (!response.ok) {
                    alert(data.error ?? "Terjadi kesalahan pada server.");
                    return;
                }

                updateCartUI(data);
            } catch (error) {
                console.error("Gagal menambah ke keranjang:", error);
                alert("Gagal menghubungi server. Periksa koneksi Anda.");
            }
        }

        async function loadCart() {
            try {
                let response = await fetch('/cart', {
                    credentials: 'same-origin'
                });
                let data = await response.json();
                updateCartUI(data);
            } catch (error) {
                console.error("Gagal memuat keranjang:", error);
            }
        }

        async function checkout() {
            let count = parseInt(document.getElementById('count').innerText);
            if (count === 0) {
                alert("Keranjang Anda masih kosong!");
                return;
            }

            try {
                let response = await fetch('/checkout', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                let res = await response.json();

                if (response.ok) {
                    alert("Pesanan berhasil dibuat! No Order: " + res.order);
                    location.reload();
                } else {
                    alert(res.error ?? "Gagal melakukan checkout.");
                }
            } catch (error) {
                console.error("Gagal checkout:", error);
                alert("Gagal memproses checkout.");
            }
        }

        function updateCartUI(data) {
            if (!data || data.count === undefined) return;

            // Update Angka
            document.getElementById('count').innerText = data.count;
            document.getElementById('total').innerText = data.total.toLocaleString('id-ID');

            // Update List Item
            let itemsContainer = document.getElementById('items');
            
            if (data.count === 0 || !data.items || Object.keys(data.items).length === 0) {
                itemsContainer.innerHTML = '<div class="empty-cart">Belum ada pesanan</div>';
                return;
            }

            let html = '';
            Object.values(data.items).forEach(item => {
                html += `
                    <div class="cart-item">
                        <span style="font-weight:500;">${item.name}</span>
                        <span>x${item.qty}</span>
                    </div>
                `;
            });
            
            itemsContainer.innerHTML = html;
        }
    </script>
</body>
</html>