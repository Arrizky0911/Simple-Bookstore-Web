<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header("Location: ../view.php");
    exit();
}
require_once("../config.php");
$db = new DBConnection();
if (isset($_POST["submit"]) and isset($_GET["id"])) {
    $db->deleteAuthor($_GET["id"]);
    header("Location: authorList.php");
}
?>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<div class="container-fluid text-center" style="margin-top: 20px;">
    <form action="" method="post">
        Are you sure you want to delete this data?<br/>
        <input class="mt-3 btn btn-danger" type="submit" name="submit" value="Confirm"/>
        <a class="mt-3 btn btn-info" href="authorList.php">Cancel</a>
    </form>
</div>