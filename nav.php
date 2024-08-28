
<nav class="navbar bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand ms-3" href="view.php">
      Honya.com
    </a>
    <form class="d-flex" role="search" action="listItem.php" method="post" style="width: 60%;">
      <input class="form-control me-3" name="search" list="datalistOptions1" id="exampleDataList" placeholder="Type to search...">
      <datalist id="datalistOptions1">
      <?php
      $listBooks = $db->getAllBooks();
      foreach($listBooks as $list){
        ?>
          <option value="<?=$list['title']?>">

        <?php
      }
      ?>
      </datalist>
      <button class="btn" name="searchSubmit" id="search" type="submit">
          Search
      </button>
    </form>

    <?php
    if (isset($_SESSION['userID'])) {
    ?>
    <a class="navbar-brand ms-3" href="cart.php">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
        <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
      </svg>
    </a>
    <a class="navbar-brand ms-3" href="account.php">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
      </svg>
    </a>

    <?php
    } else {
    ?>
    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#exampleModal" style="border:none;">
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
          <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
        </svg>
      </div>
    </button>

    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#exampleModal" style="border:none;">
      <div>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
          </svg> login
      </div>
    </button>
    <?php
    }
    ?>    

  </div>
</nav>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel"><span id="modal-title">Log In</span></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="login">
          <form class="row g-3" action="view.php" method="post">
            <div class="col-md-12">
              <label for="validationServer04" class="form-label">Email</label>
              <input type="email" name="email" class="form-control" id="validationServer04" aria-describedby="validationServer03Feedback" required>
            </div>
            <div class="col-md-12">
              <label for="validationServer05" class="form-label">Password</label>
              <input type="password" name="password" class="form-control " id="validationServer05" aria-describedby="validationServer03Feedback" required>
            </div>
            <div class="col-12">
              <button class="btn btn-primary" name="login" type="submit">Login</button>
            </div>
          </form>
          <div class="grid float-end" style="">
            <div class="col">
                <a href="create.php">create account</a> 
              </div>
            <div class="col">
                <a href="reset.php">reset password</a>
              </div>
                </div>
          </div>                    
          </div>
        </div>
      </div>
</div>
</div>
