<!doctype html>
<html class="no-js" lang="" xmlns="http://www.w3.org/1999/html">

<head> 
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Add Employee</title>
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
      include 'header_department.php';
      ?>
    <section class="pageContent container h-75">
      
      <section class="pageTitle row">
        <div class="col-md-12">
          <h3 class="text-capitalize">Add Employee</h3>
        </div>
      </section><!-- Page Title-->

      <section class="blockSection d-flex align-items-center h-100">
        <div class="d-flex align-items card boxShadow col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-4 offset-lg-4 col-xl-4 offset-xl-4">
            <div class="card-body">
                <div class="profileImage">
                    <img src="content/img/employee-profile.png" id = "uploadImage" style="height: 100px;width: 100px;" class="rounded-circle" alt="" />
                    
                    <a href="javascript:void(0)" class="profileButton" onclick="document.getElementById('fileUpload').click()" accept="image/*"></a>
                </div>
                <form class="mt-4 align-items-center" enctype="multipart/form-data" action="#" method="POST">
                <input hidden="hidden" type="file" onchange="readURL(this);" name="pic" id="fileUpload" accept="image/*">
                <div class="form-group">
                      <label for="employeeId">Employee ID</label>
                    <input class="form-control round" type="text"  maxlength="6" id="employeeId" name="employeeId" placeholder="1001" required pattern="[0-9]+"  value="<?php echo (isset($_POST['employeeId'])? $_POST['employeeId'] : ''); ?>">
                  </div>
                  <div class="form-group">
                      <label for="employeeName">Employee Name</label>
                    <input class="form-control round" type="text"  maxlength="20" id="employeeName" name="employeeName" placeholder="John Smith" required pattern="[A-Za-z ]+"  value="<?php echo (isset($_POST['employeeName'])? $_POST['employeeName'] : ''); ?>">
                  </div>
                  <div class="form-group">
                      <label for="QatarId">Qatar ID</label>
                    <input class="form-control round" type="text" maxlength="11" id="qid" name="QatarId" placeholder="11900" required pattern="[0-9]+"  value="<?php echo (isset($_POST['QatarId'])? $_POST['QatarId'] : ''); ?>">
                  </div>
                 <!-- 
                     list of departments from database
                  -->
                  <div class="form-group">
                      <label for="departmentName">Department</label>
                      <input class="form-control round" required type="text" id="dept" name="dept" placeholder="" value="<?php echo $dname; ?>" readonly>
                  </div>
                  <div class="form-group text-center">
                      <button class="btn btn-primary px-5 border-0 roundBtn" data-toggle="modal"
                              type="sumbit" name="submit">ADD</button>
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
$id=$_POST['employeeId'];
$name = $_POST['employeeName'];
$mImage = "img/"."emp_".$id.$_FILES['pic']['name'] ;
    $mImage=str_replace(' ','',$mImage);
$img='No Preview';

$qid=addslashes($_POST['QatarId']);

$query1 = mysqli_query($conn, "SELECT eid FROM employee where eid='$id'") or die(mysqli_error($conn));
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
                Employee ID Already exists.
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
else {
    if (move_uploaded_file($_FILES['pic']['tmp_name'], $mImage)) {
        $query =
            "Insert into employee(eid,ename,eqid,eimage,deptno) values ('$id','$name','$qid','$mImage','$dept')";
        if (!mysqli_query($conn, $query)) {

            $myfile = fopen("logs.txt", "a") or die("Unable to open file!");
            $txt =  mysqli_error($conn);
            fwrite($myfile, "\n". $txt);
            fclose($myfile);

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
            echo '<div class="modal" id="myModalsu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Added</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                 Employee Added Successfully
            </div>
            <div class="modal-footer">
                <form action = "all-employee-list-t.php" method = "get">

                    <input type="submit" class="btn btn-success roundBtn"   value = "OK"/>
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
                You must add Image
            </div>
            <div class="modal-footer">
                <form action = "all-employee-list-t.php" method = "get">

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
}
}
?>
  <footer class="pageFooter">
      <p>Design and Develop by Zoom Arts & Design</p>
  </footer> <!-- Page Footer -->
</header>
    <!-- SCRIPTS -->
  <script src="content/js/popper.min.js"></script>
  <script src="content/js/plugins.js"></script>
  <script src="content/js/classie.js"></script>
  <script src="content/js/mlpushmenu.js"></script>
  <!-- <script src="content/js/datepicker.js"></script> -->
  <script src="content/js/jquery/jquery-ui.js"></script>
  <script src="content/js/main.js"></script>
</body>
</html>
