<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header("Location: ../view.php");
    exit();
}
include '../config.php';

try {
    $db = new DBConnection();
} catch (\Throwable $th) {
    header("Location: ../error.php");
    exit();
}

$title = "Detail Order";

if (isset($_GET['orderID'])) {
    $orderID = (int) $_GET['orderID'];
    $order = $db->getDetailsOrder($orderID)->fetch();
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

} if (isset($_POST['update'])) {
    $update = $_POST['status'];
    $orderID = $_POST['orderID'];
    $db->updateTransaction($orderID, $update);
    echo "<script>alert('The transaction has been updated.')</script>";
    header("Location: orderList.php");
    exit();  
} 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container my-5">
        <form method="post" action="" id="updateForm"></form>
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
                    <select class="form-select" aria-label="Default select example" form="updateForm" onchange="this.form.submit();">
                        <?php
                            $liststatus = $db->getAllStatus();
                            foreach ($liststatus as $row) {
                                $selected = $row["status_value"] == $status ? "selected" : "";
                                echo("<option value=\"{$row["status_value"]}\" {$selected}>");
                                echo $row["status_value"];
                                echo("</option>");
                            }
                        ?>
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
                <div class="float-end">
                    <a class="btn" href="orderList.php">back</a>
                </div>
            </li>
        </ol>
    </div>
    </body>
</html>