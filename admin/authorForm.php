<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header("Location: ../view.php");
    exit();
}

require_once("../config.php");
try {
    $db = new DBConnection();
} catch (\Throwable $th) {
    header("Location: ../error.php");
    exit();
}

$errors = [];

if (isset($_POST["submit"])) {
    if (empty($_POST["name"])) {
        array_push($errors, "Name is required");
    }

    $name = $_POST["name"];

    if (count($errors) == 0) {
        $db->addAuthors($name);
        header("Location: authorList.php");
    }
}
?>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<div class="container mt-4">
    <form class="form" action="" method="post">
        <div class="row mt-3">
            <div class="col-4">
                <label for="name">Author Name</label>
                <input class="form-control" type="text" name="name" placeholder="Enter Author Name"/>
            </div>
        </div>
        <input class="mt-3 btn btn-primary" type="submit" name="submit" value="Add Record"/>
        <a class="mt-3 btn btn-warning" href="authorList.php">Cancel</a>
    </form>
    <div class="mt-4">
        <ul class="error">
            <?php
            foreach ($errors as $err) {
                echo("<li>{$err}</li>");
            }
            ?>
        </ul>
    </div>
</div>