<?php
// database connection
require 'admin-header.php';

// Functionorders with orderitems 
function getOrdersWithItems($pdo) {
    // PDO statement to join table orders and orderitems
    $statement = $pdo->query("
        SELECT 
            Orders.*, 
            OrderItems.OrderItemID,
            OrderItems.ProductID,
            OrderItems.Quantity,
            OrderItems.Subtotal,
            Products.ProductName
        FROM Orders
        LEFT JOIN OrderItems ON Orders.OrderID = OrderItems.OrderID
        LEFT JOIN Products ON OrderItems.ProductID = Products.ProductID
    ");

    $orders = [];//orders as an array
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $orderID = $row['OrderID'];
        if (!isset($orders[$orderID])) {
            $orders[$orderID] = [
                'OrderID' => $orderID,
                'UserID' => $row['UserID'],
                'OrderDate' => $row['OrderDate'],
                'Status' => $row['Status'],
                'TotalPrice' => $row['TotalPrice'],
                'OrderItems' => [],
            ];
        }

        if (!is_null($row['OrderItemID'])) {
            $orders[$orderID]['OrderItems'][] = [
                'OrderItemID' => $row['OrderItemID'],
                'ProductName' => $row['ProductName'],
                'Quantity' => $row['Quantity'],
                'Subtotal' => $row['Subtotal'],
            ];
        }
    }

    return $orders;
}

// delete order and orderitems
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
if (isset($_POST['delete_order'])) {
    $orderId = $_POST['order_id'];

    // Delete order and orderitems 
    deleteOrder($pdo, $orderId);

    // Prevent form resubmission
    header("Location: orders.php");
    exit();
}
}

// function delete order and order items
function deleteOrder($pdo, $orderId) {
    try {
        $pdo->beginTransaction();

        // Delete from the orderitems
        $orderItemsStatement = $pdo->prepare("DELETE FROM orderitems WHERE OrderID = ?");
        $orderItemsStatement->execute([$orderId]);

        // delete from  orders
        $ordersStatement = $pdo->prepare("DELETE FROM orders WHERE OrderID = ?");
        $ordersStatement->execute([$orderId]);

        $pdo->commit();
    } catch (PDOException $e) {
        // error occurred, rollback
        $pdo->rollBack();
        echo "Error: " . $e->getMessage(); 
    }
}
// get all orders with orderitems 
$orders = getOrdersWithItems($pdo);
?>

<!-- Display orders with orderitems -->
<main>
    <h1>Order Management</h1>

    <table>
        <tr>
            <th>OrderID</th>
            <th>UserID</th>
            <th>Order date</th>
            <th>Status</th>
            <th>Totalprice</th>
            <th>Order items</th>
            <th>Edit Order</th>
            <th>Delete Order</th>
        </tr>
        <?php foreach ($orders as $order) : ?>
            <tr>
                <td><?php echo $order['OrderID']; ?></td>
                <td><?php echo $order['UserID']; ?></td>
                <td><?php echo $order['OrderDate']; ?></td>
                <td><?php echo $order['Status']; ?></td>
                <td><?php echo $order['TotalPrice']; ?></td>
                <td>
                    <table>
                        <?php foreach ($order['OrderItems'] as $orderItem) : ?>
                            <tr>
                                <td class="orderitems">Product: <?php echo $orderItem['ProductName']; ?></td>
                                <td class="orderitems">Quantity: <?php echo $orderItem['Quantity']; ?></td>
                                <td class="orderitems">Subtotal: <?php echo $orderItem['Subtotal']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </td>
                <td>
                    <form method="post" action="edit-order.php?order_id=<?php echo $order['OrderID']; ?>">
                    <button type="submit" name="edit_order">Edit Order</button>
                    </form>
                </td>
                
                <td>
                    <form method="post" action="">
                    <input type="hidden" name="order_id" value="<?php echo $order['OrderID']; ?>">
                    <button type="submit" name="delete_order">Delete Order</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>
