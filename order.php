<?php
session_start();
include 'config.php';

try {
    $db = new DBConnection();
} catch (\Throwable $th) {
    header("Location: error.php");
    exit();
}

$destination = $street = $city = $country = $shipment = $payment = $select = "";
$disc = $shipcost = $total = $orderID = 0;
$dest = "Choose destination address";
$ship = "Choose shipment method";
$pay = "Choose payment method";
if (isset($_SESSION["userID"])){
    $id = $_SESSION["userID"];
    $title = "Review Order";

    if (isset($_POST['order'])) {
        $total = $total_cart = (int) $_POST['total'];
        $title = "Review Order";
        $carts = $db->getCarts($id);
        
    } else if (isset($_POST['shipmethod'])){
        $shipment = $_POST['shipmethod'];
        $carts = $db->getCarts($id);
        $total_cart = (int) $_POST['totalCartShip'];
        $disc = (int) $_POST['discShip'];
        $shipmethod = $db->getShipMethod($shipment)->fetch();
        $shipcost = (int) $shipmethod['cost'];
        $ship = $shipmethod['method_name']  . " - Rp " . $shipcost . ".000";;
        $total = $total_cart + $shipcost - $disc;
        $destination = $_POST['destinationShip'];
        $payment = $_POST['paymentShip'];
        if ($destination != "") {
            $dest_address = $db->getAllDetailAddressByID($destination)->fetch();
            $street = $dest_address['street'];
            $city = $dest_address['city'];
            $country = $dest_address['country'];
            $dest = $street . ", " . $city . ", " . $country;
        }
        if ($payment != "") {
            $paymethod = $db->getPayMethod($payment)->fetch();
            $pay = $paymethod['method_name'];

        }
    } else if (isset($_POST['destination'])){
        $carts = $db->getCarts($id);
        $destination = $_POST['destination'];
        $dest_address = $db->getAllDetailAddressByID($destination)->fetch();
        $street = $dest_address['street'];
        $city = $dest_address['city'];
        $country = $dest_address['country'];
        $payment = $_POST['paymentAddress'];
        $disc = (int) $_POST['discAddress'];
        $shipment = $_POST['shipmentAddress'];
        $shipcost = $_POST['shipCostAddress'];
        if ($shipment != "") {
            $shipmethod = $db->getShipMethod($shipment)->fetch();
            $ship = $shipmethod['method_name']  . " - Rp " . $shipcost . ".000";;
        }
        if ($payment != "") {
            $paymethod = $db->getPayMethod($payment)->fetch();
            $pay = $paymethod['method_name'];
        }
        $total_cart = (int) $_POST['totalCartAddress'];
        $total = $total_cart + $shipcost - $disc;
        $dest = $street . ", " . $city . ", " . $country;
        
    } else if (isset($_POST['useVoucher'])){
        $carts = $db->getCarts($id);
        $destination = $_POST['destinationVoucher'];
        $shipment = $_POST['shipmentVoucher'];
        $shipcost = (int) $_POST['shipCostVoucher'];
        $payment = $_POST['paymentVoucher'];
        if ($destination != "") {
            $dest_address = $db->getAllDetailAddressByID($destination)->fetch();
            $street = $dest_address['street'];
            $city = $dest_address['city'];
            $country = $dest_address['country'];
            $dest = $street . ", " . $city . ", " . $country;
        }
        if ($shipment != "") {
            $shipmethod = $db->getShipMethod($shipment)->fetch();
            $ship = $shipmethod['method_name'] . " - Rp " . $shipcost . ".000";;
        }
        if ($payment != "") {
            $paymethod = $db->getPayMethod($payment)->fetch();
            $pay = $paymethod['method_name'];
            
        }
        $code = $_POST['voucherCode'];
        $total_cart = (int) $_POST['totalCartVoucher'];
        $voucher = $db->checkVoucher($code)->fetch();
        if ($voucher['code'] == $code) {
            $discount = (int) $voucher['disc'];
            $disc = $total_cart * $discount;
            $total = $total_cart + $shipcost - $disc;
        } else {
            echo '<script>alert("Your voucher is wrong")</script>';
        }
    } else if (isset($_POST['paymethod'])) {
        $payment = $_POST['paymethod'];
        $carts = $db->getCarts($id);
        $destination = $_POST['destinationPay'];
        $shipment = $_POST['shipmentPay'];
        $shipcost = $_POST['shipCostPay'];
        $disc = $_POST['discPay'];
        if ($destination != "") {
            $dest_address = $db->getAllDetailAddressByID($destination)->fetch();
            $street = $dest_address['street'];
            $city = $dest_address['city'];
            $country = $dest_address['country'];
            $dest = $street . ", " . $city . ", " . $country;
        }
        if ($shipment != "") {
            $shipmethod = $db->getShipMethod($shipment)->fetch();
            $ship = $shipmethod['method_name'] . " - Rp " . $shipcost . ".000";
        }
        $total_cart = (int) $_POST['totalCartPay'];
        $total = $total_cart + $shipcost - $disc;
        $paymethod = $db->getPayMethod($payment)->fetch();
        $pay = $paymethod['method_name'];

    } else if (isset($_POST['checkout'])) {
        $total = (int) $_POST['totalCheckOut'];
        $destination = $_POST['destinationCheckOut'];
        $shipment = $_POST['shipmentCheckOut'];
        $payment = $_POST['paymentCheckOut'];
        if ($destination == "" || $shipment == "" || $payment == "") {
            if ($destination == "") {
                echo "<script>alert('Please choose the destination address.')</script>";
                
            } else {
                $dest_address = $db->getAllDetailAddressByID($destination)->fetch();
                $street = $dest_address['street'];
                $city = $dest_address['city'];
                $country = $dest_address['country'];
                $dest = $street . ", " . $city . ", " . $country;
                if ($shipment == "") {
                    echo "<script>alert('Please choose the shipment method.')</script>";
                    
                } else {
                    $shipmethod = $db->getShipMethod($shipment)->fetch();
                    $ship = $shipmethod['method_name'];
                    if ($payment == "") {
                        echo "<script>alert('Please choose the payment method.')</script>";
                        
                    } else {
                        $paymethod = $db->getPayMethod($payment)->fetch();
                        $pay = $paymethod['method_name'];
                    }
                }
            }
            
        } else {
            $db->checkout($id, $total, $destination, $shipment, $payment);
            header("Location: account.php");
            exit();
            
        }
    } else {
        echo "<script>alert('Something went wrong.')</script>";
        header("Location: cart.php");
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
        <form id="voucherForm" method="post" action=""></form>
        <form id="address" method="post" action=""></form>
        <form id="shipment" method="post" action=""></form>
        <form id="payment" method="post" action=""></form>
        <form id="checkoutForm" method="post" action=""></form>
        <form method="post" action="" id="cancelForm"></form>
        <form method="post" action="" id="finishForm"></form>
            <ol class="list-group">
                <li class="list-group-item fw-bold"><?= $title ?></li>
                <li class="list-group-item grid" style="border-top: none; border-bottom: none;">
                    <div class="m-2">
                        <label for="exampleFormControlTextarea1" class="form-label fw-bold">Shipping address</label>
                        <input type="hidden" name="totalCartAddress" value="<?=$total?>" form="address">
                        <input type="hidden" name="shipmentAddress" value="<?=$shipment?>" form="address">
                        <input type="hidden" name="paymentAddress" value="<?=$payment?>" form="address">
                        <input type="hidden" name="discAddress" value="<?=$disc?>" form="address">
                        <input type="hidden" name="shipCostAddress" value="<?=$shipcost?>" form="address">
                        <select class="form-select" name="destination" aria-label="Default select example" form="address"  onchange="this.form.submit()">
                            <option value="" selected><?=$dest?></option>
                            <?php
                                $addresses = $db->getAllAddressesByID($id);
                                foreach($addresses as $address) {
                                    $address_id = $address['address_id'];
                                    if ($address_id == $destination) {
                                        continue;
                                    }
                                    ?>
                                        <option value="<?= $address_id;?>"><?=$address['street']?>, <?=$address['city']?>, <?=$address['country']?></option>
                                    <?php
                                }
                            ?>
                        </select>
                    </div>
                </li>
                <li class="list-group-item grid" style="border-top: none; border-bottom: none;">
                    <div class="row fw-bold px-3">
                        Shipping Method
                    </div>
                    <div class="row p-3">
                        <input type="hidden" name="totalCartShip" value="<?=$total_cart?>" form="shipment">
                        <input type="hidden" name="destinationShip" value="<?=$destination?>" form="shipment">
                        <input type="hidden" name="paymentShip" value="<?=$payment?>" form="shipment">
                        <input type="hidden" name="discShip" value="<?=$disc?>" form="shipment">
                        <select class="form-select" name="shipmethod" aria-label="Default select example" form="shipment"  onchange="this.form.submit()">
                            <option value="" selected><?=$ship?></option>
                            <?php
                            $ships = $db->getAllShipMethod();
                            foreach($ships as $ship) {
                                $shipment_id = $ship['method_id'];
                                if ($shipment_id == $shipment) {
                                    continue;
                                }
                                ?>
                                    <option value="<?= $shipment_id?> ">
                                    <?= $ship['method_name']?> -  Rp. <?= $ship['cost']?>.000
                                    </option>
                                <?php
                            }
                            ?>
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
                <div class="row ">
                    <div class="form-checker m-2">
                        <input class="form-check-input check-voucher" type="checkbox" value="" id="someCheckbox">
                        <label class="form-check-label" for="flexCheckDefault">
                            Use coupon or voucher
                        </label>
                    </div>
                </div>
                <div class="row collapse" id="collapseContainer">
                    <div class="row m-2 voucher">
                        <input type="text"class="form-control" form="voucherForm" name="voucherCode">
                        <input type="hidden" name="totalCartVoucher" value="<?=$total_cart?>" form="voucherForm">
                        <input type="hidden" name="shipmentVoucher" value="<?=$shipment?>" form="voucherForm">
                        <input type="hidden" name="shipCostVoucher" value="<?=$shipcost?>" form="voucherForm">
                        <input type="hidden" name="destinationVoucher" value="<?=$destination?>" form="voucherForm">
                        <input type="hidden" name="paymentVoucher" value="<?=$payment?>" form="voucherForm">
                    </div>
                    <div class="row float-end m-2 voucher">
                        <button class="btn" type="submit" form="voucherForm" name="useVoucher">Use</button>
                    </div>
                </div>
            </li>
            <li class="list-group-item grid" style="border-top: none; border-bottom: none;">
                <div class="row fw-bold px-3">
                    Payment Method
                </div>
                <div class="row p-3">
                    <input type="hidden" name="totalCartPay" value="<?=$total_cart?>" form="payment">
                    <input type="hidden" name="shipmentPay" value="<?=$shipment?>" form="payment">
                    <input type="hidden" name="shipCostPay" value="<?=$shipcost?>" form="payment">
                    <input type="hidden" name="discPay" value="<?=$disc?>" form="payment">
                    <input type="hidden" name="destinationPay" value="<?=$destination?>" form="payment">
                    <select class="form-select" name="paymethod" aria-label="Default select example" form="payment"  onchange="this.form.submit()">
                        <option value="" selected><?=$pay?></option>
                        <?php
                            $paymentss = $db->getAllPayMethod();
                            foreach($paymentss as $payments) {
                                $payment_id = $payments['method_id'];
                                if ($payment_id == $payment) {
                                    continue;
                                }
                                ?>
                                    <option value="<?= $payments['method_id']?>">
                                        <?= $payments['method_name']?>
                                    </option>
                                <?php
                            }
                            ?>
                    </select>
                </div>
            </li>
            <li class="list-group-item grid" style="border-top: none; border-bottom: none;">
                <div class="row fw-bold px-3">
                    Total
                </div>
                <?php 
                    if ($shipment != ""){
                        ?>
                            <div class="row px-3">
                                Rp  <?= $total_cart ?>.000
                            </div>
                            <div class="row px-3">
                                Rp  <?= $shipcost ?>.000
                            </div>
                        <?php
                        if ($disc > 0){
                            ?>
                            <div class="row px-3 text-danger-emphasis">
                                Rp -<?= $disc ?>.000
                            </div>
                            
                            <?php
                        }
                    }
                ?>

                <div class="row fw-bold px-3">
                    Rp <?= $total ?>.000
                </div>
            </li>
            <li class="list-group-item grid" style="border-top: none;">
                <a class="btn btn-outline-dark m-2 p-2" href="cart.php">back</a>
                <input type="hidden" name="destinationCheckOut" value="<?=$destination?>" form="checkoutForm">
                <input type="hidden" name="shipmentCheckOut" value="<?=$shipment?>" form="checkoutForm">
                <input type="hidden" name="paymentCheckOut" value="<?=$payment?>" form="checkoutForm">
                <input type="hidden" name="totalCheckOut" value="<?=$total?>" form="checkoutForm">
                <button class="btn float-end m-2 p-2 rounded btn-outline-dark" type="submit" form="checkoutForm" name="checkout">Checkout</button>
            </li>
        </ol>
    </div>
    <?php 
    include_once("footer.php");
    ?>
    </body>
</html>