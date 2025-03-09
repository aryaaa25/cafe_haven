<?php
session_start();
include '../components/admin_header.php';
include '../components/connect.php';



// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('location:admin_login.php');
    exit;
}

$admin_id = $_SESSION['admin_id'];

// Fetch data for dashboard
$total_pendings = 0;
$result = mysqli_query($conn, "SELECT total_price FROM orders WHERE payment_status = 'pending'");
while ($row = mysqli_fetch_assoc($result)) {
    $total_pendings += $row['total_price'];
}

$total_completes = 0;
$result = mysqli_query($conn, "SELECT total_price FROM orders WHERE payment_status = 'completed'");
while ($row = mysqli_fetch_assoc($result)) {
    $total_completes += $row['total_price'];
}

$numbers_of_orders = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM orders"));
$numbers_of_products = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM products"));
$numbers_of_users = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users"));
$numbers_of_admins = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM admin"));
$numbers_of_messages = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM messages"));

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php //include '../components/admin_header.php'; ?>


<!-- Admin Dashboard Section Starts -->

<section class="dashboard">

   <h1 class="heading">Dashboard</h1>

   <div class="box-container">

      <div class="box">
         <h3>Welcome!</h3>
         <p>Admin ID: <?= $admin_id; ?></p>
         <a href="update_product.php" class="btn">Update</a>
      </div>

      <div class="box">
         <h3><span>$</span><?= $total_pendings; ?><span>/-</span></h3>
         <p>Total Pendings</p>
         <a href="placed_orders.php" class="btn">See Orders</a>
      </div>

      <div class="box">
         <h3><span>$</span><?= $total_completes; ?><span>/-</span></h3>
         <p>Total Completes</p>
         <a href="placed_orders.php" class="btn">See Orders</a>
      </div>

      <div class="box">
         <h3><?= $numbers_of_orders; ?></h3>
         <p>Total Orders</p>
         <a href="placed_orders.php" class="btn">See Orders</a>
      </div>

      <div class="box">
         <h3><?= $numbers_of_products; ?></h3>
         <p>Products Added</p>
         <a href="products.php" class="btn">See Products</a>
      </div>

      <div class="box">
         <h3><?= $numbers_of_users; ?></h3>
         <p>User Accounts</p>
         <a href="users_accounts.php" class="btn">See Users</a>
      </div>

      <div class="box">
         <h3><?= $numbers_of_admins; ?></h3>
         <p>Admins</p>
         <a href="admin_accounts.php" class="btn">See Admins</a>
      </div>

      <div class="box">
         <h3><?= $numbers_of_messages; ?></h3>
         <p>New Messages</p>
         <a href="messages.php" class="btn">See Messages</a>
      </div>

      
   </div>

</section>

<!-- Admin Dashboard Section Ends -->

<!-- Custom JS File Link -->
<script src="../js/admin_script.js"></script>

</body>
</html>
