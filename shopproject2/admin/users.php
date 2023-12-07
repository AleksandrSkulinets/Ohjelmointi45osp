<?php require 'admin-header.php'; ?>
<main>
<?php
//Fetching users from database
$statement = $pdo->query("SELECT * FROM users");
$users = $statement->fetchAll(PDO::FETCH_ASSOC);

// Remov a user
if (isset($_GET['DeleteUser'])) {
    $userId = $_GET['DeleteUser'];

    // Check if related records in other tables 
    $ordersStatement = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE UserID = ?");
    $ordersStatement->execute([$userId]);
    $ordersCount = $ordersStatement->fetchColumn();

    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['confirm_update_records'])) {
         
            deleteUser($pdo, $userId); //proceed with deletion
    
            
            header("Location: users.php"); // redirect to prevent form resubmission
            exit();
        } elseif (isset($_POST['cancel_update_records'])) {
            
            header("Location: users.php"); //same redirection if canceled
            exit();
        }
    }
    
    if ($ordersCount > 0) {
        // Confirmation message Yes/No 
        echo "<center>This user has $ordersCount orders. Do you want to delete user with all orders?</center>";

        // form for confirmation
        echo "<center><form method='post' action=''>
                  <input type='hidden' name='id' value='$userId'>
                  <button type='submit' name='confirm_update_records'>Yes</button>
                  <button type='submit' name='cancel_update_records'>No</button>
              </form></center>";
    } else {
        // No related records, proceed with deletion
        deleteUser($pdo, $userId);

        // Prevent form resubmission
        header("Location: users.php");
        exit();
    }
}

function deleteUser($pdo, $userId) {
    try {
        $pdo->beginTransaction();
  
        // delete from the orderitems
        $orderItemsStatement = $pdo->prepare("
            DELETE FROM orderitems 
            WHERE OrderID IN (SELECT OrderID FROM orders WHERE UserID = ?)
        ");
        $orderItemsStatement->execute([$userId]);

        // delete from the orders
        $ordersStatement = $pdo->prepare("DELETE FROM orders WHERE UserID = ?");
        $ordersStatement->execute([$userId]);

        // delete from the users
        $deleteUserStatement = $pdo->prepare("DELETE FROM users WHERE UserID=?");
        $deleteUserStatement->execute([$userId]);

        $pdo->commit();

        // Redirect to prevent form resubmission 
        header("Location: users.php");
        exit();
    } catch (PDOException $e) {
        // if error occurred, rollback 
        $pdo->rollBack();
        echo "Error: " . $e->getMessage(); // error message
    }
}

?>

<!-- users table -->

    <h1>User Management</h1>
    <table>
        <tr>
            <th>User ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Address</th>
            <th>User Type</th>
            <th>Edit</th>
            <th>Remove</th>
        </tr>
        <?php foreach ($users as $user) : ?>
            <tr>
                <td><?php echo $user['UserID']; ?></td>
                <td><?php echo $user['FirstName']; ?></td>
                <td><?php echo $user['LastName']; ?></td>  
                <td><?php echo $user['Email']; ?></td>
                <td><?php echo $user['Address']; ?></td>
                <td><?php echo $user['UserType']; ?></td>
                <td>
                    <form method="post" action="edit-user.php?id=<?php echo $user['UserID']; ?>">
                    <button type="submit" name="edit_user">Edit</button>
                    </form>
                <td>
                <form method="get" action="">
                    <input type="hidden" name="DeleteUser" value="<?php echo $user['UserID']; ?>">
                    <button type="submit" name="remove_user">Remove</button>
                </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h3><a href="add-user.php">Add User</a></h3>
 </main>
</body>
</html>