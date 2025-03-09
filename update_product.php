<?php
include '../components/connect.php';

session_start();

// Verify admin session
$admin_id = $_SESSION['admin_id'] ?? null;

if (!$admin_id) {
    header('location:admin_login.php');
    exit();
}

// Check if the product ID is provided
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $product_query = "SELECT * FROM `products` WHERE id = $product_id";
    $product_result = mysqli_query($conn, $product_query);

    // If the product exists, fetch the details
    if (mysqli_num_rows($product_result) > 0) {
        $product = mysqli_fetch_assoc($product_result);
    } else {
        echo "Product not found.";
        exit();
    }
} else {
    echo "Invalid product ID.";
    exit();
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $name = mysqli_real_escape_string($conn, filter_var($_POST['name'], FILTER_SANITIZE_STRING));
    $price = mysqli_real_escape_string($conn, filter_var($_POST['price'], FILTER_SANITIZE_STRING));
    $category = mysqli_real_escape_string($conn, filter_var($_POST['category'], FILTER_SANITIZE_STRING));

    // File upload handling
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_img/' . $image;

    if (!empty($image)) {
        if ($_FILES['image']['size'] > 2000000) {
            $message[] = 'Image size is too large!';
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);

            // Update with a new image
            $update_query = "UPDATE `products` SET name = '$name', category = '$category', price = '$price', image = '$image' WHERE id = $product_id";
        }
    } else {
        // Update without changing the image
        $update_query = "UPDATE `products` SET name = '$name', category = '$category', price = '$price' WHERE id = $product_id";
    }

    if (mysqli_query($conn, $update_query)) {
        $message[] = 'Product updated successfully!';
        header('location:products.php');
        exit();
    } else {
        $message[] = 'Failed to update product: ' . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Update Product</h1>

        <!-- Display feedback messages -->
        <?php if (isset($message)) : ?>
            <?php foreach ($message as $msg) : ?>
                <div class="alert alert-info"><?= $msg; ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Update Form -->
        <form action="" method="POST" enctype="multipart/form-data" class="mt-4">
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($product['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="text" id="price" name="price" class="form-control" value="<?= htmlspecialchars($product['price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <input type="text" id="category" name="category" class="form-control" value="<?= htmlspecialchars($product['category']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" id="image" name="image" class="form-control">
                <small class="text-muted">Current image: <?= htmlspecialchars($product['image']); ?></small>
            </div>
            <button type="submit" name="update_product" class="btn btn-primary">Update Product</button>
        </form>

        <div class="mt-4">
            <a href="products.php" class="btn btn-secondary">Back to Product List</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
