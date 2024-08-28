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
    header("Location: error.php");
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
    <?php include_once 'nav.php';?>
    <form method="post" action="" class="d-flex" role="search">
        <input class="form-control me-3 w-50 mb-3 ms-3 mt-3" name="search" id="exampleDataList" placeholder="Type to search...">
        <button class="btn" name="searchSubmit" id="search" type="submit">
            Search
        </button>
    </form>
    <div class="container-fluid mt-3 text-center" style="margin-bottom: 100px;">
        <table class="table table-sm table-bordered">
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Order Date</th>
                <th>Detail</th>
            </tr>
        <?php
         if (isset($_POST['searchSubmit']) && $_POST['search'] != "") {
            $orders = $db->findOrder($_POST['search']);
        } else {
            $orders = $db->getAllOrder();
        } 
        foreach ($orders as $row) {
        ?>
        <tr>
            <td><?= $row["order_id"]; ?></td>
            <td><?= $row["fname"]; ?> <?= $row["lname"]; ?></td>
            <td><?= $row["order_date"]; ?></td>
            <td>
                <a class="btn btn-primary" href="updateOrder.php?orderID=<?= $row['order_id']; ?>">Detail</a>
            </td>
        </tr>
        <?php
        }
        ?>
        </table>
    </div>

</body>
</html>