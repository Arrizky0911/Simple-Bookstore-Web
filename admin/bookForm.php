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


$title = $isbn = $author = $publication_date = $publisher = $language = $picture = $cover = $pages = $price = $stock = "";
if (isset($_GET["id"])) {
    $result = $db->getBooksById($_GET["id"]);
    $pages = $price = $stock = 0;
    if ($result) {
        $row = $result->fetch();
        $title = $row["title"];
        $isbn = $row["isbn"];
        $author = $row["author"];
        $time = strtotime($row["publication_date"]);
        $publication_date = date('Y-m-d',$time);
        $publisher = $row["publisher"];
        $language = $row["language"];
        $picture = $row["picture"];
        $cover = $row["cover"];
        $pages = (int) $row["pages"];
        $stock = (int) $row["stock"];
        $price = (int) $row["price"];
        $price *= 1000;
    }
}

if (isset($_POST["submit"])) {
    // check name first, set error if no value
    if (empty($_POST["title"])) {
        array_push($errors, "Title is required");
    }
    
    if (empty($_POST["isbn"])) {
        array_push($errors, "NIM is required");
    } 

    if (empty($_POST["author"])) {
        array_push($errors, "Author is required");
    }
    if (empty($_POST["publication_date"])) {
        array_push($errors, "Publication date is required");
    }
    if (empty($_POST["publisher"])) {
        array_push($errors, "Publisher is required");
    }
    if (empty($_POST["language"])) {
        array_push($errors, "Language is required");
    }
    if (empty($_POST["picture"])) {
        array_push($errors, "Picture link is required");
    }
    if (empty($_POST["cover"])) {
        array_push($errors, "Cover is required");
    }
    if (empty($_POST["price"])) {
        array_push($errors, "Price is required");
    }
    if (empty($_POST["stock"])) {
        array_push($errors, "Stock is required");
    }
    if (empty($_POST["pages"])) {
        array_push($errors, "Pages is required");
    }

    // save data and go back to index.php (list of students)
    $title = $_POST["title"];
    $isbn = $_POST["isbn"];
    $author = $_POST["author"];
    $publication_date = $_POST["publication_date"];
    $publisher = $_POST["publisher"];
    $language = $_POST["language"];
    $cover = $_POST["cover"];
    $pages = (int) $_POST["pages"];
    $price = (int) $_POST["price"];
    $stock = (int) $_POST["stock"];
    $price /= 1000;

    if (count($errors) == 0) {
        if (isset($_GET["id"])) {
            $db->updateBook($_GET["id"], $title, $author, $isbn, $price, $pages, $language, $stock, $cover, $publication_date, $publisher, $picture);
        } else {
            $db->addBook($title, $author, $isbn, $price, $pages, $language, $stock, $cover, $publication_date, $publisher, $picture);
        }
        header("Location: bookList.php");
    }
}
?>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>
<body>
<div class="container mt-4 w-100">
    <form class="form" action="" method="post">
        <div class="row mt-3">
            <div class="col-4">
                <label for="name">Title</label>
                <input class="form-control" type="text" name="title"
                    value="<?php echo($title); ?>" placeholder="Enter Book Title"/>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-4">
                <label for="nim">ISBN</label>
                <input class="form-control" type="text" name="isbn"
                    value="<?php echo($isbn); ?>" placeholder="Enter Book ISBN"/>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-4">
                <label for="nim">Cover</label>
                <input class="form-control" type="text" name="cover"
                    value="<?php echo($cover); ?>" placeholder="Enter Book Cover"/>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-4">
                <label for="nim">Picture Link</label>
                <input class="form-control" type="text" name="picture"
                value="<?php echo($picture); ?>" placeholder="Enter Book Picture Link"/>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-4">
                <label for="nim">Publication Date</label>
                <input class="form-control" type="date" name="publication_date"
                    value="<?php echo($publication_date); ?>" placeholder="Enter Book Publication Date"/>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-4">
                <label for="nim">Price</label>
                <input class="form-control" type="number" name="price"
                    value="<?php echo($price); ?>" placeholder="Enter Book Price"/>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-4">
                <label for="nim">Stock</label>
                <input class="form-control" type="number" name="stock"
                    value="<?php echo($stock); ?>" placeholder="Enter Book Stock"/>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-4">
                <label for="nim">Number of Pages</label>
                <input class="form-control" type="number" name="pages"
                    value="<?php echo($pages); ?>" placeholder="Enter Number of Pages"/>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-4">
                <label for="author">Author</label>
                <select name="author" id="authorSelect">
                    <?php
                        $writers = $db->getAllAuthors();
                        foreach ($writers as $writer) {
                            $author_name = $writer["author_name"];
                            $selected = $author_name == $author ? "selected" : "";
                            echo("<option value=\"{$author_name}\" {$selected}>");
                            echo $author_name;
                            echo("</option>");
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-4">
                <label for="author">Publisher</label>
                <select name="publisher" id="publisherSelect">
                    <?php
                        $penerbits = $db->getAllPublishers();
                        foreach ($penerbits as $penerbit) {
                            $publisher_name = $penerbit['publisher_name'];
                            $selected = $publisher_name == $publisher ? "selected" : "";
                            echo("<option value=\"{$publisher_name}\" {$selected}>");
                            echo $publisher_name;
                            echo("</option>");
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-4">
                <label for="author">Language</label>
                <select name="language" id="languageSelect">
                    <?php
                        $languages = $db->getAllLanguages();
                        foreach ($languages as $lang) {
                            $selected = $lang["language_name"] == $language ? "selected" : "";
                            echo("<option value=\"{$lang["language_name"]}\" {$selected}>");
                            echo("{$lang["language_name"]} - {$lang['language_code']}");
                            echo("</option>");
                        }
                    ?>
                </select>
            </div>
        </div>
        <input class="mt-3 btn btn-primary" type="submit" name="submit" value="Add Record"/>
        <a class="mt-3 btn btn-warning" href="bookList.php">Cancel</a>
    </form>
    <div class="mt-4">
        <ul class="text-danger">
            <?php
            foreach ($errors as $err) {
                echo("<li>{$err}</li>");
            }
            ?>
        </ul>
    </div>
</div>
<body>
<script type="text/javascript">	
	$(document).ready(function() {
		$('#authorSelect').select2();
		$('#publisherSelect').select2();
		$('#languageSelect').select2();
	});
</script>
</html>