<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'] ?? null;

if (!$admin_id) {
    header('location:admin_login.php');
    exit();
}

if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']); // Ensure ID is an integer
    $delete_message_query = "DELETE FROM `messages` WHERE id = $delete_id";
    if (mysqli_query($conn, $delete_message_query)) {
        header('location:messages.php');
        exit();
    } else {
        echo "Error deleting message: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Messages</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php // include '../components/admin_header.php'; ?>

<!-- Messages section starts -->
<section class="messages">

   <h1 class="heading">Messages</h1>

   <div class="box-container">

   <?php
      $select_messages_query = "SELECT * FROM `messages`";
      $select_messages_result = mysqli_query($conn, $select_messages_query);

      if (mysqli_num_rows($select_messages_result) > 0) {
         while ($fetch_messages = mysqli_fetch_assoc($select_messages_result)) {
   ?>
   <div class="box">
      <p> Name: <span><?= htmlspecialchars($fetch_messages['name']); ?></span> </p>
      <p> Number: <span><?= htmlspecialchars($fetch_messages['number']); ?></span> </p>
      <p> Email: <span><?= htmlspecialchars($fetch_messages['email']); ?></span> </p>
      <p> Message: <span><?= htmlspecialchars($fetch_messages['message']); ?></span> </p>
      <a href="messages.php?delete=<?= $fetch_messages['id']; ?>" class="delete-btn" onclick="return confirm('Delete this message?');">Delete</a>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty">You have no messages</p>';
      }
   ?>

   </div>

</section>
<!-- Messages section ends -->

<!-- Custom JS file link -->
<script src="../js/admin_script.js"></script>

</body>
</html>
