<?php
session_start();
include 'config.php';

try {
    $db = new DBConnection();
} catch (\Throwable $th) {
    header("Location: error.php");
    exit();
}
 
if (isset($_POST['login'])) {
    session_unset();
    $email = $_POST['email'];
    $password = hash('sha256', $_POST['password']); // Hash the input password using SHA-256
 
    $result = $db->login($email, $password);
    $row = $result->fetch();
    if (isset($row['user_id'])) {
        $_SESSION['userID'] = $row['user_id'];
        $_SESSION['userEmail'] = $email;
        $email = "";
        $_POST['password'] = "";
    } else {
        echo "<script>alert('Your Email or Password is wrong. Please try again!')</script>";
    }
}

$books = $db->getCarouselBooks();
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once("header.php"); ?>
<body>
    <?php include_once("nav.php"); ?>
    <div id="carouselExampleRide" class="carousel slide mb-5" data-bs-ride="true">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="assets/Background-1.png" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="assets/Background-2.png" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="assets/Background-3.png" class="d-block w-100" alt="...">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleRide" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleRide" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <div class="container text-center p-1 w-100 mb-3 mt-5 rounded" style="background-color: --secondary;">
    <div class="grid gap-0 row-gap-3">
    <div class="row mb-4 mt-3">
        <div class="col"><h1>Editor's Pick</h1></div>
    </div>
    <div class="row w-100">
        <div id="carousel-card-1" class="carousel carousel-dark slide mb-4">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="container text-center mx-6">
                        <div class="card-deck row">
                <?php 
                $i = 1;
                foreach($books as $book) {
                    if (($i % 5) == 1 and $i != 1) {
                        ?>
                         </div>
                            </div>
                            </div>
                            <div class="carousel-item">
                                <div class="container text-center">
                                    <div class="card-deck row">
                        <?php
                    }

                    
                    ?>
                            <div class="card col">
                                <img class="card-img-top" src="<?= $book['picture'] ?>" alt="Card image cap" style="height: 200px; max-height: 200px; max-width: 200px;">
                                <div class="card-body">
                                    <a class="link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hove" href="product.php?hon=<?=$book['book_id']?>">
                                    <?= $book["title"] ?>
                                    </a>
                                    <p class="card-text"><small class="text-muted"><?= $book["author"] ?></small></p>
                                    <p class="card-text"><small class="text-muted">Rp <?= $book["price"] ?>.000</small></p>
                                </div>
                            </div>
                    <?php
                    $i++;
                }
                ?>
            </div>
            </div>
            </div>
            </div>
            
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-card-1" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carousel-card-1" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    </div>
    </div>
    <?php include_once("footer.php"); ?>
</body>
</html>

