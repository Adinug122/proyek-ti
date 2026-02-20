<style>
    .receipt-container {
        font-family: 'Courier New', Courier, monospace;
        width: 100%;
        max-width: 300px;
        margin: auto;
        padding: 15px;
        background: #fff;
        color: #000;
    }
    .line-dashed {
        border-bottom: 1px dashed #ccc;
        margin: 10px 0;
    }
    .flex-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }
    /* Grid System Manual untuk Item-Qty-Total */
    .grid-row {
        display: flex;
        align-items: flex-start;
        margin-bottom: 8px;
    }
    .col-item { width: 60%; }
    .col-qty  { width: 15%; text-align: center; }
    .col-total { width: 25%; text-align: right; font-weight: bold; }
    
    .text-small { font-size: 10px; color: #666; }
    .text-total { font-size: 16px; font-weight: bold; color: #f97316; }
</style>

<div class="receipt-container">
    <div class="flex-row">
        <span>Pelanggan</span>
        <span style="font-weight: bold; text-transform: uppercase;">{{ $order->customer_name }}</span>
    </div>
    <div class="flex-row">
        <span>Status</span>
        <span style="font-weight: bold; color: #f97316;">[{{ $order->status }}]</span>
    </div>

    <div class="line-dashed"></div>

    <div class="grid-row text-small" style="text-transform: uppercase; font-weight: bold;">
        <div class="col-item">Item</div>
        <div class="col-qty">Qty</div>
        <div class="col-total">Total</div>
    </div>

    @foreach($order->items as $item)
    <div class="grid-row" style="font-size: 11px;">
        <div class="col-item">
            <div style="font-weight: bold;">{{ $item->product_name }}</div>
            <div class="text-small">@Rp{{ number_format($item->price, 0, ',', '.') }}</div>
        </div>
        <div class="col-qty">{{ $item->qty }}</div>
        <div class="col-total">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</div>
    </div>
    @endforeach

    <div class="line-dashed"></div>

    <div class="flex-row" style="font-size: 11px;">
        <span>Subtotal</span>
        <span>Rp{{ number_format($order->total_price - 3000, 0, ',', '.') }}</span>
    </div>
    <div class="flex-row" style="font-size: 11px;">
        <span>Biaya Layanan</span>
        <span>Rp3.000</span>
    </div>
    
    <div class="line-dashed"></div>
    
    <div class="flex-row">
        <span style="font-weight: bold; font-size: 12px;">TOTAL BAYAR</span>
        <span class="text-total">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
    </div>

    <div style="text-align: center; margin-top: 20px; font-size: 10px; color: #999;">
        --- TERIMA KASIH ---
    </div>
</div>