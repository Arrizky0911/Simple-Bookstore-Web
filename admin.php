<?php
session_start();
include 'config.php';

try {
    $db = new DBConnection();
} catch (\Throwable $th) {
    header("Location: error.php");
    exit();
}
if (isset($_POST['loginAdmin'])) {
    session_unset();
    $username = $_POST['username'];
    $password = $_POST['password']; 
 
    $result = $db->loginAdmin($username, $password);
    $row = $result->fetch();
    if (isset($row['username'])) {
        $_SESSION['admin'] = $row['username'];
        header("location: admin/bookList.php");
        exit();
    } else {
        echo "<script>alert('Your username or Password is wrong. Please try again!')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
include_once 'header.php';?>
<body>
    <div class="container-fluid w-50 text-center">
        <form class="row g-3" action="" method="post">
            <div class="col-md-12">
              <label for="validationServer04" class="form-label">Username</label>
              <input type="text" name="username" class="form-control" id="validationServer04" aria-describedby="validationServer03Feedback" required>
            </div>
            <div class="col-md-12">
              <label for="validationServer05" class="form-label">Password</label>
              <input type="password" name="password" class="form-control " id="validationServer05" aria-describedby="validationServer03Feedback" required>
            </div>
            <div class="col-12">
              <button class="btn btn-primary" name="loginAdmin" type="submit">Login</button>
            </div>
        </form>
    </div>
</body>
</html>