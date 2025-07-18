<!DOCTYPE html>
<html>
<head>
    <title>Scan Barcode</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <style>
        #scanner {
            width: 100%;
            max-width: 500px;
            height: 300px;
            border: 2px solid #ccc;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Scan Product Barcode</h1>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color:red;">{{ session('error') }}</p>
    @endif

    <div id="scanner"></div>

    <form id="scan-form" action="{{ route('barcode.scan.process') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="barcode" id="barcode">
    </form>

    <script>
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#scanner'),
                constraints: {
                    facingMode: "environment",
                    width: { min: 640 },
                    height: { min: 480 }
                }
            },
            decoder: {
                readers: ["code_128_reader", "ean_reader", "ean_8_reader"],
                multiple: false
            },
            locate: true
        }, function(err) {
            if (err) {
                console.error("Quagga init error:", err);
                return;
            }
            console.log("Quagga initialized successfully.");
            Quagga.start();
        });

        Quagga.onDetected(function(result) {
            const code = result.codeResult.code;
            console.log("Detected Barcode:", code); // ✅ Debug line

            document.getElementById('barcode').value = code;
            document.getElementById('scan-form').submit();
            Quagga.stop(); // Stop scanning after first result
        });
    </script>
</body>
</html>
