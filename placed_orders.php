<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'] ?? null;

if (!$admin_id) {
    header('location:admin_login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_payment'])) {

    $order_id = intval($_POST['order_id']);
    $payment_status = mysqli_real_escape_string($conn, $_POST['payment_status']);

    $update_status_query = "UPDATE `orders` SET payment_status = '$payment_status' WHERE id = $order_id";
    if (mysqli_query($conn, $update_status_query)) {
        $message[] = 'Payment status updated!';
    } else {
        $message[] = 'Error updating payment status: ' . mysqli_error($conn);
    }
}

if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']); // Ensure ID is an integer
    $delete_order_query = "DELETE FROM `orders` WHERE id = $delete_id";
    if (mysqli_query($conn, $delete_order_query)) {
        header('location:placed_orders.php');
        exit();
    } else {
        echo "Error deleting order: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Placed Orders</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php //include '../components/admin_header.php'; ?>

<!-- Placed Orders section starts -->

<section class="placed-orders">

   <h1 class="heading">Placed Orders</h1>

   <div class="box-container">

   <?php
      $select_orders_query = "SELECT * FROM `orders`";
      $select_orders_result = mysqli_query($conn, $select_orders_query);

      if (mysqli_num_rows($select_orders_result) > 0) {
         while ($fetch_orders = mysqli_fetch_assoc($select_orders_result)) {
   ?>
   <div class="box">
      <p> User ID: <span><?= htmlspecialchars($fetch_orders['user_id']); ?></span> </p>
      <p> Placed On: <span><?= htmlspecialchars($fetch_orders['placed_on']); ?></span> </p>
      <p> Name: <span><?= htmlspecialchars($fetch_orders['name']); ?></span> </p>
      <p> Email: <span><?= htmlspecialchars($fetch_orders['email']); ?></span> </p>
      <p> Number: <span><?= htmlspecialchars($fetch_orders['number']); ?></span> </p>
      <p> Address: <span><?= htmlspecialchars($fetch_orders['address']); ?></span> </p>
      <p> Total Products: <span><?= htmlspecialchars($fetch_orders['total_products']); ?></span> </p>
      <p> Total Price: <span>$<?= htmlspecialchars($fetch_orders['total_price']); ?>/-</span> </p>
      <p> Payment Method: <span><?= htmlspecialchars($fetch_orders['method']); ?></span> </p>
      <form action="" method="POST">
         <input type="hidden" name="order_id" value="<?= htmlspecialchars($fetch_orders['id']); ?>">
         <select name="payment_status" class="drop-down">
            <option value="" selected disabled><?= htmlspecialchars($fetch_orders['payment_status']); ?></option>
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
         </select>
         <div class="flex-btn">
            <input type="submit" value="Update" class="btn" name="update_payment">
            <a href="placed_orders.php?delete=<?= htmlspecialchars($fetch_orders['id']); ?>" class="delete-btn" onclick="return confirm('Delete this order?');">Delete</a>
         </div>
      </form>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty">No orders placed yet!</p>';
      }
   ?>

   </div>

</section>

<!-- Placed Orders section ends -->

<!-- Custom JS file link -->
<script src="../js/admin_script.js"></script>

</body>
</html>
