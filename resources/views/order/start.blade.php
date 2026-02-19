<!DOCTYPE html>
<html>
<head>
    <title>Pilih Jenis Pesanan</title>
</head>
<body style="font-family: sans-serif; text-align:center; margin-top:80px">
@if ($errors->any())
    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <h2>Selamat Datang 👋</h2>

    @if($tableId)
        <p>Anda berada di Meja: <b>{{ $tableId }}</b></p>
    @else
        <p>Mode tanpa meja</p>
    @endif

    <h3>Pilih Cara Pesan</h3>

<form method="POST" action="{{ route('order.setType') }}">
    @csrf
    <input type="hidden" name="table_id" value="{{ $tableId }}">
    <button type="submit" name="order_type" value="dine_in">
        🍽️ Makan Disini
    </button>
</form>

<form method="POST" action="{{ route('order.setType') }}">
    @csrf
    <input type="hidden" name="table_id" value="{{ $tableId }}">
    <button type="submit" name="order_type" value="take_away">
        🛍️ Bawa Pulang
    </button>
</form>


</body>
</html>
