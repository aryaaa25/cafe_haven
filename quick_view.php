<?php
// Include the database connection
include 'components/connect.php';

// Start the session
session_start();

// Check if a user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

// Include the add-to-cart functionality
include 'components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick View</title>

    <!-- Font Awesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS File Link -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Include Header -->
<?php include 'components/user_header.php'; ?>

<section class="quick-view">
    <h1 class="title">Quick View</h1>

    <?php
    // Check if product ID exists in the GET request
    if (isset($_GET['pid']) && !empty($_GET['pid'])) {
        $pid = $_GET['pid'];

        // Prepare and execute the SQL query using MySQLi
        $query = "SELECT * FROM `products` WHERE id = '$pid'";
        $result = mysqli_query($conn, $query);

        // Check if any product matches the given ID
        if (mysqli_num_rows($result) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($result)) {
                ?>
                <!-- Display product details -->
                <form action="" method="post" class="box">
                    <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_products['id']); ?>">
                    <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_products['name']); ?>">
                    <input type="hidden" name="price" value="<?= htmlspecialchars($fetch_products['price']); ?>">
                    <input type="hidden" name="image" value="<?= htmlspecialchars($fetch_products['image']); ?>">
                    
                    <!-- Product Image -->
                    <img src="uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="">
                    
                    <!-- Category Link -->
                    <a href="category.php?category=<?= htmlspecialchars($fetch_products['category']); ?>" class="cat">
                        <?= htmlspecialchars($fetch_products['category']); ?>
                    </a>
                    
                    <!-- Product Name -->
                    <div class="name"><?= htmlspecialchars($fetch_products['name']); ?></div>
                    
                    <div class="flex">
                        <!-- Product Price -->
                        <div class="price"><span>₹</span><?= htmlspecialchars($fetch_products['price']); ?></div>
                        
                        <!-- Quantity Input -->
                        <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
                    </div>
                    
                    <!-- Add to Cart Button -->
                    <button type="submit" name="add_to_cart" class="cart-btn">Add to Cart</button>
                </form>
                <?php
            }
        } else {
            echo '<p class="empty">No products added yet!</p>';
        }
    } else {
        echo '<p class="empty">Invalid product ID!</p>';
    }
    ?>
</section>

<!-- Include Footer -->
<?php include 'components/footer.php'; ?>

<!-- Swiper JS -->
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- Custom JS File -->
<script src="js/script.js"></script>

</body>
</html>
