<!DOCTYPE html>
<html>
<head>
    <title>Local Purchase Order Dashboard</title>
</head>
<body>
    <h1>Local Purchase Order Dashboard</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Supplier</th>
            <th>Date</th>
            <th>Total</th>
        </tr>
        <?php foreach($orders as $order): ?>
        <tr>
            <td><?= $order->id ?></td>
            <td><?= $order->supplier_name ?></td>
            <td><?= $order->date ?></td>
            <td><?= $order->total_amount ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
