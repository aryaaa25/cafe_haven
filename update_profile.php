<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:index.php');
    exit();
}

if (isset($_POST['submit'])) {

    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);

    if (!empty($name)) {
        $update_name_query = "UPDATE `users` SET name = '$name' WHERE id = '$user_id'";
        mysqli_query($conn, $update_name_query);
    }

    if (!empty($email)) {
        $select_email_query = "SELECT * FROM `users` WHERE email = '$email'";
        $select_email_result = mysqli_query($conn, $select_email_query);
        if (mysqli_num_rows($select_email_result) > 0) {
            $message[] = 'Email already taken!';
        } else {
            $update_email_query = "UPDATE `users` SET email = '$email' WHERE id = '$user_id'";
            mysqli_query($conn, $update_email_query);
        }
    }

    if (!empty($number)) {
        $select_number_query = "SELECT * FROM `users` WHERE number = '$number'";
        $select_number_result = mysqli_query($conn, $select_number_query);
        if (mysqli_num_rows($select_number_result) > 0) {
            $message[] = 'Number already taken!';
        } else {
            $update_number_query = "UPDATE `users` SET number = '$number' WHERE id = '$user_id'";
            mysqli_query($conn, $update_number_query);
        }
    }

    $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
    $select_prev_pass_query = "SELECT password FROM `users` WHERE id = '$user_id'";
    $select_prev_pass_result = mysqli_query($conn, $select_prev_pass_query);
    $fetch_prev_pass = mysqli_fetch_assoc($select_prev_pass_result);
    $prev_pass = $fetch_prev_pass['password'];

    $old_pass = sha1($_POST['old_pass']);
    $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
    $new_pass = sha1($_POST['new_pass']);
    $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
    $confirm_pass = sha1($_POST['confirm_pass']);
    $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

    if ($old_pass != $empty_pass) {
        if ($old_pass != $prev_pass) {
            $message[] = 'Old password not matched!';
        } elseif ($new_pass != $confirm_pass) {
            $message[] = 'Confirm password not matched!';
        } else {
            if ($new_pass != $empty_pass) {
                $update_pass_query = "UPDATE `users` SET password = '$confirm_pass' WHERE id = '$user_id'";
                mysqli_query($conn, $update_pass_query);
                $message[] = 'Password updated successfully!';
            } else {
                $message[] = 'Please enter a new password!';
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
    <title>Update Profile</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<!-- header section starts -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<section class="form-container update-form">

    <form action="" method="post">
        <h3>Update Profile</h3>
        <input type="text" name="name" placeholder="<?= htmlspecialchars($fetch_profile['name']); ?>" class="box" maxlength="50">
        <input type="email" name="email" placeholder="<?= htmlspecialchars($fetch_profile['email']); ?>" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="number" name="number" placeholder="<?= htmlspecialchars($fetch_profile['number']); ?>" class="box" min="0" max="9999999999" maxlength="10">
        <input type="password" name="old_pass" placeholder="Enter your old password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" name="new_pass" placeholder="Enter your new password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" name="confirm_pass" placeholder="Confirm your new password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="submit" value="Update Now" name="submit" class="btn">
    </form>

</section>

<?php include 'components/footer.php'; ?>

<!-- custom js file link -->
<script src="js/script.js"></script>

</body>
</html>
