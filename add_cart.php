<?php

// Start session to access user ID
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

if (isset($_POST['add_to_cart'])) {

   if ($user_id == '') {
      header('location:login.php'); // Redirect to login if user is not logged in
      exit; // Stop further execution
   } else {

      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      $qty = $_POST['qty'];
      $qty = filter_var($qty, FILTER_SANITIZE_STRING);

      // Check if the item already exists in the cart
      $check_cart_numbers_query = "SELECT * FROM `cart` WHERE name = ? AND user_id = ?";
      $check_cart_numbers = $conn->prepare($check_cart_numbers_query);
      $check_cart_numbers->bind_param("si", $name, $user_id); // Use bind_param for prepared statements
      $check_cart_numbers->execute();
      $result = $check_cart_numbers->get_result();

      if ($result->num_rows > 0) {
         $message[] = 'Already added to cart!';
      } else {
         // Add the item to the cart
         $insert_cart_query = "INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?, ?, ?, ?, ?, ?)";
         $insert_cart = $conn->prepare($insert_cart_query);
         $insert_cart->bind_param("iissis", $user_id, $pid, $name, $price, $qty, $image);
         $insert_cart->execute();
         $message[] = 'Added to cart!';
      }
   }
}
?>

