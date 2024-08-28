<?php
include_once 'config.php'; 

try {
    $db = new DBConnection();
} catch (\Throwable $th) {
    header("Location: error.php");
    exit();
}

if (isset($_GET['subItem'])) {
    $line = (int) $_GET['subItem'];
    $n = (int) $_GET['quantity'];
    $n -= 1;
    if ($n > 0) {
      $db->updateCart($line, $n);
    } else {
        echo "<script>alert('Woops! The numbers is cannot be 0.')</script>";
    }
}

if (isset($_GET['addItem'])) {
    $line = (int) $_GET['addItem'];
    $results = $db->getCartsItem($line);
    $result = $results->fetch();
    $n = (int) $_GET['quantity'];
    $book_id = $result['book_id'];
    $n += 1;
    $val_stocks = $db->validateStock($book_id, $n);
    if ($val_stocks) {
        $db->updateCart($line, $n);
    } else {
        echo "<script>alert('Woops! The numbers is out of stock.')</script>";
    }
  }

header("Location: cart.php");
exit();
?>