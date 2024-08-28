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
    header("Location: ../error.php");
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
        <div class="float-end mb-3 me-3 mt-3">
            <a class="btn btn-success" href="bookForm.php">Add New Data</a>
        </div>
        <form method="post" action="" class="d-flex" role="search">
            <input class="form-control me-3 w-50 mb-3 ms-3 mt-3" name="search" id="exampleDataList" placeholder="Type to search...">
            <button class="btn" name="searchSubmit" id="search" type="submit">
                Search
            </button>
        </form>
        <table class="table table-sm table-bordered object-fit-contain text-center" style="margin-right: 100px;">
        <tr>
            <th>Title</th>
            <th>Picture</th>
            <th>ISBN</th>
            <th>Author</th>
            <th>Publication Date</th>
            <th>Publisher</th>
            <th>Language</th>
            <th>Cover</th>
            <th>Pages</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        <?php
        if (isset($_POST['searchSubmit']) && $_POST['search'] != "") {
            $books = $db->findBooks($_POST['search']);
        } else {
            $books = $db->getAllBooks();
        }        
        foreach ($books as $row) {
        ?>
        <tr>
            <td><?= $row["title"]; ?></td>
            <td><img src="<?= $row["picture"]; ?>" style="height: 200px; max-height: 200px; max-width: 200px;"></td>
            <td><?= $row["isbn"]; ?></td>
            <td><?= $row["author"] ?></td>
            <td><?= $row["publication_date"]; ?></td>
            <td><?= $row["publisher"]; ?></td>
            <td><?= $row["language"]; ?></td>
            <td><?= $row["cover"] ?></td>
            <td><?= $row["pages"] ?></td>
            <td><?= $row["price"] ?>.000</td>
            <td><?= $row["stock"] ?></td>
            <td>
                <a class="btn btn-warning" href="bookForm.php?id=<?= $row['book_id']; ?>">Edit</a>
            </td>
            <td>
                <a class="btn btn-danger" href="deleteBook.php?id=<?= $row['book_id']; ?>">Delete</a>
            </td>
        </tr>
        <?php
        }
        ?>
        </table>
</body>
</html>