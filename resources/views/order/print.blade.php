<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Print Struk #{{ $order->id }}</title>
    <style>
        /* Mengatur ukuran kertas thermal 58mm */
        @page { size: 58mm auto; margin: 0; }
        body { 
            font-family: 'Courier New', Courier, monospace; 
            width: 58mm; 
            margin: 0; 
            padding: 5px; 
            font-size: 12px; 
            color: #000;
        }
        .text-center { text-align: center; }
        .dashed-line { border-top: 1px dashed #000; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; }
        .text-right { text-align: right; }
    </style>
</head>
<body onload="window.print();"> <div class="text-center">
        <strong style="font-size: 14px;">CAFE ID</strong><br>
        Madiun, Jawa Timur
    </div>

    <div class="dashed-line"></div>
    <div>
        No : #{{ $order->id }}<br>l
        Tgl: {{ $order->created_at->format('d/m/y H:i') }}<br>
        Plg: {{ $order->customer_name }}
    </div>
    <div class="dashed-line"></div>

    <table>
        @foreach($order->items as $item)
        <tr>
            <td colspan="2">{{ $item->product_name }}</td>
        </tr>
        <tr>
            <td>{{ $item->qty }} x {{ number_format($item->price, 0, ',', '.') }}</td>
            <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="dashed-line"></div>
    <table>
        <tr>
            <td><strong>TOTAL</strong></td>
            <td class="text-right"><strong>Rp{{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <div class="dashed-line"></div>
    <div class="text-center">
        Terima Kasih Atas Kunjungannya
    </div>
</body>
</html>