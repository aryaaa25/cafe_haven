<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:index.php');
    exit;
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $method = $_POST['method'];
    $method = filter_var($method, FILTER_SANITIZE_STRING);
    $address = $_POST['address'];
    $address = filter_var($address, FILTER_SANITIZE_STRING);
    $total_products = $_POST['total_products'];
    $total_price = $_POST['total_price'];

    // Check if the cart has items
    $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $check_cart->bind_param("i", $user_id);
    $check_cart->execute();
    $result = $check_cart->get_result();

    if ($result->num_rows > 0) {
        if ($address == '') {
            $message[] = 'please add your address!';
        } else {
            // Insert order
            $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
            $insert_order->bind_param("isssssii", $user_id, $name, $number, $email, $method, $address, $total_products, $total_price);
            $insert_order->execute();

            // Delete cart items after placing the order
            $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
            $delete_cart->bind_param("i", $user_id);
            $delete_cart->execute();

            $message[] = 'order placed successfully!';
        }
    } else {
        $message[] = 'your cart is empty';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<!-- Header Section Starts -->
<?php include 'components/user_header.php'; ?>
<!-- Header Section Ends -->

<div class="heading">
    <h3>Checkout</h3>
    <p><a href="index.php">home</a> <span> / checkout</span></p>
</div>

<section class="checkout">

    <h1 class="title">Order Summary</h1>

    <form action="" method="post">

        <div class="cart-items">
            <h3>Cart Items</h3>
            <?php
            $grand_total = 0;
            $cart_items = [];
            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->bind_param("i", $user_id);
            $select_cart->execute();
            $result_cart = $select_cart->get_result();
            if ($result_cart->num_rows > 0) {
                while ($fetch_cart = $result_cart->fetch_assoc()) {
                    $cart_items[] = $fetch_cart['name'] . ' (' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ') - ';
                    $total_products = implode($cart_items);
                    $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
            ?>
            <p><span class="name"><?= $fetch_cart['name']; ?></span><span class="price">₹<?= $fetch_cart['price']; ?> x <?= $fetch_cart['quantity']; ?></span></p>
            <?php
                }
            } else {
                echo '<p class="empty">Your cart is empty!</p>';
            }
            ?>
            <p class="grand-total"><span class="name">Grand Total:</span><span class="price">₹<?= $grand_total; ?></span></p>
            <a href="cart.php" class="btn">View Cart</a>
        </div>

        <input type="hidden" name="total_products" value="<?= isset($total_products) ? $total_products : ''; ?>">
        <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
        <input type="hidden" name="name" value="<?= $fetch_profile['name'] ?? ''; ?>">
        <input type="hidden" name="number" value="<?= $fetch_profile['number'] ?? ''; ?>">
        <input type="hidden" name="email" value="<?= $fetch_profile['email'] ?? ''; ?>">
        <input type="hidden" name="address" value="<?= $fetch_profile['address'] ?? ''; ?>">

        <div class="user-info">
            <h3>Your Info</h3>
            <p><i class="fas fa-user"></i><span><?= $fetch_profile['name'] ?? ''; ?></span></p>
            <p><i class="fas fa-phone"></i><span><?= $fetch_profile['number'] ?? ''; ?></span></p>
            <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email'] ?? ''; ?></span></p>
            <a href="update_profile.php" class="btn">Update Info</a>
            <h3>Delivery Address</h3>
            <p><i class="fas fa-map-marker-alt"></i><span><?= isset($fetch_profile['address']) && $fetch_profile['address'] != '' ? $fetch_profile['address'] : 'Please enter your address'; ?></span></p>
            <a href="update_address.php" class="btn">Update Address</a>
            <select name="method" class="box" required>
                <option value="" disabled selected>Select payment method --</option>
                <option value="cash on delivery">Cash on Delivery</option>
                <option value="credit card">Credit Card</option>
                <option value="paytm">Paytm</option>
                <option value="paypal">PayPal</option>
            </select>
            <input type="submit" value="Place Order" class="btn <?php if (empty($fetch_profile['address'])) { echo 'disabled'; } ?>" style="width:100%; background:var(--red); color:var(--white);" name="submit">
        </div>

    </form>
   
</section>

<!-- Footer Section Starts -->
<?php include 'components/footer.php'; ?>
<!-- Footer Section Ends -->

<!-- Custom JS file link -->
<script src="js/script.js"></script>

</body>
</html>
