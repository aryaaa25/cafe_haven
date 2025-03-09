<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <section class="flex">

      <a href="index.php" class="logo">CAFE HAVEN☕</a>

      <nav class="navbar">
         <a href="index.php">home</a>
         <a href="about.php">about</a>
         <a href="menu.php">menu</a>
         <a href="orders.php">orders</a>
         <a href="contact.php">contact</a>
      </nav>

      <div class="icons">
         <?php
         // Fetch total cart items
         $result = mysqli_query($conn, "SELECT COUNT(*) AS total_items FROM `cart` WHERE user_id = '$user_id'");
         $row = mysqli_fetch_assoc($result);
         $total_cart_items = $row['total_items'];
         ?>
         <a href="search.php"><i class="fas fa-search"></i></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_items; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="menu-btn" class="fas fa-bars"></div>
      </div>

      <div class="profile">
         <?php
         // Fetch user profile
         $result = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'");
         if (mysqli_num_rows($result) > 0) {
            $fetch_profile = mysqli_fetch_assoc($result);
         ?>
         <p class="name"><?= $fetch_profile['name']; ?></p>
         <div class="flex">
            <a href="profile.php" class="btn">profile</a>
            <a href="components/user_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
         </div>
         <p class="account">
            <a href="login.php">login</a> or
            <a href="register.php">register</a>
         </p>

         <?php
         } else {
         ?>
            <p class="name">please login first!</p>
            <a href="login.php" class="btn">login</a>
         <?php
         }
         ?>

   <?php
// Start the session only if it is not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>      

<header>
    <div class="container">
        <nav>
            <a href="index.php" class="logo">CAFE HAVEN☕</a>
            <div class="links">
                <?php 
                if (isset($_SESSION['user_id'])) {
                    $user_id = $_SESSION['user_id'];

                    // Check if the user is admin
                    if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] == $user_id) {
                        // Add a switch button for admins
                        echo '<a href="/cafe website/admin/admin_login.php" class="btn">Switch to Admin Panel</a>';

                    } else {
                        echo '<a href="index.php" class="btn">Switch to Admin Panel</a>';
                    }
                } else {
                  echo '<a href="/cafe website/admin/admin_login.php" class="btn">Switch to Admin Panel</a>';

                }
                ?>
            </div>
        </nav>
    </div>
</header>

      </div>

   </section>

</header>
