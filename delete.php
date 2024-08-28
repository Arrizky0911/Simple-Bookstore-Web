<?php
include_once 'config.php'; 

try {
    $db = new DBConnection();
} catch (\Throwable $th) {
    header("Location: error.php");
    exit();
}
if (isset($_GET['address_id'])) {
    $db->deleteAddress($_GET['address_id']);
    header("Location: account.php");
    exit();

} else if (isset($_GET['item_id'])) {
    $db->deleteCart($_GET['item_id']);
    header("Location: cart.php");
    exit();

} else {
    echo "<script>alert('Woops! Something went wrong.')</script>";
}
header("Location: view.php");
exit();
?>