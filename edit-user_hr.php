<!doctype html>
<html class="no-js" lang="">

<head> 
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Edit User</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- STYLESHEETS -->
  <link rel="stylesheet" href="content/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="content/css/icons.css" />
  <link rel="stylesheet" href="content/css/normalize.css">
  <link rel="stylesheet" href="content/css/style.css">

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
          <h3 class="text-capitalize">Edit User</h3>
        </div>
      </section><!-- Page Title-->
      <!-- FOR GETTING DETAILS OF SELECTED EMPLOYEE-->
      <?php
                  require_once 'db_connection.php';
                  $conn=opencon();
                  
                if(isset($_GET['id']))
                {
                  $ID = $_GET['id'];
    
	 $query = mysqli_query($conn, "SELECT * FROM hr_user where hid=$ID") or die(mysqli_error($conn));
 while ($result=mysqli_fetch_array($query))
    {
        $chname=$result['username'];
        $img=$result['image'];
                
                ?>
      <section class="blockSection d-flex align-items-center h-100">
        <div class="d-flex align-items card boxShadow col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-4 offset-lg-4 col-xl-4 offset-xl-4">
            <div class="card-body">
            <div class="profileImage">
                    <img src="<?php echo $img ?>" id = "uploadImage" style="height: 100px;width: 100px;" class="rounded-circle" alt="" />
                    
                    <a href="javascript:void(0)" class="profileButton" onclick="document.getElementById('fileUpload').click()" accept="image/*"></a>
                </div>
                <form class="mt-4 align-items-center" enctype="multipart/form-data" action="#" method="POST">
                <input hidden="hidden" type="file" onchange="readURL(this);" name="pic" id="fileUpload" accept="image/*">
                  <div class="form-group">
                      <label for="userName">Name</label>
                    <input class="form-control round" required type="text" id="name" name="name" maxlength="20" placeholder="" value="<?php echo $result['name'] ?>" pattern="[a-zA-Z ]+">
                  </div>
                  <div class="form-group">
                      <label for="userName">Username</label>
                    <input class="form-control round" required type="text" id="userName" name="userName" maxlength="10" placeholder="" value="<?php echo $result['username'] ?>">
                  </div>
                  <div class="form-group">
                      <label for="userPassword">Password</label>
                    <input class="form-control round" required type="text" id="password" name="password" maxlength="10" placeholder="" value="<?php echo $result['password'] ?>">
                  </div>
                  <div class="form-group">
                      <label for="userPassword">Email</label>
                    <input class="form-control round" required type="email" id="email" name="email" placeholder="" value="<?php echo $result['email'] ?>">
                  </div>
                
     <!-- FOR GETTING DETAILS OF SELECTED EMPLOYEE-->
                 <!-- 
                     list of departments from database
                  -->
                  <div class="form-group text-center">
                      <input type="hidden" id="employeeId" name="userID" placeholder="" value="<?php echo $result['hid'] ?>">
                      <button class="btn btn-primary px-5 border-0 roundBtn" type="sumbit" name="submit">UPDATE </button>
                  </div>
                </form>
            </div>
        </div>
      </section>
    </section>
    <?php }}?>
  </div><!-- Page Wrapper Ends -->
  <?php
  require_once 'db_connection.php';
  $conn = opencon();
if(isset($_POST['submit']))
{
    
$id=$_POST['userID'];
$uname = $_POST['userName'];
$name = $_POST['name'];
$password = $_POST['password'];
$email = $_POST['email'];
//$dept = $_POST['deptno'];
$image = "img/"."emp_".$id.$_FILES['pic']['name'] ;
    $image=str_replace(' ','',$image);

    if($chname!=$uname)
    {
        $query1 = mysqli_query($conn, "SELECT username FROM hr_user where username='$uname'") or die(mysqli_error($conn));
        $row=$query1->fetch_array();
        $count = $query1->num_rows;
        if($count==1) {
            echo '<div class="modal" id="myModalsu">
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
        }  else goto next;     }
    else
    {next:
if (move_uploaded_file($_FILES['pic']['tmp_name'], $image)) 
{
    $query="update hr_user set username='$uname', name='$name',password='$password',email='$email',image='$image' where hid='$id'";
    if(!mysqli_query($conn,$query))
    {
        echo "Error";
    }
    else
    {
        //echo "Data Inserted";
        $query2="update login set username='$uname',password='$password' where username='$chname'";
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
    echo  '<div class="modal" id="myModalsu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Updated</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                Information is updated successfully
            </div>
            <div class="modal-footer">
                <form action = "users_hr.php" method = "get">

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
    }
}
else
{
    $query="update hr_user set  username='$uname',name='$name',password='$password',email='$email',image='$img' where hid='$id'";
    if(!mysqli_query($conn,$query))
    {
        echo "Error";
    }
    else
    {
        $query2="update login set username='$uname',password='$password' where username='$chname'";
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
    echo  '<div class="modal" id="myModalsu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Updated</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                Information is updated successfully
            </div>
            <div class="modal-footer">
                <form action = "users_hr.php" method = "get">

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
}     // header("Location: all-employee-list.php");
    }
}
}
}
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
