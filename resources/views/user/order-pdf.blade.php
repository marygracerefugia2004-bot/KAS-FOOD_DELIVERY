<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Receipt #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #FF6B2C;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #FF6B2C;
        }
        .order-details {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #FF6B2C;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">FoodDash</div>
        <p>Order Receipt</p>
    </div>

    <div class="order-details">
        <h3>Order #{{ $order->id }}</h3>
        <p><strong>Date:</strong> {{ $order->created_at->format('F d, Y h:i A') }}</p>
        <p><strong>Customer:</strong> {{ $order->user->name }}</p>
        <p><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $order->food->name }}</td>
                <td>{{ $order->quantity }}</td>
                <td>₱{{ number_format($order->food->price, 2) }}</td>
                <td>₱{{ number_format($order->food->price * $order->quantity, 2) }}</td>
            </tr>
            @if($order->discount > 0)
            <tr>
                <td colspan="3" style="text-align: right;">Discount ({{ $order->promo_code }}):</td>
                <td>-₱{{ number_format($order->discount, 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="total">
        Total Paid: ₱{{ number_format($order->total_price, 2) }}
    </div>

    @if($order->driver)
    <div style="margin-top: 30px;">
        <h4>Delivery Information</h4>
        <p><strong>Driver:</strong> {{ $order->driver->name }}</p>
        <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
    </div>
    @endif

    <div class="footer">
        <p>Thank you for ordering with FoodDash!</p>
        <p>For any issues, contact our support team.</p>
    </div>
</body>
</html>