<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Código de Barras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 5px;
            text-align: center;
        }

        .container {
            border: 1px dashed #000;
            padding: 5px;
            display: inline-block;
            width: 95%;
            height: 95%;
        }

        .logo {
            font-size: 8px;
            font-weight: bold;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .title {
            font-size: 10px;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        .barcode {
            margin-bottom: 2px;
        }

        .code {
            font-size: 9px;
            margin-top: 2px;
        }

        .meta {
            font-size: 7px;
            color: #333;
            margin-top: 2px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">DIMAK</div>
        <div class="title">{{ Str::limit($asset->nombre, 25) }}</div>

        <div class="barcode">
            <img src="data:image/png;base64,{{ $barcode }}" style="width: 140px; height: 40px;">
        </div>

        <div class="code">
            <strong>{{ $asset->codigo_interno }}</strong>
        </div>

        <div class="meta">
            {{ $asset->codigo_barra }}
        </div>
    </div>
</body>

</html>