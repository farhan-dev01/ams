<!doctype html>
<html class="no-js" lang="">

<head> 
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Add User</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- STYLESHEETS -->
  <link rel="stylesheet" href="content/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="content/css/icons.css" />
  <link rel="stylesheet" href="content/css/normalize.css">
  <link rel="stylesheet" href="content/css/style.css">
    <link rel="stylesheet" href="content/font-awesome/css/font-awesome.css">

  <!-- SCRIPTS -->
  <script src="content/js/vendor/modernizr-3.6.0.min.js"></script>
  <script src="content/js/jquery-slim.min.js"></script>
  <script src="content/js/modernizr.custom.js"></script>
    <script src="content/js/bootstrap.min.js"></script>
  <script>
  function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#uploadImage').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

  </script>
    <style>
        .field-icon {
            float: right;
            margin-left: -25px;
            margin-top: -25px;
            position: relative;
            z-index: 2;
            opacity: 0.7;
        }
    </style>
</head>

<body class="mp-pusher" id="mp-pusher">
  <!--[if lte IE 9]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
  <![endif]-->
  <header>
      <?php
      include 'header_hr.php';
      ?>

    <section class="pageContent container h-75">
      
      <section class="pageTitle row">
        <div class="col-md-12">
          <h3 class="text-capitalize">Add Department Head</h3>
        </div>
      </section><!-- Page Title-->

      <section class="blockSection d-flex align-items-center h-100">
        <div class="d-flex align-items card boxShadow col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-4 offset-lg-4 col-xl-4 offset-xl-4">
            <div class="card-body" >
                <div class="profileImage">
                    <img src="content/img/employee-profile.png" id = "uploadImage" style="height: 100px;width: 100px;" class="rounded-circle" alt="" />
                    
                    <a href="javascript:void(0)" class="profileButton" onclick="document.getElementById('fileUpload').click()" accept="image/*"></a>
                </div>
                <form class="mt-4 align-items-center" enctype="multipart/form-data" action="#" method="POST" >
                <input hidden="hidden" type="file" onchange="readURL(this);" name="pic" id="fileUpload" accept="image/*">
                  <div class="form-group">
                      <label for="userName">Name</label>
                    <input class="form-control round" type="text" id="name" name="name" placeholder="John Smith" maxlength="20" required pattern="[A-Za-z ]+"  value="<?php echo (isset($_POST['name'])? $_POST['name'] : ''); ?>">
                  </div>
                  <div class="form-group">
                      <label for="userName">Username</label>
                    <input class="form-control round" type="text" id="userName" name="userName" placeholder="smith007" maxlength="10" required  value="<?php echo (isset($_POST['userName'])? $_POST['userName'] : ''); ?>">
                  </div>
                  <div class="form-group">
                      <label for="QatarId">Password</label>
                    <input class="form-control round" type="password" id="password" name="password" maxlength="10" placeholder="password" required  value="<?php echo (isset($_POST['password'])? $_POST['password'] : ''); ?>">
                      <span onclick="$(this).toggleClass('fa-eye fa-eye-slash');
                            var input = $($(this).attr('toggle'));
                            if (input.attr('type') == 'password') {
                            input.attr('type', 'text');
                            } else {
                            input.attr('type', 'password');
                            }" toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                  </div>
                  <div class="form-group">
                      <label for="userName">Email</label>
                    <input class="form-control round" type="email" id="email" name="email" placeholder="example@example.com" required  value="<?php echo (isset($_POST['email'])? $_POST['email'] : ''); ?>">
                  </div>
                  <div class="form-group">
                      <label for="userType">Department</label>
                  <select name="deptno">
                      <option value=""></option>
                  <?php
                   require_once 'db_connection.php';
                   $conn=opencon();
                  $query = mysqli_query($conn, "SELECT * FROM department where uid2 is null ") or die(mysqli_error($conn));
               while ($result=mysqli_fetch_array($query))
                  {?>
                  <option value="<?= $result['deptno']; ?>"><?php echo $result['dname']; ?></option>
                  <?php
                  }
                  ?>
                  </select>
                  </div>
                  <div class="form-group text-center">
                      <button class="btn btn-primary px-5 border-0 roundBtn" type="sumbit" name="submit">ADD</button>
                  </div>
                </form>
            </div>
        </div>
      </section>

    </section>
    
  </div><!-- Page Wrapper Ends -->
  <?php
require_once 'db_connection.php';
$conn = opencon();
if(isset($_POST['submit']))
{
//$id=$_POST['userId'];
$username = $_POST['userName'];
$name = $_POST['name'];
$password = $_POST['password'];
$email = $_POST['email'];
$deptno = $_POST['deptno'];
$mImage = "img/"."emp_".$username.$_FILES['pic']['name'] ;
    $mImage=str_replace(' ','',$mImage);
$img='No Preview';

$query1 = mysqli_query($conn, "SELECT username FROM teamhead_user where username='$username'") or die(mysqli_error($conn));
$row=$query1->fetch_array();
$count = $query1->num_rows;
if($count==1)
{
    echo  '<div class="modal" id="myModalsu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Warning</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               username name already exists
            </div>
            <div class="modal-footer">
                <form action = "#" method = "get">

                    <input type="button" class="btn btn-danger roundBtn"  data-dismiss="modal" value = "OK"/>
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
else
{
    if (move_uploaded_file($_FILES['pic']['tmp_name'], $mImage)){
        if($deptno==null)
        {
            $query="Insert into teamhead_user(name,username,password,email,uimage) values ('$name','$username','$password','$email','$mImage')";
        }
        else {
            $query = "Insert into teamhead_user(name,username,password,email,uimage,deptno) values ('$name','$username','$password','$email','$mImage','$deptno')";
        }
if(!mysqli_query($conn,$query))
{
	echo "Error";
}
else
{
    $type='teamhead';
    $query2="insert into login(username,password,type) values ('$username','$password','$type')";
    if(!mysqli_query($conn,$query2))
{
    echo  '<div class="modal" id="myModalsu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Error</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                Something went wrong, please try again later
            </div>
            <div class="modal-footer">
                <form action = "all-employee-list.php" method = "get">

                    <input type="button" class="btn btn-danger roundBtn"  data-dismiss="modal" value = "OK"/>
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
else
{
    if($deptno!=null)
    {
        require_once 'db_connection.php';
        $conn = opencon();
        $query2 = mysqli_query($conn, "select uid from teamhead_user where deptno ='$deptno'");
        $row2 = $query2->fetch_array();
        $uid = $row2['uid'];
        $query3 = mysqli_query($conn, "update department set uid2='$uid' where deptno='$deptno'");
    }
    echo  '<div class="modal" id="myModalsu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Created</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                Account Created Successfully
            </div>
            <div class="modal-footer">
                <form action = "users_teamhead.php" method = "get">

                    <input type="submit" class="btn btn-success roundBtn"  value = "OK"/>
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
}}
else
{
    echo  '<div class="modal" id="myModalsu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Warning</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                You must select the image
            </div>
            <div class="modal-footer">
                <form action = "all-employee-list.php" method = "get">

                    <input type="button" class="btn btn-danger roundBtn"  data-dismiss="modal" value = "OK"/>
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
}}}
?>
  <footer class="pageFooter">
      <p>Design and Develop by Zoom Arts & Design</p>
  </footer> <!-- Page Footer -->
  </header>
    <!-- SCRIPTS -->
    <script src="content/js/popper.min.js"></script>
    <script src="content/js/bootstrap.min.js"></script>
    <script src="content/js/plugins.js"></script>
    <script src="content/js/classie.js"></script>
    <script src="content/js/mlpushmenu.js"></script>
    <script src="content/js/datepicker.js"></script>
    <script src="content/js/main.js"></script>
</body>
</html>
