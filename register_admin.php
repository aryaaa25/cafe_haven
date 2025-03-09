<?php

include '../components/connect.php';

session_start();

if (isset($_POST['submit'])) {

    // Sanitize and validate inputs
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    // Check if username already exists
    $select_admin_query = "SELECT * FROM `admin` WHERE name = ?";
    $stmt = $conn->prepare($select_admin_query);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $select_admin_result = $stmt->get_result();

    if ($select_admin_result->num_rows > 0) {
        $message[] = 'Username already exists!';
    } else {
        if ($pass != $cpass) {
            $message[] = 'Confirm password does not match!';
        } else {
            // Insert new admin
            $insert_admin_query = "INSERT INTO `admin` (name, password) VALUES (?, ?)";
            $stmt = $conn->prepare($insert_admin_query);
            $stmt->bind_param("ss", $name, $cpass);

            if ($stmt->execute()) {
                $message[] = 'New admin registered!';
            } else {
                $message[] = 'Failed to register admin!';
            }
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin</title>
    <!-- Add CSS links -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php
if (isset($message)) {
    foreach ($message as $msg) {
        echo '<div class="message">' . $msg . '</div>';
    }
}
?>

<section class="form-container">
    <form action="" method="post">
        <h3>Register New Admin</h3>
        <input type="text" name="name" placeholder="Enter username" class="box" required>
        <input type="password" name="pass" placeholder="Enter password" class="box" required>
        <input type="password" name="cpass" placeholder="Confirm password" class="box" required>
        <input type="submit" name="submit" value="Register" class="btn">
    </form>
</section>

</body>
</html>
