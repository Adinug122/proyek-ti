<!DOCTYPE html>
<html>
<head>
    <title>Print QR Meja</title>
    <style>
        body {
          
            font-family: sans-serif;
            display: flex;
            margin:0;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
           }
        img {
            width: 250px;
        }
        div{
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
    </style>
</head>
<body onload="window.print()">

    <div>
        <h2>Meja {{ $table->number }}</h2>
    
        <img src="{{ asset('storage/' . $table->qr_code) }}">
    
        <p>Scan untuk order</p>
    </div>

</body>
</html>
