<?php
include '../components/connect.php';

session_start();

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $pass = $_POST['pass'];
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    // Use a prepared statement to prevent SQL injection
    $query = "SELECT * FROM admin WHERE name = ? AND password = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $name, $pass);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        die('Query failed: ' . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        $fetch_admin_id = mysqli_fetch_assoc($result);
        $_SESSION['admin_id'] = $fetch_admin_id['id'];
        $_SESSION['admin_name'] = $fetch_admin_id['name'];
        $_SESSION['admin_email'] = $fetch_admin_id['email'];
        header('location:dashboard.php');
    } else {
        $message[] = 'Incorrect username or password!';
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php
if (isset($message)) {
   foreach ($message as $msg) {
      echo '
      <div class="message">
         <span>' . $msg . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<!-- admin login form section starts -->
<section class="form-container">
   <form action="" method="POST">
      <h3>Login Now</h3>
      <p>Default name = <span>admin</span> & password = <span>admin#01</span></p>
      <input type="text" name="name" maxlength="20" required placeholder="Enter your username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" maxlength="20" required placeholder="Enter your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Login Now" name="submit" class="btn">
   </form>
</section>
<!-- admin login form section ends -->

</body>
</html>
