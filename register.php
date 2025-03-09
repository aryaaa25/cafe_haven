<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
   $email = $_POST['email'];
   $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
   $number = $_POST['number'];
   $number = htmlspecialchars($number, ENT_QUOTES, 'UTF-8');
   $pass = sha1($_POST['pass']);
   $pass = htmlspecialchars($pass, ENT_QUOTES, 'UTF-8');
   $cpass = sha1($_POST['cpass']);
   $cpass = htmlspecialchars($cpass, ENT_QUOTES, 'UTF-8');

   // Check if email or number already exists
   $query = "SELECT * FROM users WHERE email = ? OR number = ?";
   $stmt = $conn->prepare($query);
   $stmt->bind_param('ss', $email, $number);
   $stmt->execute();
   $result = $stmt->get_result();

   if($result->num_rows > 0){
      $message[] = 'Email or number already exists!';
   }else{
      if($pass != $cpass){
         $message[] = 'Confirm password does not match!';
      }else{
         $query = "INSERT INTO users (name, email, number, password) VALUES (?, ?, ?, ?)";
         $stmt = $conn->prepare($query);
         $stmt->bind_param('ssss', $name, $email, $number, $cpass);
         $stmt->execute();

         $query = "SELECT * FROM users WHERE email = ? AND password = ?";
         $stmt = $conn->prepare($query);
         $stmt->bind_param('ss', $email, $pass);
         $stmt->execute();
         $result = $stmt->get_result();

         if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $_SESSION['user_id'] = $row['id'];
            header('location:index.php');
         }
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
   <title>Register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<section class="form-container">

   <form action="" method="post">
      <h3>Register Now</h3>
      <input type="text" name="name" required placeholder="Enter your name" class="box" maxlength="50">
      <input type="email" name="email" required placeholder="Enter your email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="number" name="number" required placeholder="Enter your number" class="box" min="0" max="9999999999" maxlength="10">
      <input type="password" name="pass" required placeholder="Enter your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Confirm your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Register Now" name="submit" class="btn">
      <p>Already have an account? <a href="login.php">Login now</a></p>
   </form>

</section>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
