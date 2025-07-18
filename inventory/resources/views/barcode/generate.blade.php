<!DOCTYPE html>
<html>
<head>
    <title>Barcode for {{ $product['name'] }}</title>
</head>
<body>
    <h2>{{ $product['name'] }}</h2>
    <img src="{{ $barcodePath }}" alt="Barcode for {{ $product['name'] }}">
</body>
</html>
