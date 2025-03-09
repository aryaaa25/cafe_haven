<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);

    $errors = []; // Array to collect validation errors

    // Server-side email validation
    if (empty($email)) {
        $errors[] = 'Email is required!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format!';
    }

    // Server-side password validation
    if (empty($pass)) {
        $errors[] = 'Password is required!';
    } elseif (strlen($pass) < 6) {
        $errors[] = 'Password must be at least 6 characters long!';
    }

    // Proceed only if no validation errors
    if (empty($errors)) {
        // Hash password for security
        $hashed_pass = sha1($pass);

        // Prepare and execute MySQLi query securely
        $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
        $select_user->bind_param("ss", $email, $hashed_pass);
        $select_user->execute();
        $result = $select_user->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['user_id'] = $row['id'];

            // Check if the user is an admin
            if ($row['user_type'] == 'admin') {
                $_SESSION['admin_name'] = $row['name'];
                $_SESSION['admin_email'] = $row['email'];
                $_SESSION['admin_id'] = $row['id'];
                header('location:admin_login.php');
            } else {
                header('location:index.php');
            }
        } else {
            $errors[] = 'Incorrect email or password!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<!-- Header -->
<?php include 'components/user_header.php'; ?>

<section class="form-container">
   <form action="" method="post" id="login-form">
      <h3>Login Now</h3>
      <div class="message-container">
         <?php
         // Display validation errors or messages
         if (!empty($errors)) {
            foreach ($errors as $error) {
               echo "<div class='alert' style='color: red;'>$error</div>";
            }
         }
         ?>
      </div>
      <input type="email" name="email" required placeholder="Enter your email" class="box" maxlength="50" id="email" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Enter your password" class="box" maxlength="50" id="password" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Login Now" name="submit" class="btn">
      <p>Don't have an account? <a href="register.php">Register Now</a></p>
   </form>
</section>

<?php include 'components/footer.php'; ?>

</body>
</html>
