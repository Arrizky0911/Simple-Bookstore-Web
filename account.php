<?php
session_start();

require_once 'config.php';

try {
    $db = new DBConnection();
} catch (\Throwable $th) {
    header("Location: error.php");
    exit();
}


if (isset($_SESSION['userID'])) {
    $id =  $_SESSION['userID'];
    $email = $_SESSION['userEmail'];
    $user = $db->getUserByID($_SESSION['userID'])->fetch();
    $first_name = $user['first_name'];
    $last_name = $user['last_name'];

    if(isset($_POST['add-address'])) {
        $street = $_POST['street'];
        $city = $_POST['city'];
        $country = $_POST['country'];
        $db->addAddress($id, $street, $city, $country);
    }
    if(isset($_POST['update-user'])) {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $db->updateUser($id, $fname, $lname);
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
    <?php include_once("nav.php"); ?>
    <div class=" container grid my-5 gap-0 column-gap-4">
            <div class="row">
                <div class="col-4">
                    <div class="accordion" id="accordionPanelsStayOpenExample" style="width: 80%; margin-left: 30px;">
                        <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button text-bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                Orders & Payment
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                            <ul class="list-group list-group-flush">
                                    <button type="submit" name="current" class="list-group-item list-group-item-action" id="current-transaction">Current Transaction</button>
                                    <button type="submit" name="history" class="list-group-item list-group-item-action" id="history-order">Orders History</button>
                            </ul>
                        </div>
                        </div>
                        <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button text-bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
                            Account Setting
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show">
                                <ul class="list-group list-group-flush">
                                    <button type="submit" class="list-group-item list-group-item-action" id="your-account">Your Account</button>
                                    <button type="submit" class="list-group-item list-group-item-action" id="your-address">Addresses</button>
                                    <form action="reset.php" method="post">
                                        <button type="submit" class="list-group-item list-group-item-action" id="reset">Reset Password</button>
                                    </form>
                                    <form action="logout.php" method="post">
                                        <button type="submit" class="list-group-item list-group-item-action" id="logout">Logout</button>
                                    </form>
                                </ul>
                            
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-8 grid row-gap-2" >
                    <div class="p-2 row text-center" style="margin-left: 50px; width: 80%;">
                        <h2>Profile Account</h2>
                    </div>
                    <div class="p-2 row rounded w-100" style="margin-left: 10px; <?= $account_table?>">
                        <div class="container grid" style="width: 100%;">
                            <div class="show-account row p-2">
                                <ol class="list-group" style="margin-bottom: 30px;">
                                    <li class="list-group-item fw-bold">Account Information</li>
                                    <li class="list-group-item">First Name : <?= $first_name?></li>
                                    <li class="list-group-item">Last Name : <?= $last_name?></li>
                                    <li class="list-group-item">Email : <?= $email?></li>
                                    <li class="list-group-item" style="border:none;">
                                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#updtUserModal" style="border:none;">
                                            Update User Information
                                        </button>
                                    </li>
                                </ol>
                            </div>
                            <div class="show-addresses row p-2" style="margin-top: 10px; display: none;">
                                <ol class="list-group" style="margin-bottom: 30px; border:none;">
                                    <li class="list-group-item fw-bold" style="border:none;">Your Addresses</li>
                                    <li class="list-group-item grid" style="border: none;">
                                        <?php 
                                    $addresses = $db->getAllAddressesByID($id);
                                    foreach($addresses as $address) {
                                        $address_id = $address['address_id'];
                                        ?>
                                        <div class="card">
                                        <div class="row g-0">
                                            <div class="col-md-10">
                                                <div class="card-body" style="margin: 10px;">
                                                <h5 class="card-text"><?=$address['street']?></h5>
                                                <p class="card-text"><?=$address['city']?></</p>
                                                <p class="card-text"><?=$address['country']?></</p>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <a class="btn btn-danger" href="delete.php?address_id=<?=$address_id;?>" style="height: 160px; width: 80px; margin-left: 30px;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash position-relative top-50 translate-middle" viewBox="0 0 16 16" style="margin-left: 16px;">
                                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    </li>
                                    <li class="list-group-item" style="border:none;">
                                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addAddressModal" style="border:none;">
                                            Add Address
                                        </button>
                                    </li>
                                </ol>
                                
                            </div>
                            <div class="show-history p-1" style="margin-top: 10px; display: none;" id="show-transaction">
                                <ol class="list-group" style="margin-bottom: 30px; border:none;">
                                    <li class="list-group-item fw-bold text-center" style="border:none;">History Order</li>
                                    <li class="list-group-item grid" style="border: none;">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                <th scope="col">OrderID</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Details</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $histories = $db->getHistoryOrder($id);
                                                foreach($histories as $history) {
                                                    ?>
                                                        <tr>
                                                            <td><?= $history['order_id']?></td>
                                                            <td><?= $history['order_date']?></td>
                                                            <td>
                                                                <a href="detailorder.php?orderID=<?= $history['order_id']?>" class="btn btn-primary">
                                                                        See
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                }
                                                ?>
                                                
                                            </tbody>
                                        </table>
                                    </li>
                                </ol>
                            </div>
                            <div class="show-transaction p-1" style="margin-top: 10px; display: none;" >
                                <ol class="list-group" style="margin-bottom: 30px; border:none;">
                                    <li class="list-group-item fw-bold text-center" style="border:none;">Current Order</li>
                                    <li class="list-group-item grid" style="border: none;">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                <th scope="col">OrderID</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Details</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $orders = $db->getCurrentOrder($id);
                                                foreach($orders as $order) {
                                                    ?>
                                                        <tr>
                                                            <td><?= $order['order_id']?></td>
                                                            <td><?= $order['order_date']?></td>
                                                            <td>
                                                                <a href="detailorder.php?orderID=<?= $order['order_id']?>" class="btn btn-primary">
                                                                        See
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                }
                                                ?>
                                                
                                            </tbody>
                                        </table>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once("footer.php")?>
        <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel"><span id="modal-title">Add Address</span></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="edit-address">
                            <form class="row g-3" action="" method="post">
                                <div class="col-md-12">
                                    <label for="validationServer11" class="form-label">Street</label>
                                    <input type="text-area" name="street" class="form-control" id="validationServer11" aria-describedby="validationServer03Feedback" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="validationServer12" class="form-label">City</label>
                                    <input type="text" name="city" class="form-control " id="validationServer12" aria-describedby="validationServer03Feedback" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="validationServer13" class="form-label">State</label>
                                    <div id="styled-select"> 
                                        <select name="country" class="form-select form-control " id="validationServer13" aria-describedby="validationServer03Feedback" aria-label="Default select example" required>
                                            <?php
                                            $countries = $db->getCountries();
                                            foreach($countries as $country){
                                                ?>
                                                <option value="<?= $country['country_name'];?>">
                                                    <?= $country['country_name'];?>
                                                </option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button class="btn btn-primary" name="add-address" type="submit">Add Address</button>
                                </div>
                            </form>
                        </div>                    
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="updtUserModal" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel1"><span id="modal-title1">Add Address</span></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="edit-user">
                            <form class="row g-3" action="account.php" method="post">
                                <div class="col-md-12">
                                    <label for="validationServer21" class="form-label">First name</label>
                                    <input type="text" name="fname" class="form-control" id="validationServer21" placeholder="<?= $first_name ?>" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="validationServer22" class="form-label">Last name</label>
                                    <input type="text" name="lname" class="form-control" id="validationServer22" placeholder="<?= $last_name ?>" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="validationServer23" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="validationServer23" aria-describedby="validationServer03Feedback" disabled placeholder="<?= $email ?>">
                                </div>
                                <div class="col-md-12">
                                    <button class="btn btn-primary" name="update-user" type="submit">Update</button>
                                </div>
                            </form>
                        </div>                    
                    </div>
                </div>
            </div>
        </div>


    </body>
</html>
