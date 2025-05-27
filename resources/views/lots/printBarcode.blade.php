<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcode</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 20px;
        }
        .barcode {
            margin-top: 50px;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="barcode">
        <img src="{{ asset('storage/' . $lot->barcode) }}" alt="Barcode for {{ $lot->batch_number }}" class="h-32 w-auto">
    </div>
    <script>
        window.print();
    </script>
</body>
</html>