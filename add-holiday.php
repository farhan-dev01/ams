<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Set Holidays</title>
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
            <div class="col-md-10">
                <h3>Set Holidays</h3>
            </div><!-- md12 -->
        </section><!-- Page Title-->

      <section class="blockSection d-flex align-items-center h-75">
        <div class="d-flex align-items card col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-4 offset-lg-4 col-xl-4 offset-xl-4">
            <div class="card-body py-4">
                <div class="profileImage">
                    <img src="content/img/employee-profile.png" id = "uploadImage" style="height: 100px;width: 100px;" class="rounded-circle" alt="" />

                    <a href="javascript:void(0)" class="profileButton" onclick="document.getElementById('fileUpload').click()" accept="image/*"></a>
                </div>
                <form class="mt-4 align-items-center" enctype="multipart/form-data" method="POST" action="#">
                    <input hidden="hidden" type="file" onchange="readURL(this);" name="pic" id="fileUpload" accept="image/*">
                  <div class="form-group">
                      <label for="holidayReason">Holiday Reason</label>
                    <input class="form-control round"required maxlength="15" type="text" name="holidayReason" placeholder="New Year" pattern="[A-Za-z ]+" value="<?php echo (isset($_POST['holidayReason'])? $_POST['holidayReason'] : ''); ?>">
                  </div>
                  <div class="form-group">
                      <label for="holidayDate1">Start Date</label>
                      <input class="form-control round dateIcon" required data-toggle="datepicker" readonly type="text" name="startDate"  placeholder="select a date"  value="<?php echo (isset($_POST['startDate'])? $_POST['startDate'] : ''); ?>">
                  </div>
                  <div class="form-group">
                      <label for="holidayDate2">End Date</label>
                      <input class="form-control round dateIcon" required data-toggle="datepicker" readonly type="text" name="endDate"  placeholder="select a date"  value="<?php echo (isset($_POST['endDate'])? $_POST['endDate'] : ''); ?>">
                  </div>
                  <div class="form-group text-center">
                      <button class="btn btn-primary roundBtn" type="submit" name="submit">ADD</button>
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

if($_POST['startDate']==null || $_POST['endDate'] == null)
{
    echo  '<div class="modal" id="myModalsu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Warning</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                Please set dates first
            </div>
            <div class="modal-footer">
                <form action = "" method = "get">

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
$reason = $_POST['holidayReason'];
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
    $mImage = "img/"."emp_".$reason.$_FILES['pic']['name'] ;
    $mImage=str_replace(' ','',$mImage);
    $needheight = 24;
    $needwidth = 24;
if($_FILES['pic']['name']!=null) {
    $arrtest = getimagesize($_FILES['pic']['tmp_name']);

    $actualwidth = $arrtest[0];
    $actualheight = $arrtest[1];
}
$sd=date_create($startDate);
$ed=date_create($endDate);
if($sd>$ed)
{
    goto error2;
}
    $querytable1 = mysqli_query($conn, "select * from holidays");
    $Holidays = "";
    $holidayStart ="";
    $holidayend ="";

    while ($r = mysqli_fetch_array($querytable1)){
        $holidayStart = date_create($r[2]);
        $holidayend = date_create($r[3]);
        if($holidayStart!=$holidayend)
            date_sub($holidayStart, date_interval_create_from_date_string('1 days'));

        do{
           if($sd==$holidayStart || $ed==$holidayStart)
           {
               goto error;

           }
            date_add($holidayStart, date_interval_create_from_date_string('1 days'));
        }while($holidayStart <= $holidayend);
    }


    $query1 = mysqli_query($conn, "SELECT reason FROM holidays where reason like '%".$reason."%'") or die(mysqli_error($conn));
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
                Holiday Reason Already exists.
            </div>
            <div class="modal-footer">
                <form action = "#" method = "get">

                    <input type="submit" class="btn btn-danger roundBtn"  data-dismiss="modal" value = "OK"/>
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
        if (move_uploaded_file($_FILES['pic']['tmp_name'], $mImage)) {


            if($actualwidth > $needwidth ||  $actualheight > $needheight){
                echo  '<div class="modal" id="myModalsu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Warning</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                Icon size is too big
            </div>
            <div class="modal-footer">
                <form action = "# method = "get">

                    <input type="submit" class="btn btn-danger roundBtn"  data-dismiss="modal" value = "OK"/>
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
            else{

$query="Insert into holidays(reason,startdate,enddate,image) values ('$reason','$startDate','$endDate','$mImage')";
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
    echo '<div class="modal" id="myModalsu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Added</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                Holiday Added Successfully
            </div>
            <div class="modal-footer">
                <form action = "all-holiday.php" method = "get">

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
}}}
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
    }
    error:
    echo  '<div class="modal" id="myModalsu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Warning</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                Dates Already exists.
            </div>
            <div class="modal-footer">
                <form action = "#" method = "get">

                    <input type="submit" class="btn btn-danger roundBtn"  data-dismiss="modal" value = "OK"/>
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
    error2:
    echo  '<div class="modal" id="myModalsu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Warning</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                End Date is smaller than Start Date..!
            </div>
            <div class="modal-footer">
                <form action = "" method = "get">

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
    <!-- <script src="content/js/datepicker.js"></script> -->
    <script src="content/js/jquery/jquery-ui.js"></script>
    <script src="content/js/main.js"></script>
    
</body>
</html>
