<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/add_cart.php';

?>



<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>



<section class="hero">

   <div class="swiper hero-slider">

      <div class="swiper-wrapper">

         <div class="swiper-slide slide">
            <div class="content">
               <h3>CAFE HAVEN</h3>
               <h3>"Where every cup tells a story"</h3>
               <a href="menu.php" class="btn">See menu</a>
              
            </div>
            <div class="image">
               <img src="img/main.png" alt="">
            </div>
         </div>

         <div class="swiper-slide slide">
            <div class="content">
               <span>order online</span>
               <h3>Cold coffee</h3>
               <a href="menu.php" class="btn">See menu</a>
            </div>
            <div class="image">
               <img src="img/menu-2.jpg" alt="">
            </div>
         </div>

         <div class="swiper-slide slide">
            <div class="content">
               <span>order online</span>
               <h3>Espresso</h3>
               <a href="menu.php" class="btn">See menu</a>
            </div>
            <div class="image">
               <img src="img/menu-3.jpg" alt="">
            </div>
         </div>

         <div class="swiper-slide slide">
            <div class="content">
               <span>Order online</span>
               <h3>Mocha</h3>
               <a href="menu.php" class="btn">See menu</a>
            </div>
            <div class="image">
               <img src="images/gallery-1.jpg" alt="">
            </div>
         </div>
      </div>

      <div class="swiper-pagination"></div>

   </div>

</section>

<section class="category">

   <h1 class="title">Cafe products</h1>

   <div class="box-container">

      <a href="category.php?category=coffee" class="box">
         <img src="images/cold-beverages.png" alt="">
         <h3>Coffee Drinks</h3>
      </a>

     <a href="category.php?category=dessert" class="box">
         <img src="images/desserts.png" alt="">
         <h3>Dessert</h3>
      </a>

      <a href="category.php?category=snacks" class="box">
         <img src="images/gallery-6.jpg" alt="">
         <h3>Snacks and pastries</h3>
      </a>

      <a href="category.php?category=drinks" class="box">
         <img src="images/hot-beverages.png" alt="">
         <h3>Speciality drinks</h3>
      </a>

   </div>

</section>




<section class="products">

   <h1 class="title">Latest Dishes</h1>

   <div class="box-container">

   <?php
   $select_products_query = "SELECT * FROM `products` LIMIT 6";
   $result = mysqli_query($conn, $select_products_query);

   if(mysqli_num_rows($result) > 0){
      while($fetch_products = mysqli_fetch_assoc($result)){
?>
      <form action="" method="post" class="box">
         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
         <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
         <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
         <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
         <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
         <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a>
         <div class="name"><?= $fetch_products['name']; ?></div>
         <div class="flex">
            <div class="price"><span>₹</span><?= $fetch_products['price']; ?></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
         </div>
      </form>
<?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
?>
 

   </div>

   <div class="more-btn">
      <a href="menu.php" class="btn">View all</a>
   </div>

</section>


















<?php include 'components/footer.php'; ?>


<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".hero-slider", {
   loop:true,
   grabCursor: true,
   effect: "flip",
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
});

</script>

</body>
</html>