<!doctype html>



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
    <link rel="stylesheet" type="text/css" href="content/css/icons.css"/>
    <link rel="stylesheet" href="content/css/bootstrap.min.css">
    <link rel="stylesheet" href="content/css/style.css">

    <!-- SCRIPTS -->
    <script src="content/js/vendor/modernizr-3.6.0.min.js"></script>
    <script src="content/js/jquery-slim.min.js"></script>
    <script src="content/js/modernizr.custom.js"></script>
    <script src="content/js/jquery/jquery.js"></script>
    <script src="content/js/bootstrap.min.js"></script>
    
    <link rel="stylesheet" href="content/font-awesome/css/font-awesome.css">

</head>
<?php

$allOK = true;
$c=false;
$records;
$holidays;
if (isset($_POST['btnsave'])) {

    require_once 'db_connection.php';
    $conn = opencon();

    try {

        foreach ($_POST['eid'] as $eid) {

            $d = explode(' ', isset($_GET['date']) ? $_GET['date'] : date('F Y'));
            $month = $d[0];
            $year = $d[1];
            $nmonth = date("m", strtotime($month))-1;
            if(date('d')>=26)
            {
                $nmonth+=1;

            }
            $i = 25;
            $MstDate1 = date_create(($year . '-' . $nmonth . '-' . $i));

            foreach ($_POST['status' . $eid] as $status) {
                date_add($MstDate1, date_interval_create_from_date_string('1 days'));
                $date = date_format($MstDate1, 'Y-m-d');

                if($status == ''){continue;}

                $query =
                    mysqli_query($conn, ("INSERT INTO attendance (date,eid,status) SELECT '$date','$eid','$status' FROM DUAL
WHERE NOT EXISTS (SELECT eid FROM attendance WHERE eid='$eid'and date = '$date');")) or die(mysqli_error($conn));

                if (!$query) {

                    $allOK = false;
                }
                else
                {
                    $c=true;
                }
            }

            if(!$allOK){
                break;
            }
        }
        if($allOK){
        }
        $conn->close();
    }catch(\Exception $ex){


    }

}
$d = explode(' ', (isset($_GET['date']) ? $_GET['date'] : date('F Y')));
$month = $d[0];
$year = $d[1];
$nmonth = date("m", strtotime($month))-1;
if(date('d')>=26)
{
    $nmonth+=1;

}
if($nmonth<=0)
{
    $nmonth=12;
    $year-=1;
}
$MstDate1 = date_create(($year . '-' . $nmonth . '-26'));

date_add($MstDate1, date_interval_create_from_date_string('1 months'));
date_sub($MstDate1, date_interval_create_from_date_string('1 days'));
$date = date_format($MstDate1, 'Y-m-d');

require_once 'db_connection.php';
$conn = opencon();
$querytable= mysqli_query($conn,"SELECT a.date,e.eid,a.status from attendance as a join employee as e on a.eid = e.eid JOIN department 
as d on e.deptno = d.deptno where a.date BETWEEN '".($year."-".($nmonth))."-26' and '$date' order by a.eid,a.DATE ");

$records = mysqli_fetch_all($querytable);

require_once 'db_connection.php';
$conn = opencon();
$querytable= mysqli_query($conn,"SELECT `startdate`,`enddate`,`image`,`reason` from holidays");

$holidays = mysqli_fetch_all($querytable);

?>

<?php
if (!$allOK) {

    echo '<div class="modal" id="myModalsu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attendance</h5>
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
 //alert("data entered");
 $(\'#myModalsu\').modal({
    backdrop: \'static\',
    keyboard: false
});
                $("#myModalsu").modal("show");
            </script>';

}
if($c==true)
{

    echo '<div class="modal" id="myModalsu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Marked</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                 Attendance has been marked successfully
            </div>
            <div class="modal-footer">
            

                    <input type="submit" data-dismiss="modal" class="btn btn-success roundBtn"   value = "OK"/>
       
            </div>
        </div>
    </div>
</div>
            <script>
 //alert("data entered");
 $(\'#myModalsu\').modal({
    backdrop: \'static\',
    keyboard: false
})
                $("#myModalsu").modal("show");
            </script>';

}


?>

<body class="mp-pusher" id="mp-pusher">
<!--[if lte IE 9]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade
    your browser</a> to improve your experience and security.</p>
<![endif]-->
<header>

    <script>

        var inputname = "";

    </script>


    <?php
    include 'header_department.php';
    ?>

    <?php echo '<script>var curRecords = '.json_encode($records).'</script>';?>
    <?php echo '<script>var holidays = '.json_encode($holidays).'</script>';?>
    <?php
    require_once 'db_connection.php';
    $conn=opencon();
    $per_page = 10;
    $pages_query = mysqli_query($conn,"select count('eid') from employee where deptno='$dept'");
    $records = mysqli_fetch_array($pages_query);
    $records = $records[0];
    $pages = ceil($records / $per_page);
    $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
    $start = ($page - 1) * $per_page;
    $sno = $start + 1;

    ?>
    <div class="pageWrapper bg-light h-50">
        <section class="pageContent container">
            <div class="filterSection">
                <div class="container">
                    <div class="row">
                        <div class="filterTitle col-md-12">
                            <h3>Filter</h3>
                        </div>

                        <form class="filterForm col-md-8" method="POST" action="#">
                            <div class="row">

                                <div class="form-group col-md-4">
                                    <label for="employeeName">Employee Name</label>
                                    <select name="name">
                                        <?php

                                        require_once 'db_connection.php';
                                        $conn = opencon();
                                        {
                                            $query = mysqli_query($conn, "SELECT * from employee where deptno='$dept'");

                                            $sno = 1;
                                            while ($result = mysqli_fetch_array($query)) {
                                                $d = $result['date'];
                                                ?>
                                                <option value="<?php echo $result['eid']; ?>"
                                                        rel="icon-temperature"><?php echo $result['ename']; ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>

                            </div>
                            <div class="btnWrap">
                                <button type="button" data-dismiss="filterSection" name="submit" class="btn btn-primary roundBtn
                            applyBtn">Cancel
                                </button>
                                <button type="submit"  onclick="if($('.select-styled:eq(0)').text()!='--Select--'){}else{window.location.assign('mark-attendance-t.php');return false;}" name="submit" class="btn btn-primary roundBtn applyBtn">Apply
                                </button>

                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- Filter Section -->

            <form method="get" id = "form" >
                <section class="pageTitle row">
                    <div class="col-md-4">
                        <h3 class="d-inline">Mark Attendance</h3>
                    </div><!-- md12 -->

                    <div class="col-md-4">
                    </div>

                    <div class="col-md-4">
                        <a href="javascript:void(0)" class="btn btn-primary roundBtn float-right addEmployee">FILTER</a>
                    </div>
                </section><!-- Page Title-->
                <div class="monthWrap">
                    <a href="javascript:void(0)"></a>
                    <a href="javascript:void(0)">
                        <?php
                        $d = explode(' ', date('F Y'));
                        $month = $d[0];
                        $year = $d[1];
                        $MstDate1 = date_create(($year . '-' . $nmonth . '-1'));
                        if(date('d')>=26)
                        {
                            date_add($MstDate1, date_interval_create_from_date_string('1 months'));
                            $date = date_format($MstDate1, 'F Y');
                        }
                        else
                        {
                            $date = date('F Y');
                        }
                        echo $date;
                        ?>
                    </a>
                    <a href="javascript:void(0)"></a>
                </div>
            </form>
            <form method="post">
                <section class="">
                    <section class="blockSection ">
                        <div class="table-responsive">

                            <ul class="employeListHeading clearfix">
                                <li class="align-middle ">EMPLOYEE NAME</li>
                                <li class="align-middle">EMP ID</li>
                                <?php
                                $d=explode(' ',isset($_GET['date']) ? $_GET['date'] : date('F Y'));
                                //echo'<script>alert('.date("d").');</script>';
                                $month =$d[0];
                                $year =$d[1];
                                $nmonth = date("m", strtotime($month))-1;
                                if(date('d')>=26)
                                {
                                    $nmonth+=1;

                                }


                                $stdate1 = ($year . '-' . $nmonth . '-' . 26);

                                $MstDate1 = date_create($stdate1);
                                $MendDate1 = date_create($stdate1);
                                date_add($MendDate1, date_interval_create_from_date_string('1 months'));

                                $datediff1 = date_diff($MendDate1, $MstDate1)->days;

                                for($i=1;$i<=$datediff1;$i++) {
                                    $date = date_format($MstDate1, 'Y-m-d');

                                    echo '<li><label>'.date_format($MstDate1, 'd').'</label><span>'.date_format
                                        ($MstDate1, 'D').'</span></li>';

                                    date_add($MstDate1, date_interval_create_from_date_string('1 days'));

                                }

                                ?>
                            </ul>
                            <div id="page">


                                <?php
                                require_once 'db_connection.php';
                                $conn = opencon();
                                {
                                    if (isset($_POST['submit'])) {
                                        $ename = $_POST['name'];

                                        $query = mysqli_query($conn,
                                            "SELECT employee.*,department.dname from employee join department on employee.deptno=department.deptno where employee.eid like '%" .
                                            $ename . "%' and department.deptno like '%" . $dept . "%' order by employee.eid limit $start,$per_page ");
                                    } else {
                                        $query = mysqli_query($conn,
                                            "SELECT employee.*,department.dname FROM employee join department on employee.deptno=department.deptno where employee.deptno ='$dept' order by employee.eid limit $start,$per_page") or
                                        die(mysqli_error($conn));
                                    }
                                    $sno = 1;
                                    while ($result = mysqli_fetch_array($query))
                                    {
                                        ?>

                                        <ul class="employeeList clearfix">
                                            <li class="bg-white round boxShadow">
                                                <ul class="employeeListDesc clearfix align-middle">
                                                    <li class="employeeImage clearfix"><img src=<?php echo $result['eimage'];?> class="rounded-circle" alt="" /><label class="text-left"><?php echo $result['ename'];?></label></li>
                                                    <li class="employeeID"><?php echo $result['eid'];?><input
                                                                type="hidden" value="<?php echo $result['eid'];?>" name="eid[]" /></li>

                                                    <?php
                                                    $a = ['present', 'absent' , 'workingOnSite' , 'unPaidLeave' , 'weeklyOff' ,
                                                        'maternityLeave' , 'annualLeave' , 'compassionate' , 'sickLeave' ,
                                                        'paternityLeave' , 'businessTrip' ];
                                                    $icon = "";


                                                    $d=explode(' ',isset($_GET['date']) ? $_GET['date'] : date('F Y'));
                                                    $month =$d[0];
                                                    $year =$d[1];
                                                    $nmonth = date("m", strtotime($month))-1;
                                                    if(date('d')>=26)
                                                    {
                                                        $nmonth+=1;

                                                    }
                                                    $stdate1 = ($year . '-' . $nmonth . '-' . 26);

                                                    $MstDate1 = date_create($stdate1);
                                                    $MendDate1 = date_create($stdate1);
                                                    date_add($MendDate1, date_interval_create_from_date_string('1 months'));

                                                    $datediff1 = date_diff($MendDate1, $MstDate1)->days;
                                                    $l=0;
                                                    $l = 0;
                                                    for ($i = 1; $i <= $datediff1; $i++) {
                                                        $l++;
                                                        $datee = date_format($MstDate1, 'Y-m-d');

                                                        $day = date_format($MstDate1, 'D');
                                                        $iconH = ($day == 'Fri' ? 'weeklyOffIcon ' : 'defaultIcon '
                                                        );

                                                        ?>

                                                        <li class="JPO_open"><a id="a<?php echo $result['eid']
                                                                . $datee;
                                                            ?>" class="icon <?php echo $iconH . 'a' . $datee; ?>"
                                                                                href="javascript:void(0)"
                                                                                onclick="inputname = '<?php echo $result['eid'].$datee;?>';"
                                                            ></a><input type="hidden" value="" name="<?php echo 'status'
                                                                .$result['eid'];
                                                            ?>[]" id="<?php echo $result['eid']
                                                                .$datee;
                                                            ?>"/> </li>

                                                        <?php
                                                        date_add($MstDate1, date_interval_create_from_date_string('1 days'));

                                                    }

                                                    ?>
                                                </ul>
                                            </li>
                                        </ul>

                                        <?php
                                        $sno++;
                                    }
                                }
                                ?>

                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-primary px-5 border-0 roundBtn" type="sumbit" name="btnsave" >SAVE
                            </button>
                        </div>
                        <form/>
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
                </section>
                <!-- Page Wrapper Ends -->
                <section class="blockSection ">

                    <div class="form-check w-25 popOver bg-white mb-3 p-2" id="JPO">
                        <div class="box">
                            <input type="radio" onchange="if(this.checked){document.getElementById('a'+inputname).style.cssText= 'background:url(icon presentIcon);';document.getElementById(inputname).value =
                'present'; this.checked = false; document
                .getElementById('a'+inputname).className = 'icon presentIcon';$('#JPO').popup('hide');
                }else{}" id="present" name="markAttendance"/>
                            <label class="present" for="present">Present</label>

                            <input type="radio" onchange="if(this.checked){document.getElementById('a'+inputname).style.cssText= 'background:url(icon absentIcon);';
                            ;document.getElementById(inputname).value =
                'absent'; this.checked = false;$('#JPO').popup('hide') ;document
                .getElementById('a'+inputname).className = 'icon absentIcon';}else{}" id="absent"
                                   name="markAttendance"/>
                            <label class="absent" for="absent">Absent</label>

                            <input type="radio" onchange="if(this.checked){document.getElementById('a'+inputname).style.cssText= 'background:url(icon workingOnSiteIcon);';document.getElementById(inputname).value =
                'workingOnSite'; this.checked = false;$('#JPO').popup('hide') ;document
                .getElementById('a'+inputname).className = 'icon workingOnSiteIcon';}else{}" id="workingOnSite"
                                   name="markAttendance"/>
                            <label class="workOnSite" for="workingOnSite">Work on Site</label>

                            <input type="radio" onchange="if(this.checked){document.getElementById('a'+inputname).style.cssText= 'background:url(icon unPaidLeaveIcon);';document.getElementById(inputname).value =
                'unPaidLeave'; this.checked = false;$('#JPO').popup('hide') ;document
                .getElementById('a'+inputname).className = 'icon unPaidLeaveIcon';}else{}" id="unPaidLeave"
                                   name="markAttendance"/>
                            <label class="unpaidLeave" for="unPaidLeave">Unpaid Leave</label>

                            <input type="radio" onchange="if(this.checked){document.getElementById('a'+inputname).style.cssText= 'background:url(icon weeklyOffIcon);';document.getElementById(inputname).value =
                'weeklyOff'; this.checked = false;$('#JPO').popup('hide') ;document
                .getElementById('a'+inputname).className = 'icon weeklyOffIcon';}else{}" id="weeklyOff"
                                   name="markAttendance"/>
                            <label class="weeklyOff" for="weeklyOff">Weekly Off</label>

                            <input type="radio" onchange="if(this.checked){document.getElementById('a'+inputname).style.cssText= 'background:url(icon maternityLeaveIcon);';document.getElementById(inputname).value =
                'maternityLeave'; this.checked = false;$('#JPO').popup('hide') ;document
                .getElementById('a'+inputname).className = 'icon maternityLeaveIcon';}else{}" id="maternityLeave"
                                   name="markAttendance"/>
                            <label class="maternityLeave" for="maternityLeave">Maternity Leave</label>

                            <input type="radio" onchange="if(this.checked){document.getElementById('a'+inputname).style.cssText= 'background:url(icon annualLeaveIcon);';document.getElementById(inputname).value =
                'annualLeave'; this.checked = false;$('#JPO').popup('hide') ;document
                .getElementById('a'+inputname).className = 'icon annualLeaveIcon';}else{}" id="annualLeave"
                                   name="markAttendance"/>
                            <label class="annualLeave" for="annualLeave">Annual Leave</label>

                            <input type="radio" onchange="if(this.checked){document.getElementById('a'+inputname).style.cssText= 'background:url(icon compassionateIcon);';document.getElementById(inputname).value =
                'compassionate'; this.checked = false;$('#JPO').popup('hide') ;document
                .getElementById('a'+inputname).className = 'icon compassionateIcon';}else{}" id="compassionate"
                                   name="markAttendance"/>
                            <label class="compassionate" for="compassionate">Compassionate</label>

                            <input type="radio" onchange="if(this.checked){document.getElementById('a'+inputname).style.cssText= 'background:url(icon sickLeaveIcon);';document.getElementById(inputname).value =
                'sickLeave'; this.checked = false;$('#JPO').popup('hide') ;document
                .getElementById('a'+inputname).className = 'icon sickLeaveIcon';}else{}" id="sickLeave"
                                   name="markAttendance"/>
                            <label class="sickLeave" for="sickLeave">Sick Leave</label>

                            <input type="radio" onchange="if(this.checked){document.getElementById('a'+inputname).style.cssText= 'background:url(icon paternityLeaveIcon);';document.getElementById(inputname).value =
                'paternityLeave'; this.checked = false;$('#JPO').popup('hide') ;document
                .getElementById('a'+inputname).className = 'icon paternityLeaveIcon';}else{}" id="paternityLeave"
                                   name="markAttendance"/>
                            <label class="paternityLeave" for="paternityLeave">Paternity Leave</label>

                            <input type="radio" onchange="if(this.checked){document.getElementById('a'+inputname).style.cssText= 'background:url(icon businessTripIcon);';document.getElementById(inputname).value =
                'businessTrip'; this.checked = false;$('#JPO').popup('hide') ;document
                .getElementById('a'+inputname).className = 'icon businessTripIcon';}else{}" id="businessTrip"
                                   name="markAttendance"/>
                            <label class="businessTrip" for="businessTrip">Business Trip</label>

                        </div>
                    </div>

                </section>
                <!-- Page Footer -->
                <!-- SCRIPTS -->
                <footer class="pageFooter">
                    <p>Design and Develop by Zoom Arts & Design</p>
                </footer> <!-- Page Footer -->
</header>

</body>
<!-- SCRIPTS -->


<!-- Include jQuery Popup Overlay -->
<script src="content/js/jquery.popupoverlay.min.js"></script>
<script src="content/js/moment.js"> </script>

<script>
    $(document).ready(function() {

        // Initialize the plugin
        $('#JPO').popup();

        // Set default `pagecontainer` for all popups (optional, but recommended for screen readers and iOS*)
        $.fn.popup.defaults.pagecontainer = '#page'
    });
</script>
<script>
    $(document).on('click',"#JPO", function(e) {
        $('#JPO').fadeOut();
    });

    $(document).ready(function () {

        curRecords.forEach(function(element) {
            try{
                document.getElementById('a' + element[1] + element[0]).className = 'icon ' + element[2] + 'Icon';
                document.getElementById('a' + element[1] + element[0]).parentNode.className = "";

            }catch(exception){console.log(exception)}

        });
        holidays.forEach(function(element) {
            try {
                var startDate = new Date(element[0]);
                var endDate = new Date(element[1]);
                var end2=endDate.setDate(endDate.getDate()+1);
                var image= element[2];
                var title=element[3];
                while (!moment(startDate).isSame(end2)) {

                    var size = $(".a" + moment(startDate).format('YYYY-MM-DD')).length;
                    var e = document.getElementsByClassName("a"+moment(startDate).format('YYYY-MM-DD'));

                    for (var x = 0; x < size; x++) {
                        document.getElementsByClassName("a"+moment(startDate).format('YYYY-MM-DD'))[0].title= title;
                        e[x].style.cssText
                            = "background: url('"+image+"') no-repeat left center; opacity: 2;";
                    }
                    startDate = new Date(moment(startDate).add(1, "days").format('YYYY-MM-DD'));
                }
            }catch (exception){ console.log(exception);}

        });
    });

</script>

<script src="content/js/popper.min.js"></script>
<script src="content/js/bootstrap.min.js"></script>
<script src="content/js/plugins.js"></script>
<script src="content/js/classie.js"></script>
<script src="content/js/mlpushmenu.js"></script>
<!-- <script src="content/js/datepicker.js"></script> -->
<script src="content/js/jquery/jquery-ui.js"></script>
<script src="content/js/main.js"></script>
<script>
    $(document).ready(function () {
        $('.select-styled').first().empty();
        $('.select-styled').first().append('--Select--');
    });
</script>
</body>


</html>
