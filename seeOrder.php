<?php
session_start();
include_once 'config.php'; 

try {
    $db = new DBConnection();
} catch (\Throwable $th) {
    header("Location: error.php");
    exit();
}
if (isset($_GET['id'])) {
    $_SESSION['orderID'] = $_GET['id'];
    header("Location: order.php");
    $_SESSION['orderID'] = array();
    exit();
    

} else {
    echo '<script type="text/javascript">alert("Woops! Something went wrong.")</script>';
}
header("Location: account.php");
exit();
?>