<?php  

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

include 'components/save_send.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>



<!-- recommendation section starts -->
<section class="recommendation">
   <h1 class="heading">recommendation</h1>

</section>
<!-- recommendation section ends -->

<!-- services section starts  -->

<section class="services">

   <h1 class="heading">our services</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/icon-2.png" alt="">
         <h3>sewa properti</h3>
         <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Doloremque, incidunt.</p>
      </div>

      <div class="box">
         <img src="images/icon-5.png" alt="">
         <h3>sewa barang</h3>
         <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Doloremque, incidunt.</p>
      </div>

      <div class="box">
         <img src="images/vehicle-2.png" alt="">
         <h3>sewa kendaraan</h3>
         <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Doloremque, incidunt.</p>
      </div>

   </div>

</section>

<!-- services section ends -->

<!-- listings section starts  -->

<section class="listings">

   <h1 class="heading">latest listings</h1>

   <div class="box-container">
      <?php
         $total_images = 0;
         $select_properties = $conn->prepare("SELECT * FROM `product` ORDER BY date DESC LIMIT 6");
         $select_properties->execute();
         if($select_properties->rowCount() > 0){
            while($fetch_product = $select_properties->fetch(PDO::FETCH_ASSOC)){
               
            $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_user->execute([$fetch_product['user_id']]);
            $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

            if(!empty($fetch_product['image_02'])){
               $image_coutn_02 = 1;
            }else{
               $image_coutn_02 = 0;
            }
            if(!empty($fetch_product['image_03'])){
               $image_coutn_03 = 1;
            }else{
               $image_coutn_03 = 0;
            }
            if(!empty($fetch_product['image_04'])){
               $image_coutn_04 = 1;
            }else{
               $image_coutn_04 = 0;
            }
            if(!empty($fetch_product['image_05'])){
               $image_coutn_05 = 1;
            }else{
               $image_coutn_05 = 0;
            }

            $total_images = (1 + $image_coutn_02 + $image_coutn_03 + $image_coutn_04 + $image_coutn_05);

            $select_saved = $conn->prepare("SELECT * FROM `saved` WHERE product_id = ? and user_id = ?");
            $select_saved->execute([$fetch_product['id'], $user_id]);

      ?>
      <form action="" method="POST">
         <div class="box">
            <input type="hidden" name="product_id" value="<?= $fetch_product['id']; ?>">
            <?php
               if($select_saved->rowCount() > 0){
            ?>
            <button type="submit" name="save" class="save"><i class="fas fa-heart"></i><span>saved</span></button>
            <?php
               }else{ 
            ?>
            <button type="submit" name="save" class="save"><i class="far fa-heart"></i><span>save</span></button>
            <?php
               }
            ?>
            <div class="thumb">
               <p class="total-images"><i class="far fa-image"></i><span><?= $total_images; ?></span></p> 
               <img src="uploaded_files/<?= $fetch_product['image_01']; ?>" alt="">
            </div>
            <!-- ini buat nampiliin profile -->
            <div class="admin">
               <h3><?= substr($fetch_user['name'], 0, 1); ?></h3>
               <div>
                  <p><?= $fetch_user['name']; ?></p>
                  <span><?= $fetch_product['date']; ?></span>
               </div>
            </div>
         </div>
         <div class="box">
            <div class="price"><i class="fas fa-rupiah-sign"></i><span><?= $fetch_product['price']; ?></span></div>
            <h3 class="name"><?= $fetch_product['product_name']; ?></h3>
            <p class="location"><i class="fas fa-map-marker-alt"></i><span><?= $fetch_product['address']; ?></span></p>
            <div class="flex">
               <p><i class="fas fa-layer-group"></i><span><?= $fetch_product['type']; ?></span></p>
               <!-- <p class="description"><?= $fetch_product['description']; ?></p> -->
            </div>
            <div class="flex-btn">
               <a href="view_product.php?get_id=<?= $fetch_product['id']; ?>" class="btn">lihat produk</a>
               <a href="https://wa.me/62<?= substr($fetch_user['number'], 1); ?>" class="btn">hubungi lessor</a>
               <input type="submit" value="pesan" name="send" class="btn">
            </div>
         </div>
      </form>
      <?php
         }
      }else{
         echo '<p class="empty">no properties added yet! <a href="post_product.php" style="margin-top:1.5rem;" class="btn">add new</a></p>';
      }
      ?>
      
   </div>

   <div style="margin-top: 2rem; text-align:center;">
      <a href="listings.php" class="inline-btn">view all</a>
   </div>
</section>

<!-- listings section ends -->


<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>



<!-- custom js file link  -->
<script src="js/script.js"></script>
<?php include 'components/message.php'; ?>

<script>

   let range = document.querySelector("#range");
   range.oninput = () =>{
      document.querySelector('#output').innerHTML = range.value;
   }

</script>

</body>
<?php include 'components/footer.php'; ?>

</html>
