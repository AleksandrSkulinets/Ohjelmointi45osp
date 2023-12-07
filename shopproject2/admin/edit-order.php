<?php
require 'admin-header.php';

// Check OrderID is provided in URL
if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    
    // Redirect to orders.php if OrderID not provided
    header("Location: orders.php");
    exit();
}

// Get order ID from the URL
$orderID = $_GET['order_id'];

// Retrieve order details
$orderDetails = getOrderDetails($pdo, $orderID);

// Update order status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $newStatus = $_POST['order_status'];
    updateOrderStatus($pdo, $orderID, $newStatus);

    // Redirect to orders.php after updating 
    header("Location: orders.php");
    exit();
}
// function to update order status
function updateOrderStatus($pdo, $orderID, $newStatus) {
    $statement = $pdo->prepare("UPDATE Orders SET Status = ? WHERE OrderID = ?");
    $statement->execute([$newStatus, $orderID]);
}
// function retrieve order details
function getOrderDetails($pdo, $orderID) {
    // geting order details
    $statement = $pdo->prepare("SELECT * FROM Orders WHERE OrderID = ?");
    $statement->execute([$orderID]);
    $orderDetails = $statement->fetch(PDO::FETCH_ASSOC);

    return $orderDetails;
}

?>

<!--  displaying order details/ form to edit status -->
<main>
    <h1>Edit Order</h1>
    
    <table>
    <tr>
        <th>OrderID</th>
        <th>UserID</th>
        <th>Order date</th>
        <th>Status</th>
        <th>Totalprice</th>
    </tr>
    <tr>
        <td><?php echo $orderDetails['OrderID']; ?></td>
        <td><?php echo $orderDetails['UserID']; ?></td>
        <td><?php echo $orderDetails['OrderDate']; ?></td>
        <td><?php echo $orderDetails['Status']; ?></td>
        <td><?php echo $orderDetails['TotalPrice']; ?></td>
    </tr>
</table>

    <h2>Edit Order Status</h2>
    <div class="custom-form">
    <form method="post" action="" class="my-form">
        <label for="order_status">Select Order Status:</label>
        <select name="order_status">
            <option value="Pending" <?php echo ($orderDetails['Status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
            <option value="Shipped" <?php echo ($orderDetails['Status'] == 'Shipped') ? 'selected' : ''; ?>>Shipped</option>
            <option value="Delivered" <?php echo ($orderDetails['Status'] == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
        </select>
        <button type="submit" name="update_status">Update Order Status</button>
    </form>
    </div>
    

</main>
</body>
</html>