<?php
session_start();

require_once 'config.php';

try {
    $db = new DBConnection();
} catch (\Throwable $th) {
    header("Location: error.php");
    exit();
}
 
if (isset($_POST['submit']) && isset($_SESSION['userID'])) {
    $id = $_SESSION['user'];
    $password = hash('sha256', $_POST['password']); // Hash the input password using SHA-256
    $cpassword = hash('sha256', $_POST['cpassword']); // Hash the input confirm password using SHA-256
    if ($password == $cpassword) {
        $result = $db->resetPassword($id, $password, $cpassword);
        echo "<script>alert('Congrats, your password has changed successfully!')</script>";
        $_POST['password'] = "";
        $_POST['cpassword'] = "";
        header("Location: account.php");
        exit();
        
        
    } else {
        echo "<script>alert('Password does not match')</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<?php include_once("header.php"); ?>
<body>
<?php include_once("nav.php"); ?>
<div class="container my-5 mx-6">
    <?php
    if (isset($_SESSION['userID'])) {
        ?>
        <form class="row g-3 w-50" action="" method="post" style="margin-left: 270px;">
            <div class="col-md-12">
                <label for="validationServer05" class="form-label">Password</label>
                <input type="password" name="password" class="form-control " id="validationServer05" aria-describedby="validationServer03Feedback" required>
            </div>
            <div class="col-md-12">
                <label for="validationServer06" class="form-label">Retype Password</label>
                <input type="password" name="cpassword" class="form-control" id="validationServer06" aria-describedby="validationServer03Feedback" required>
            </div>`
            <div class="col-12">
                <button class="btn btn-primary" name="submit" type="submit">Submit form</button>
            </div>
        </form>
    <?php
    } else {
    ?>
        <div class="col-md-12">
            <label for="validationServer04" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="validationServer04" aria-describedby="validationServer03Feedback" required>
        </div>
        <div class="col-12">
            <button class="btn btn-primary" name="submit" type="submit">Submit form</button>
        </div>
    <?php
    }
    ?>
</div>
    <?php include_once("footer.php")?>
</body>
</html>