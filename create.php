<?php
session_start();

function generateID() {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < 15; $i++) {
          $randomString .= $characters[random_int(0, $charactersLength - 1)];
      }
      return $randomString;
    }

require_once 'config.php';

try {
    $db = new DBConnection();
} catch (\Throwable $th) {
    header("Location: error.php");
    exit();
}
 
if (isset($_POST['submit'])) {
    $id = "";
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = hash('sha256', $_POST['password']); // Hash the input password using SHA-256
    $cpassword = hash('sha256', $_POST['cpassword']); // Hash the input confirm password using SHA-256
    if ($password == $cpassword) {
        $id = generateID();
        $n = $db->validateEmail($email);
        if ($n) {
            $result = $db->createAccount($id, $fname, $lname, $email, $password);
            echo "<script>alert('Congrats, your account is successfully created!')</script>";
            $_SESSION["userID"]= $id;
            $_SESSION["userEmail"]= $email;
            $id = "";
            $fname = "";
            $lname = "";
            $email = "";
            $_POST['password'] = "";
            $_POST['cpassword'] = "";
            header("Location: account.php");
            exit();
            
        } else {
            echo "<script>alert('Woops! Email has been registered.')</script>";
        }
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
<form class="row g-3 w-50" action="" method="post" style="margin-left: 270px;">
  <div class="col-md-6">
    <label for="validationServer01" class="form-label">First name</label>
    <input type="text" name="fname" class="form-control" id="validationServer01"  required>
  </div>
  <div class="col-md-6">
    <label for="validationServer02" class="form-label">Last name</label>
    <input type="text" name="lname" class="form-control" id="validationServer02"  required>
  </div>
  <div class="col-md-12">
    <label for="validationServer04" class="form-label">Email</label>
    <input type="email" name="email" class="form-control" id="validationServer04" aria-describedby="validationServer03Feedback" required>
  </div>
  <div class="col-md-12">
    <label for="validationServer05" class="form-label">Password</label>
    <input type="password" name="password" class="form-control " id="validationServer05" aria-describedby="validationServer03Feedback" required>
  </div>
  <div class="col-md-12">
    <label for="validationServer06" class="form-label">Retype Password</label>
    <input type="password" name="cpassword" class="form-control" id="validationServer06" aria-describedby="validationServer03Feedback" required>
  </div>
  
  <div class="col-12">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" value="" id="invalidCheck3" aria-describedby="invalidCheck3Feedback" required>
      <label class="form-check-label" for="invalidCheck3">
        Agree to terms and conditions
      </label>
    </div>
  </div>
  <div class="col-12">
    <button class="btn btn-primary" name="submit" type="submit">Submit form</button>
  </div>
</form>
</div>

    <?php include_once("footer.php")?>
</body>
</html>