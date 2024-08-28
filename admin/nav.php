<?php
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();  
    header("Location: ../view.php");
    exit();
}

?>

<form method="post" action="" id="logout"></form>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid ms-3 me-3">
        <div class="nav-brand fw-bold">Admin</div>
        <a class="nav-link" href="bookList.php">Books</a>
        <a class="nav-link" href="authorList.php">Authors</a>
        <a class="nav-link" href="publisherList.php">Publishers</a>
        <a class="nav-link" href="languageList.php">Languages</a>
        <a class="nav-link" href="orderList.php">Orders</a>
        <button class="nav-link btn" type="submit" name="logout" form="logout">Log Out</button>
    </div>
</nav>