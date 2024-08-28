<?php
session_start();
include_once 'config.php'; 

try {
    $db = new DBConnection();
} catch (\Throwable $th) {
    header("Location: error.php");
    exit();
}

if (isset($_GET["hon"])) {
    $i = (int) $_GET["hon"];
    $results = $db->getBooksById($i);
    $title = $cover = $author = $major = "";
    if ($results) {
        $book_details = $results->fetch();
    } else {
        header("Location: error.php");
        exit();
    }
} else {
    header("Location: view.php");
    exit();
}

$books = $db->getCarouselBooks();

?>


<!DOCTYPE html>
<html lang="en">
    <?php include_once("header.php"); ?>
<body>
    <?php include_once("nav.php"); ?>
    <div class="container grid">
        <div class="row">
            <div class="col-5 p-2 my-3" >
                <figure class="figure p-4">
                    <img src="<?= $book_details['picture'] ?>" class="figure-img img-fluid rounded" alt="<?= $book_details['title'] ?></" style="height: 200px; max-height: 200px; max-width: 200px;">
                </figure>
            </div>
            <div class="col-7 p-2 my-3 grid">
                <ol class="list-group" style="border: none;">
                <form action="cart.php" method="post">
                    <input type="hidden" name="bookID" value="<?= $_GET["hon"] ?>">
                    <li class="list-group-item fw-bold" style="border: none;"><?= $book_details['title'] ?></li>
                    <li class="list-group-item" style="border: none;"><?= $book_details['cover'] ?></li>
                    <li class="list-group-item" style="border: none;"><?= $book_details['author'] ?></li>
                    <li class="list-group-item" style="border: none;">Rp <?= $book_details['price'] ?>.000</li>
                    <li class="list-group-item grid text-center" style="border: none;">
                        <button class="col btn" id="kurang">-</button>
                        <input  class="col text-center" name="items" type="text" style="width: 7%;" id="jumlah" value="1">
                        <button class="col btn" id="tambah">+</button>
                    </li>
                    <li class="list-group-item grid text-center" style="border: none;">
                        <?php
                        if (isset($_SESSION['userID'])) {
                        ?>
                        <button class="btn p-3" name="addCart" type="submit">Add to Chart</button>
                        <?php
                        } else {
                        ?>
                        <button type="button" class="btn p-3" data-bs-toggle="modal" data-bs-target="#exampleModal">Add to Chart</button>
                        <?php
                        }
                        ?>
                    </li>     
                </form> 
                </ol>
            </div>
        </div>
        <div class="row p-2 my-3 text-center">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">ISBN</th>
                        <th scope="col">Publisher</th>
                        <th scope="col">Publication Date</th>
                        <th scope="col">Pages</th>
                    </tr>
                </thead>
            <tbody>
                <tr>
                    <td><?=  $book_details['isbn'] ?></td>
                    <td><?=  $book_details['publisher']  ?></td>
                    <td><?=  $book_details['publication_date']  ?></td>
                    <td><?=  $book_details['pages'] ?></td>
                </tr>
            </tbody>
            </table>
        </div>
        <div class="container p-1 m-6 row">
            <ol class="list-group">
                <li class="list-group-item fw-bold">Editor's Pick</li>
                <li class="list-group-item">

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
                </li>
            </ol>
            </div>
        
        
    </div>
    <?php include_once("footer.php"); ?>
</body>
</html>