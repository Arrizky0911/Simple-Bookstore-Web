<?php
session_start();
include_once 'config.php'; 

try {
    $db = new DBConnection();
} catch (\Throwable $th) {
    header("Location: error.php");
    exit();
}

$total = 0;
if (isset($_SESSION["userID"])) {

  if (isset($_POST['addCart'])) {
    if ($_POST['items'] > 0){
      $n = $_POST['items'];
    } else {
      echo "<script>alert('Woops! The numbers cannot be 0.')</script>";
      header("Location: product.php?hon=".$hon);
      exit();
    }
    $hon = $_POST['bookID'];
    $val_stock = $db->validateStock($hon, $n);
    if ($val_stock) {
      $result = $db->addCart($hon, $n, $_SESSION['userID']);
      $_POST['items'] = "";
      $_POST['bookID'] = "";
      $_POST['addCart'] = "";
    } else {
        echo "<script>alert('Woops! The numbers is out of stock.')</script>";
        header("Location: product.php?hon=".$hon);
        exit();
    }
  }

  $carts = $db->getCarts($_SESSION["userID"]);
} else {
  header("Location: view.php");
  exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<?php include_once("header.php"); ?>
<body>
<?php include_once("nav.php"); ?>
        <ol class="list-group">
            
        <li class="list-group-item grid" style="border-top: none; border-bottom: none;">
            <div class="row fw-bold px-3">
                Items:
            </div>
            <div class="row px-3">
                <ol class="list-group">
                  <?php
                    $i = 0;
                    $price = 0;
                    $quantity = 0;
                    $c = 0;
                    foreach($carts as $cart) {
                      $price = (int) $cart['price'];
                      $quantity = (int) $cart['quantity'];
                      ?>
                        <li class="list-group-item grid" style="border: none;">
                            <div class="card mb-3">
                                <div class="row g-0">
                                  <div class="col-md-2">
                                    <img src="<?= $cart['picture'] ?>" class="img-fluid rounded-start" alt="..." style="height: 200px; max-height: 200px; max-width: 200px;">
                                  </div>
                                  <div class="col-md-10">
                                    <div class="card-body">
                                      <p class="card-text">
                                        <?= $cart['title'] ?>
                                        <br>
                                        <small class="text-body-secondary"><?=$cart['author']?></small>
                                      </p>
                                      <div class="card-text position">
                                        <a href="updateCart.php?subItem=<?= $cart['line_id'] ?>&quantity=<?= $quantity ?>" class="btn col">-</a>
                                        <input  class="col text-center" type="text" style="width: 7%;" id="jumlah" value="<?= $quantity ?>" disabled>
                                        <a href="updateCart.php?addItem=<?= $cart['line_id'] ?>&quantity=<?= $quantity ?>" class="btn col">+</a>

                                        <small class="text-body-secondary"> x Rp <?= $price ?>.000</small>

                                        <a href="delete.php?item_id=<?= $cart['line_id'] ?>" class="btn btn-danger float-end translate-middle">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                          </svg>
                                        </a>
                                      </div>
                                    </div>
                                  </div>
                                  
                                </div>
                            </div>
                        </li>
                      <?php
                    $total += $price * $quantity;
                    $c++;
                    }
                  ?>
                    
                   
                    
                </ol>
            </div>
        </li>
        
       
        <li class="list-group-item grid" style="border-top: none; border-bottom: none;">
            <div class="row fw-bold px-3">
                Total
            </div>
            <div class="row px-3">
              Rp <?= $total ?>.000
            </div>
           
        </li>
        <li class="list-group-item grid" style="border-top: none; my-5 py-5">
          <form action="order.php" method="post">
                <input type="hidden" value="<?=$total?>" name="total">
                <?php
                if ($c > 0) {
                  ?>
                  <button class="btn float-end m-2 p-2 btn-primary" type="submit" name="order" style="margin-left: 100px;">Make an order</button>
                  <?php

                } else {
                  ?>
                  <button class="btn float-end m-2 p-2 btn-primary" type="submit" name="order" style="margin-left: 100px;" disabled>Make an order</button>
                  <?php
                }
                ?>
          </form>
        </li>
    </ol>
</div>
<?php include_once("footer.php")?>
</body>
</html>