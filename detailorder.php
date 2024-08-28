<?php
session_start();
include 'config.php';

try {
    $db = new DBConnection();
} catch (\Throwable $th) {
    header("Location: error.php");
    exit();
}

if (isset($_SESSION["userID"])){
    $id = $_SESSION["userID"];
    $title = "Detail Order";
    
    if (isset($_GET['orderID'])) {
        $orderID = (int) $_GET['orderID'];
        $order = $db->getDetailsOrder($orderID)->fetch();
        if($order['user_id'] != $id) {
            header("Location: view.php");
            exit();

        }
        $carts = $db->getCartsOrders($orderID);
        $title = "Order Details";
        $street = $order['address'];
        $city = $order['city'];
        $status = $order['status'];
        $status_id = (int) $order['status_id'];
        $country = $order['country'];
        $shipment = $order['shipment'];
        $payment = $order['payment'];
        $total = $order['total'];

    } if (isset($_POST['cancel'])) {
        $update = "Cancelled";
        $orderID = $_POST['orderID'];
        $db->updateTransaction($orderID, $update);
        echo "<script>alert('Your transaction has been cancelled.')</script>";
        header("Location: account.php");
        exit();
        
    } else if (isset($_POST['finish'])) {
        $update = "Finished";
        $orderID = $_POST['orderID'];
        $db->updateTransaction($orderID, $update);
        echo "<script>alert('Your transaction has been finished.')</script>";
        header("Location: account.php");
        exit();
        

    }

    
} else {
    header("Location: view.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<?php include_once("header.php"); ?>
<body>
    <div class="container my-5">
        <form method="post" action="" id="cancelForm"></form>
        <form method="post" action="" id="finishForm"></form>
            <ol class="list-group">
                <li class="list-group-item fw-bold"><?= $title ?></li>
                <li class="list-group-item grid" style="border-top: none; border-bottom: none;">
                    <div class="m-2">
                        <label for="exampleFormControlTextarea1" class="form-label fw-bold">Shipping address</label>
                        <select class="form-select" aria-label="Default select example" disabled>
                            <option selected><?= $street ?>, <?= $city ?>, <?= $country ?> <option>
                        </select>
                    </div>
                </li>
                <li class="list-group-item grid" style="border-top: none; border-bottom: none;">
                    <div class="row fw-bold px-3">
                        Shipping Method
                    </div>
                    <div class="row p-3">
                        <select class="form-select" aria-label="Default select example" disabled>
                            <option selected><?= $shipment?><option>
                        </select>
                    </div>
                </li>
            <li class="list-group-item grid" style="border-top: none; border-bottom: none;">
            <div class="row fw-bold px-3">
                Items:
            </div>
            <div class="row px-3">
                <ol class="list-group">
                  <?php
                    $i = 0;
                    foreach($carts as $cart) {
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
                                        <small class="text-body-secondary"><?= $cart['quantity'] ?> x Rp <?= $cart['price'] ?>.000</small>
                                      </p>
                                    </div>
                                  </div>
                                  
                                </div>
                            </div>
                        </li>
                    <?php
                    }
                    ?>
                </ol>
            </div>
            </li>
            <li class="list-group-item" style="border-top: none; border-bottom: none;">
                <div class="row fw-bold px-3">
                    Order Status
                </div>
                <div class="row p-3">
                    <select class="form-select" aria-label="Default select example" disabled>
                        <option selected><?= $status?><option>
                    </select>
                </div>
            </li>
            <li class="list-group-item grid" style="border-top: none; border-bottom: none;">
                <div class="row fw-bold px-3">
                    Payment Method
                </div>
                <div class="row p-3">
                    <select class="form-select" aria-label="Default select example" disabled>
                        <option selected><?= $payment?><option>
                    </select>
                </div>
            </li>
            <li class="list-group-item grid" style="border-top: none; border-bottom: none;">
                <div class="row fw-bold px-3">
                    Total
                </div>
                <div class="row fw-bold px-3">
                    Rp <?= $total ?>.000
                </div>
            </li>
            <li class="list-group-item grid" style="border-top: none;">
                <?php
                if ($status_id < 4) {
                    ?>
                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                            <input type="hidden" name="orderID" value="<?=$orderID?>" form="cancelForm">
                            <button type="submit" class="btn btn-outline-danger" name="cancel" form="cancelForm">Cancel</button>
                        </div>
                    <?php
                    
                } else if ($status_id < 5) {
                    ?>
                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                            <input type="hidden" name="orderID" value="<?=$orderID?>" form="finishForm">
                            <button type="submit" class="btn btn-outline-success" name="finish" form="finishForm">Finish</button>
                        </div>
                    <?php

                } else {
                    ?>
                        <div class="row px-3 text-danger-emphasis">
                                Your order is <?= $status ?>
                        </div>
                    <?php
                }
                ?>
                <div class="float-end">
                    <a class="btn" href="account.php">back</a>
                </div>
            </li>
        </ol>
    </div>
    <?php 
    include_once("footer.php");
    ?>
    </body>
</html>