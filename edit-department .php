<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Edit Department</title>
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
                <div class="col-md-10">
                    <h3 class="d-inline">Attendance Book</h3>
                    <!-- <span>DEPARTMENT</span> -->
                </div><!-- md12 -->
            </section><!-- Page Title-->
          <section class="blockSection d-flex align-items-center h-100">
              <div class="d-flex align-items card boxShadow col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-4 offset-lg-4 col-xl-4 offset-xl-4">
                  <div class="card-body">
                      <div class="profileImage">
                          <img src="content/img/add-department.png" class="rounded mx-auto d-block" alt="" />
                          <a href="javascript:void(0)" class="profileButton"></a>
                      </div>
                      <form class="mt-4 align-items-center" method="POST" action="#">
                      <?php
                  require_once 'db_connection.php';
                  $conn=opencon();

                if(isset($_GET['id']))
                {
                  $ID = $_GET['id'];
                    $query0 = mysqli_query($conn, "select deptno from department where deptno ='$ID'");
                    $row=$query0->fetch_array();
                    $dpt=$row['deptno'];
	 $query = mysqli_query($conn, "SELECT department.*,teamhead_user.* FROM department left outer join teamhead_user on department.deptno=teamhead_user.deptno where department.deptno=$ID") or die(mysqli_error($conn));
 while ($result=mysqli_fetch_array($query))
    {
        $chdname=$result['dname'];
        $olduid=$result['uid'];

        ?>

    
                          <div class="form-group">
                              <label for="departmentName">Department Name</label>
                            <input class="form-control round" required type="text" id="departmentName" name="departmentName" placeholder="" value="<?php echo $result['dname'] ?>" pattern="[a-zA-Z ]+">
                            <input  type="hidden" id="departmentName" name="deptno" maxlength="20" placeholder="" value="<?php echo $result['deptno'] ?>">
                          </div>
        <div class="form-group">
            <label for="userType">Department Head</label>
            <select name="head">
                <option value="<?= $result['uid']; ?>"><?php echo $result['name']; ?></option>
                <?php
                require_once 'db_connection.php';
                $conn=opencon();
                $query = mysqli_query($conn, "SELECT * FROM teamhead_user where deptno is null ") or die(mysqli_error($conn));
                while ($row=mysqli_fetch_array($query))
                {?>
                    <option value="<?= $row['uid']; ?>"><?php echo $row['name']; ?></option>
                    <?php
                }
                ?>
            </select></div>
                          <div class="form-group text-center">
                              <button class="btn btn-primary roundBtn" type="submit" name="submit">UPDATE</button>
                          </div>
                          <?php }}?>
                        </form>
                  </div>
              </div>
            </section>
    </section>
  </div><!-- Page Wrapper Ends -->
  <?php
require_once 'db_connection.php';
$conn = opencon();
if(isset($_POST['submit'])) //submit
{
    $did=$_POST['deptno'];
$dname = $_POST['departmentName'];
$dhead= $_POST['head'];
if($dname!=$chdname)//dname change
{
$query1 = mysqli_query($conn, "SELECT dname FROM department where dname='$dname'") or die(mysqli_error($conn));
$row=$query1->fetch_array();
$count = $query1->num_rows;
if($count==1)//dname matches
{
    echo  '<div class="modal" id="myModalsu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Warning</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                Department name already exists
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
else goto next;
}
else
{next:
    if($dhead==null)
    {
        $query = "update department set dname='$dname' where deptno='$dpt'";
    }
    else {
        $query = "update department set dname='$dname',uid2='$dhead' where deptno='$dpt'";
    }
    if(!mysqli_query($conn,$query))
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
    if($dhead!=null) {
        require_once 'db_connection.php';
        $conn = opencon();
        $query4 = mysqli_query($conn, "update teamhead_user set deptno=null where uid='$olduid'");
        $query2 = mysqli_query($conn, "select deptno from department where uid2 ='$dhead'");
        $row2 = $query2->fetch_array();
        $dept = $row2['deptno'];
        $query3 = mysqli_query($conn, "update teamhead_user set deptno='$dept' where uid='$dhead'");
    }
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
                <form action = "all-departments.php" method = "get">

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
}//dname matches
}//submit
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
