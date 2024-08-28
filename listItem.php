<?php
session_start();
include 'config.php';

try {
    $db = new DBConnection();
} catch (\Throwable $th) {
    header("Location: error.php");
    exit();
}

if (isset($_POST['searchSubmit']) && $_POST['search'] != ""){
    $key = $_POST['search'];
    $books = $db->findBooks($key);
   
} else {
    $books = $db->getAllBooks();
}

?>


<!DOCTYPE html>
<html lang="en">
<?php include_once("header.php"); ?>
<body>
    <?php include_once("nav.php"); ?>
    

    <div class="container mx-6 my-5">
        <table class="table table-borderless">
            <tbody>
                <?php
                    $i = 0;
                    foreach($books as $book) {
                        if (($i+1) % 5 == 1) {
                            ?>
                            <tr>
                            <?php
                        }
    
                        ?>
                            <td class="position-relative me-2 ms-2" style="height: 380px; max-height: 380px; max-width: 220px; width: 220px;">
                                <div class="card text-center" style="height: 380px; max-height: 380px; max-width: 220px; width: 220px;">
                                    <img class="card-img-top" src="<?= $book['picture'] ?>" alt="Card image cap" style="height: 200px; max-height: 200px;">
                                    <div class="card-body">
                                        <a class="link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hove" href="product.php?hon=<?=$book['book_id']?>">
                                        <?= $book["title"] ?>
                                        </a>
                                        <p class="card-text"><small class="text-muted"><?= $book["author"] ?></small></p>
                                        <p class="card-text"><small class="text-muted">Rp <?= $book["price"] ?>.000</small></p>
                                    </div>
                                </div> 
                            </td>
                        
                        <?php
    
                        $i++;
                        if ($i % 5 == 0) {
                            ?>
                            </tr>
                            <?php
                        }
    
                        
                    }
                    if ($i % 5 != 0) {
                        for ($j = 0; $j < (5 - ($i % 5)); $j++) {
                            echo '<td></td>';
                        }
                    }
                    if ($i == 0) {
                        ?>
                        <h1>There is no such as product</h1>
                        <?php
                    }
                ?>
                </tr>
            </tbody>
        </table>
    </div>
    <?php include_once("footer.php"); ?>
</body>
</html>