{{-- reso --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Cotización</title>
    <style>
        body { font-family: sans-serif, font-size: 12px; margin: 20px; }
        .title { text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .section { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="title">Detalle de Cotización #{{ $model->serie }}-{{ str_pad($model->correlative, 4, '0', STR_PAD_LEFT) }}</div>
    <div>
        <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($model->date)->format('d/m/Y') }} <br>
        <strong>Cliente:</strong> {{ $model->customer->name ?? '-'}} <br>
        <strong>Observación:</strong> {{ $model->observation ?? '-' }}
    </div>

    <div class="section">
        <table>
            <thead>
                <tr>
                    <th>nro</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($model->products as $i => $product )
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->pivot->quantity }}</td>
                        <td>Bs/ {{ number_format($product->pivot->price, 2) }}</td>
                        <td>Bs/ {{ number_format($product->pivot->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section" style="text-align: right;">
        <strong>Total: Bs/ {{ number_format($model->total, 2) }}</strong>
    </div>

</body>
