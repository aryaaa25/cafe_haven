<?php
// Start session only if it has not already been started
if (session_status() === PHP_SESSION_NONE) {
   session_start();
}



include '../components/connect.php';
?>

<header class="header">

   <section class="flex">

      <a href="dashboard.php" class="logo">Admin<span>Panel</span></a>

      <nav class="navbar">
         <a href="dashboard.php">home</a>
         <a href="products.php">products</a>
         <a href="placed_orders.php">orders</a>
         <a href="admin_accounts.php">admins</a>
         <a href="users_accounts.php">users</a>
         <a href="messages.php">messages</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
            $admin_id = $_SESSION['admin_id'] ?? null;

            if ($admin_id) {
                $select_profile_query = "SELECT * FROM `admin` WHERE id = '$admin_id'";
                $select_profile_result = mysqli_query($conn, $select_profile_query);

                if ($select_profile_result && mysqli_num_rows($select_profile_result) > 0) {
                    $fetch_profile = mysqli_fetch_assoc($select_profile_result);
                } else {
                    $fetch_profile = ['name' => 'Unknown'];
                }
            } else {
                $fetch_profile = ['name' => 'Guest'];
            }
         ?>
          <?php
         
         
         if (isset($_SESSION['admin_id'])) {
            echo '<a href="/cafe website/index.php" class="btn">Switch to User Panel</a>';
        
        }
       ?>
         
          <p><?= htmlspecialchars($fetch_profile['name']); ?></p>
         <a href="update_profile.php" class="btn">update profile</a>
         <div class="flex-btn">
            <a href="admin_login.php" class="option-btn">login</a>
            <a href="register_admin.php" class="option-btn">register</a>
            
         </div>
         <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
      </div>
 
     

   </section>

</header>
