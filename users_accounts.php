<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit();
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    // Delete user
    $delete_users = $conn->prepare("DELETE FROM `users` WHERE id = ?");
    $delete_users->bind_param("i", $delete_id);
    $delete_users->execute();

    // Delete user's orders
    $delete_order = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
    $delete_order->bind_param("i", $delete_id);
    $delete_order->execute();

    // Delete user's cart
    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart->bind_param("i", $delete_id);
    $delete_cart->execute();

    header('location:users_accounts.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Accounts</title>

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<!-- user accounts section starts  -->

<section class="accounts">
    <h1 class="heading">Users Account</h1>
    <div class="box-container">

        <?php
        $select_account = $conn->prepare("SELECT * FROM `users`");
        $select_account->execute();
        $result = $select_account->get_result();

        if ($result->num_rows > 0) {
            while ($fetch_accounts = $result->fetch_assoc()) {
                ?>
                <div class="box">
                    <p> User ID: <span><?= $fetch_accounts['id']; ?></span> </p>
                    <p> Username: <span><?= $fetch_accounts['name']; ?></span> </p>
                    <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn"
                       onclick="return confirm('Delete this account?');">Delete</a>
                </div>
                <?php
            }
        } else {
            echo '<p class="empty">No accounts available</p>';
        }
        ?>

    </div>
</section>

<!-- user accounts section ends -->

<!-- custom js file link -->
<script src="../js/admin_script.js"></script>

</body>
</html>
