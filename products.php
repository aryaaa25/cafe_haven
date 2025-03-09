<?php
include '../components/connect.php';

session_start();
$admin_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;

if (!$admin_id) {
    header('location:admin_login.php');
    exit();
}

$message = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_img/' . $image;

    if ($_FILES['image']['size'] > 2000000) {
        $message[] = 'Image size is too large!';
    } else {
        if (move_uploaded_file($image_tmp_name, $image_folder)) {
            $query = "INSERT INTO `products` (name, category, price, image) VALUES ('$name', '$category', '$price', '$image')";
            if (mysqli_query($conn, $query)) {
                $message[] = 'Product added successfully!';
            } else {
                $message[] = 'Failed to add product!';
            }
        } else {
            $message[] = 'Failed to upload image!';
        }
    }
}

if (isset($_GET['delete'])) {
    $delete_id = filter_var($_GET['delete'], FILTER_SANITIZE_NUMBER_INT);

    $query = "DELETE FROM `products` WHERE id = $delete_id";
    if (mysqli_query($conn, $query)) {
        $message[] = 'Product deleted successfully!';
    } else {
        $message[] = 'Failed to delete product!';
    }

    header('location:products.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Product</title>
    <style>
        form {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        input, select {
            width: 100%;
            margin: 10px 0;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color:rgb(44, 17, 181);
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Add Product</h1>

    <?php if (!empty($message)) { ?>
        <div style="text-align: center; color: red;">
            <?php foreach ($message as $msg) {
                echo "<p>$msg</p>";
            } ?>
        </div>
    <?php } ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <label for="name">Product Name</label>
        <input type="text" name="name" id="name" required>

        <label for="price">Product Price</label>
        <input type="text" name="price" id="price" required>

        <label for="category">Product Category</label>
        <select name="category" id="category" required>
            <option value="category1">Coffee drinks</option>
            <option value="category2">Desserts</option>
            <option value="category3">Snacks & Pastries</option>
            <option value="category4">Speciality Drinks</option>
        </select>

        <label for="image">Product Image</label>
        <input type="file" name="image" id="image" accept="image/*" required>

        <button type="submit" name="add_product">Add Product</button>
    </form>
</body>
</html>
