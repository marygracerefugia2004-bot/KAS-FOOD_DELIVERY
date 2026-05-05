<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Receipt #<?php echo e($order->id); ?></title>
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
        <h3>Order #<?php echo e($order->id); ?></h3>
        <p><strong>Date:</strong> <?php echo e($order->created_at->format('F d, Y h:i A')); ?></p>
        <p><strong>Customer:</strong> <?php echo e($order->user->name); ?></p>
        <p><strong>Delivery Address:</strong> <?php echo e($order->delivery_address); ?></p>
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
                <td><?php echo e($order->food->name); ?></td>
                <td><?php echo e($order->quantity); ?></td>
                <td>₱<?php echo e(number_format($order->food->price, 2)); ?></td>
                <td>₱<?php echo e(number_format($order->food->price * $order->quantity, 2)); ?></td>
            </tr>
            <?php if($order->discount > 0): ?>
            <tr>
                <td colspan="3" style="text-align: right;">Discount (<?php echo e($order->promo_code); ?>):</td>
                <td>-₱<?php echo e(number_format($order->discount, 2)); ?></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="total">
        Total Paid: ₱<?php echo e(number_format($order->total_price, 2)); ?>

    </div>

    <?php if($order->driver): ?>
    <div style="margin-top: 30px;">
        <h4>Delivery Information</h4>
        <p><strong>Driver:</strong> <?php echo e($order->driver->name); ?></p>
        <p><strong>Status:</strong> <?php echo e(ucfirst(str_replace('_', ' ', $order->status))); ?></p>
    </div>
    <?php endif; ?>

    <div class="footer">
        <p>Thank you for ordering with FoodDash!</p>
        <p>For any issues, contact our support team.</p>
    </div>
</body>
</html><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\user\order-pdf.blade.php ENDPATH**/ ?>