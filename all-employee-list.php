<!doctype html>
<?php
require_once 'db_connection.php';
$conn=opencon();
$per_page = 10;
$pages_query = mysqli_query($conn,"select count('eid') from employee");
$records = mysqli_fetch_array($pages_query);
$records = $records[0];
$pages = ceil($records / $per_page);
$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;
$sno = $start + 1;

   ?>

<html class="no-js" lang="">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Attendance System | Attendance Book</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- STYLESHEETS -->
  <link rel="stylesheet" href="content/js/jquery/jquery-ui.css">
  <link rel="stylesheet" href="content/js/jquery/jquery-ui.theme.css">
  <link rel="stylesheet" href="content/css/normalize.css">
  <link rel="stylesheet" type="text/css" href="content/css/icons.css" />
  <link rel="stylesheet" href="content/css/bootstrap.min.css">
  <link rel="stylesheet" href="content/css/style.css">

  <!-- SCRIPTS -->
  <script src="content/js/jquery-slim.min.js"></script>
  <script src="content/js/vendor/modernizr-3.6.0.min.js"></script>
  <script src="content/js/modernizr.custom.js"></script>
  <script src="content/js/jquery/jquery.js"></script>

  <style>
      #pic {

  width:60px;
  height:60px;
  }
  </style>
</head>

<body class="mp-pusher" id="mp-pusher">
<!--[if lte IE 9]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
<![endif]-->
<header>
<?php 
 require_once 'header_hr.php';
  ?>
        <section class="pageContent">
    

        <div class="container h-75">
        <section class="pageTitle row">
            <div class="col-md-10">
                <h3>All Employee</h3>
            </div><!-- md12 -->
            <div class="col-md-2">
                <a href="javascript:void(0)" onclick="window.location.href='add-employee.php'" class="btn btn-primary roundBtn float-right">+ ADD EMPLOYEE</a>
            </div>
        </section><!-- Page Title-->

        <section class="blockSection ">
            <div class="allEmployeeList">
               
                <ul class="row list clearfix d-block m-0 p-0 ">
                    <li class="tex-center row align-items-center justify-content-center">
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xl-1 align-self-center text-left">S.NO</div>
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xl-1 align-self-center text-left"></div>
                        <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 align-self-center text-left">EMPLOYEE NAME</div>
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xl-1 align-self-center text-left">EMP ID</div>
                        <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 align-self-center text-left">QID</div>
                        <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 align-self-center text-left">DEPARTMENT</div>
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xl-1 align-self-center text-center">EDIT</div>
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xl-1 align-self-center text-center">DELETE</div>
                    </li>
                    <?php
 
 require_once 'db_connection.php';
 $conn=opencon();
 {
    
	 $query = mysqli_query($conn, "SELECT employee.*,department.dname FROM employee join department on employee.deptno=department.deptno order by employee.eid limit $start,$per_page") or die(mysqli_error($conn));
 while ($result=mysqli_fetch_array($query))
    {
        ?>
        <form>
                    <li class="tex-center row align-items-center justify-content-center bg-white round boxShadow">
                        <div class="col-sm-1 col-md-2 col-lg-1 col-xl-1 align-self-center text-left"><?php echo $sno?></div>
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xl-1 align-self-center text-left thumbnail"><img id ="pic" src=<?php echo $result['eimage'] ?> class="rounded-circle" alt="" /></div>
                        <div class="col-sm-3 col-md-2 col-lg-1 col-xl-3 align-self-center text-left holidayName "><label><?php echo $result['ename'] ?></label></div>
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xl-1 align-self-center text-left"><?php echo $result['eid'] ?></div>
                        <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 align-self-center text-left"><?php echo $result['eqid'] ?></div>
                        <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 align-self-center text-left" ><?php echo $result['dname'] ?></div>
                        <a class="col-sm-1 col-md-1 col-lg-1 col-xl-1 align-self-center text-center" href="edit-employee.php?id=<?php echo $result['eid'] ?>;"><img src="content/img/edit.png" class="" alt="" /></a>
                        <a class="col-sm-1 col-md-1 col-lg-1 col-xl-1 align-self-center text-center" onclick = "document.getElementById('sid').value =  <?php echo $result['eid'] ?>;" 
                         data-toggle="modal" data-target="#myModal" href="" >
                        <img src="content/img/delete.png" class="" alt="" name="btn"/></a>
                    </li>
                    </form>
    <?php 
$sno++;    
}}
   
    ?>
                </ul>   
            </div><!-- ALL EMPLOYEE LIST WRAP ENDS -->

            <div class="pagination d-block text-center">

                    <?php
                    if ($pages >= 1 && $page <= $pages) {

                        if ($page != 1) {
                            ?>
<!--                             <a class="active" href="?page=--><?php //echo $page - 1 ?><!--"> </a>-->
                             <?php
                        }
                        for ($x = $page - 5; $x <= $page + 5; $x++) {
                            if ($x > $pages || $x < 1) {
                            } else {
                                echo ($x == $page) ? ' <a class=active href="?page=' . $x . '">' . $x . '</a>' : '  <a class="link" href="?page=' . $x . '">' . $x . '</a> ';
                            }
                        }
                        if ($page != $pages) {
                            ?>
<!--                            <a class="active" href="?page=--><?php //echo $page + 1 ?><!--"> </a> --><?php
                        }
                    } else {
                        echo '<div class="form-control">There is no Record in this Page | please go back....!!</div>';
                    }
                    ?>

            </div>
        </section>
    </div><!-- Container -->
    </section><!-- pageContent -->

    <!-- MODAL -->
    <div class="modal" id="myModal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Confirm Delete</h5>
              <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              Are you Sure you want to Delete? 
            </div>
            <div class="modal-footer">
            <form action = "" method = "get">
            <input type="hidden" value="" id = "sid" name = "id"/>
            
              <input type="submit" class="btn btn-danger roundBtn"   value = "Yes"/>
              <input type="button" data-dismiss="modal" class="btn btn-success roundBtn"  value = "No"/>
              </form>
            </div>
          </div>
        </div>
      </div>
  </div><!-- Page Wrapper Ends -->
 
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
   <!-- <script src="content/js/datepicker.js"></script> -->
   <script src="content/js/jquery/jquery-ui.js"></script>
   <script src="content/js/main.js"></script>
<?php


if(isset($_GET['id']))
{
    require_once 'db_connection.php';
    $conn = opencon();
    $ID = $_GET['id'];

    {
        $sql = "DELETE FROM employee WHERE eid='$ID'";
        if (!mysqli_query($conn, $sql)) {
            echo 'error';
        } else {
            echo  '<div class="modal" id="myModalsu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Deleted</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               Data deleted successfully
            </div>
            <div class="modal-footer">
                <form action = "all-employee-list.php" method = "get">

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
?>
</body>
</html>
