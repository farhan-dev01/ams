<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Attendance System</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- STYLESHEETS -->
  <link rel="stylesheet" href="content/css/bootstrap.min.css">
  <link rel="stylesheet" href="content/css/normalize.css">
  <link rel="stylesheet" href="content/css/style.css">

  <!-- SCRIPTS -->
  <script src="content/js/vendor/modernizr-3.6.0.min.js"></script>
  <script src="content/js/jquery-slim.min.js"></script>

</head>

<body>
  <!--[if lte IE 9]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
  <![endif]-->
  <div class="pageWrapper container-fluid h-100">
    <header class="">
      <a href="login.php" class="logoLoginPage"><img src="content/img/zoom.png" class="img-responsive" alt="" /></a>
    </header>
    <section class="pageContent row justify-content-center h-100">
      <div class="leftSection col">
          <div class="d-flex align-items-center h-100">
              <form class="col-sm-12 col-md-10 offset-md-1 col-lg-10 offset-lg-1 col-xl-6 offset-xl-3 align-items-center" action="" method="POST">
                  <h3>Login</h3>
                <div class="form-group">
                    <input class="form-control bg-secondary light border-0" type="text" name="idd" id="username" placeholder="Username">
                </div>
                <div class="form-group">
                  <input class="form-control bg-secondary light border-0" type="password" name="psw" id="password" placeholder="Password">
                </div>
                <div class="form-group">
                    <button class="btn btn-primary px-5 border-0 text-uppercase" id="submitBtn" name="login" type="submit">Login</button>
                </div>
              </form>
          </div>
          <p class="homeFooter">Design and Develop By Zoom Arts IT</p>
      </div><!-- Left Section -->
      <div class="rightSection col">

      </div><!-- Right Section -->
    </section>
  </div><!-- Page Wrapper Ends -->
  <!-- <footer class="pageFooter">
  </footer>  Page Footer -->
  <!-- SCRIPTS -->
  <script src="content/js/popper.min.js"></script>
  <script src="content/js/bootstrap.min.js"></script>
  <script src="content/js/plugins.js"></script>
  <script src="content/js/main.js"></script>

  <?php

  require_once 'db_connection.php';
  $conn=opencon();
  session_start();
  if(isset($_SESSION['login_id']))
  {
      header("Location:attendance-book-hr.php");
      exit();
  }
  if(isset($_SESSION['login_id2']))
  {
      header("Location:attendance-book-department.php");
      exit();
  }

  if (isset($_POST['login'])) {

      $admin_name = strip_tags($_POST['idd']);
      $admin_pass = strip_tags($_POST['psw']);


      $query = $conn->query("SELECT * FROM login WHERE username='$admin_name'") or die(mysqli_error());
      $row=$query->fetch_array();
      $count = $query->num_rows; // if email/password are correct returns must be 1 row



      if ($admin_pass == $row['password'] && $count==1) {

          if( $row['type']=='teamhead')
          {
              $query2 = $conn->query("SELECT deptno FROM teamhead_user WHERE username='$admin_name'") or die(mysqli_error());
              $row2=$query2->fetch_array();
              if($row2['deptno']==null)
              {
                  echo  '<div class="modal" id="myModalsu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Warning !</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                You have no access yet
            </div>
            <div class="modal-footer">
                <form action = "index.php" method = "get">

                    <input type="submit" class="btn btn-danger roundBtn"  value = "OK"/>
                </form>
            </div>
        </div>
    </div>
</div>
            <script>
 $(\'#myModalsu\').modal({
    backdrop: \'static\',
    keyboard: false
})
                $("#myModalsu").modal("show");
            </script>';
                  die(0);

              }
              else
              {
              $_SESSION['login_id2'] = $row['username'];
              $_SESSION['LAST_ACTIVITY'] = time();
              header("Location:attendance-book-department.php");
          }}
          else
          {
              $_SESSION['login_id'] = $row['username'];
              $_SESSION['LAST_ACTIVITY'] = time();
              header("Location:attendance-book-hr.php");
          }
      }
      else
      {
          echo  '<div class="modal" id="myModalsu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Warning !</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                Invalid username/password !!
            </div>
            <div class="modal-footer">
                <form action = "index.php" method = "get">

                    <input type="submit" class="btn btn-danger roundBtn"  value = "OK"/>
                </form>
            </div>
        </div>
    </div>
</div>
            <script>
 $(\'#myModalsu\').modal({
    backdrop: \'static\',
    keyboard: false
})
                $("#myModalsu").modal("show");
            </script>';
      }

  }

  ?>
</body>
</html>
