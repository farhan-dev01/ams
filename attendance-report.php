<!doctype html>
<html class="no-js" lang="">
<?php
require_once 'db_connection.php';
$conn = opencon();
$querytable1 = mysqli_query($conn, "select * from holidays");
$Holidays = " , ";
$holidayStart ="";
$holidayend ="";

while ($r = mysqli_fetch_array($querytable1)){
    $holidayStart = date_create($r[2]);
    $holidayend = date_create($r[3]);

    do{
        $Holidays .=date_format($holidayStart, 'Y-m-d').' , ';
        date_add($holidayStart, date_interval_create_from_date_string('1 days'));
    }while($holidayStart <= $holidayend);
}
function getDaysInYear($startDate,$endDate){

    $days = false;
    $startDate = strtotime($startDate);
    $endDate = strtotime($endDate);

    if($startDate <= $endDate){
        $datediff = $endDate - $startDate;
        $days = floor($datediff / (60 * 60 * 24)); // Total Nos Of Days
    }
    return $days;
}
?>
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

    <script type="text/javascript">
        $(document).ready(
            $(function () {
                $('.date-picker2').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: true,
                    dateFormat: 'MM yy',
                    onClose: function (dateText, inst) {
                        $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
                    }
                });
            }));
    </script>
    <style>
        #pic {

            width: 150px;
            height: 150px;
        }
        .ui-datepicker-calendar {
            display: none;
        }
    </style>


</head>

<body class="mp-pusher" id="mp-pusher">
<!--[if lte IE 9]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade
    your browser</a> to improve your experience and security.</p>
<![endif]-->
<header>
    <?php
    include 'header_hr.php';
    ?>
    <script>

        var curYear=0,curMonth=0;
    </script>
    <section class="pageContent container h-75">
        <div class="filterSection">
            <div class="container">
                <div class="row">
                    <div class="filterTitle col-md-12">
                        <h3>Filter</h3>
                    </div>

                    <form class="filterForm col-md-8" method="POST" id = "filterAll" action="attendance-report.php">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="monthEnd">Month</label>
                                <input class="form-control round monthEnd dateIcon date-picker2" name="date" readonly
                                       type="text"
                                       value="<?php echo isset($_POST['date']) ? $_POST['date'] : date('F Y'); ?>"
                                       id="dt" placeholder=""
                                >
                            </div>
                            <div class="form-group col-md-4">
                                <label for="depttFilter">Department</label>
                                <select name="dname" id="department">
                                    <?php
                                    require_once 'db_connection.php';
                                    $conn = opencon();
                                    {
                                        $query = mysqli_query($conn, "SELECT * from department");

                                        while ($result = mysqli_fetch_array($query)) {
                                            $departno = $result['deptno'];
                                            $departname = $result['dname'];
                                            ?>
                                            <option value="<?php echo $departno; ?>"
                                                    rel="icon-temperature"><?php echo $departname; ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>

                            <div id="empDiv" class="form-group col-md-4">
                                <label for="employeeName">Employee Name</label>
                                <div class="select"><select name="name" id="depar" class="select-hidden">
                                        <option value="<?php
                                        if (isset($_GET['id'])) {
                                            echo addslashes($_GET['id']);
                                        } else if (isset($_POST['submit'])) {echo $_POST['name'];}

                                        ?>" disable="" selected=""> -- Select --</option>
                                    </select>
                                    <div class="select-styled"> -- Select --</div>
                                    <ul class="select-options" style="display: none;">
                                        <li rel=""> -- Select --</li>
                                    </ul>
                                </div>

                            </div>

                        </div>
                        <div class="btnWrap">
                            <button type="button" data-dismiss="filterSection" name="filter" class="btn btn-primary roundBtn
                            applyBtn">Cancel
                            </button>
                            <button type="submit" onclick="if($('.select-styled:eq(1)').text()!='--Select--'){}else{$('#myModal2').modal('show');return false;}" name="submit" class="btn btn-primary roundBtn applyBtn abc">Apply
                            </button>

                        </div>
                    </form>
                </div>
            </div>
        </div><!-- Filter Section -->
        <section class="pageTitle row">
            <div class="col-md-4">
                <h3 class="d-inline">Attendance Report</h3>
            </div><!-- md12 -->

            <div class="col-md-4">
            </div>

            <div class="col-md-4">
                <a href="javascript:void(0)" class="btn btn-primary roundBtn float-right addEmployee">FILTER</a>
            </div>

        </section><!-- Page Title-->
        <section class="blockSection row d-flex align-items-center h-100">
            <div class="reportSection w-100">
                <div class="col-md-12">
                    <div class="leftColumn round boxShadow bg-white float-left w-25">

                        <div class="empDesc text-center">
                            <?php
                            require_once 'db_connection.php';
                            $conn = opencon();
                            if (isset($_GET['id']) || isset($_POST['submit'])) {
                                if (isset($_GET['id'])) {
                                    $id = addslashes($_GET['id']);
                                } else if (isset($_POST['submit'])) {
                                    $id = $_POST['name'];
                                    if($id==null)
                                    {
                                        echo '<div class="modal" id="myModalsu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Warning</h5>
            </div>
            <div class="modal-body" style="padding-right: 270px;">
              Please select an Employee
                </div>
            <div class="modal-footer">
            
<form action="#">
                    <input type="submit" class="btn btn-danger roundBtn"   value = "OK"/>
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
})
                $("#myModalsu").modal("show");
            </script>';
                                    }

                                }
                                $query = mysqli_query($conn, "SELECT * from employee where eid='$id'");//e.ename like '%%' and d.dname like '%%' and a.date<=2019-01-04   order by a.date");

                                while ($row = mysqli_fetch_array($query)) {
                                    ?>
                                    <img src="<?php echo $row['eimage']; ?>" id="pic" class="rounded-circle" alt="" />
                                    <h5 class="d-block"><?php echo $row['ename']; ?></h5>
                                    <span class="d-block"><?php echo $row['eid']; ?></span>
                                    <?php
                                }
                            } else {
                                ?>
                                <img src="content/img/employee-profile.png" class="rounded-circle" alt="" id="pic"/>
                                <h5 class="d-block">XYZ</h5>
                                <span class="d-block">1001</span>

                                <?php
                            } ?>
                        </div>
                    </div>

                    <div class="rightColumn round boxShadow bg-white float-right w-75">
                        <div class="panel panel-primary">
                            <div class="panel-heading col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="row py-2">
                                    <div class="offset-sm-8 offset-md-8 offset-lg-8 offset-xl-8 col-sm-2 col-md-2 col-lg-2 col-xl-2 text-center align-middle text-white font-weight-bold" id="pm"
                                         style="padding-left: 0px; padding-right: 0px;"><span class="prevIcon" style="cursor:pointer" onclick="if(document.getElementById('pm').innerText!=' Month '){submonth();} else{return false;}"></span>
                                        <?php
                                        if (isset($_GET['id'])) {

                                            $month = date("F");
                                            $nmonth = date("m", strtotime('2020'.'-'.'F'.'-1'));
                                            if( date('d')>=26)
                                            {
                                                $nmonth+=1;
                                                $month = date("F", strtotime('2020-'.$nmonth.'-1'));
                                            }
                                            echo '<script> curMonth = '.$nmonth.'</script>';
                                            echo $month;
                                        } elseif (isset($_POST['submit'])) {
                                            $d = explode(' ', $_POST['date']);
                                            $month = $d[0];
                                            $year = $d[1];
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'));
                                            echo '<script> curMonth = '.$nmonth.'</script>';
                                            echo $month;
                                        } else
                                            echo 'Month';

                                        ?>
                                        <span class="nextIcon" style="cursor:pointer" onclick="if(document.getElementById('pm').innerText!=' Month '){addmonth();} else{return false;}"></span></div>
                                    <div class="col-sm-2 text-center align-middle text-white font-weight-bold" id="py"><span
                                                class="prevIcon" style="cursor:pointer" onclick="if(document.getElementById('py').innerText!=' Year'){subyear();} else{return false;}" ></span>
                                        <?php
                                        if (isset($_GET['id'])) {
                                            $year = date("Y");
                                            echo '<script> curYear = '.$year.'</script>';
                                            echo $year;
                                        } elseif (isset($_POST['submit'])) {
                                            $d = explode(' ', $_POST['date']);
                                            $month = $d[0];
                                            $year = $d[1];
                                            echo '<script> curYear = '.$year.'</script>';
                                            echo $year;
                                        } else
                                            echo 'Year';

                                        ?><span class="nextIcon" style="cursor:pointer"onclick="if(document.getElementById('py').innerText!=' Year'){addyear();} else{return false;}"></span></div>
                                </div>
                            </div>
                            <ul class="col-sm-12 col-md-12 col-lg-12 col-xl-12 arList d-block py-0 m-0 ">
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle presentIcon">Present</div>
                                    <?php
                                    if (isset($_GET['id']) || isset($_POST['submit'])) {
                                        if (isset($_GET['id'])) {
                                            $month = date("m");
                                            if( date('d')>=26)
                                            {
                                                $month+=1;
                                            }
                                            $year = date("Y");
                                            $year2 = date("Y");
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        } else if (isset($_POST['submit'])) {
                                           // echo '<script>alert('.$_POST['date'].')</script>';
                                            $d = explode(' ', ($_POST['date']));
                                            $month = $d[0];
                                            $year = $d[1];
                                            $year2 = $d[1];
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        }
                                        $querytable = mysqli_query($conn, "SELECT count(*) from attendance where status='present' and eid='$id' and date between '$date1' and '$date2'");
                                        $abc="SELECT count(*) from attendance where status='present' and eid='$id' and date between '$date1' and '$date2'";

                                        while ($result = mysqli_fetch_array($querytable)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo $result[0]; ?></div>
                                            <?php
                                        }
                                        $y = $year2 - 1;
                                        $d1 = $y . '-12-26';
                                        $d2 = $year2 . '-12-25';
                                        $query2 = mysqli_query($conn, "SELECT count(*) from attendance where status='present' and eid='$id' and date between '$d1' and '$d2'");
                                        while ($r = mysqli_fetch_array($query2)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo $r[0]; ?></div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle absentIcon">Absent</div>
                                    <?php
                                    if (isset($_GET['id']) || isset($_POST['submit'])) {
                                        if (isset($_GET['id'])) {
                                            $month = date("m");
                                            if( date('d')>=26)
                                            {
                                                $month+=1;
                                            }
                                            $year = date("Y");
                                            $year2 = date("Y");
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        } else if (isset($_POST['submit'])) {
                                            $d = explode(' ', ($_POST['date']));
                                            $month = $d[0];
                                            $year = $d[1];
                                            $year2 = $d[1];
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        }
                                        $querytable = mysqli_query($conn, "SELECT count(*) from attendance where status='absent' and eid='$id' and date between '$date1' and '$date2'");//e.ename like '%%' and d.dname like '%%' and a.date<=2019-01-04   order by a.date");
                                        // $result=mysqli_fetch_array($querytable);
                                        while ($result = mysqli_fetch_array($querytable)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo $result[0]; ?></div>
                                            <?php
                                        }
                                        $y = $year2 - 1;
                                        $d1 = $y . '-12-26';
                                        $d2 = $year2 . '-12-25';
                                        $query2 = mysqli_query($conn, "SELECT count(*) from attendance where status='absent' and eid='$id' and date between '$d1' and '$d2'");
                                        while ($r = mysqli_fetch_array($query2)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo $r[0]; ?></div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle workingOnSiteIcon">Working on Site</div>
                                    <?php
                                    if (isset($_GET['id']) || isset($_POST['submit'])) {
                                        if (isset($_GET['id'])) {
                                            $month = date("m");
                                            if( date('d')>=26)
                                            {
                                                $month+=1;
                                            }
                                            $year = date("Y");
                                            $year2 = date("Y");
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        } else if (isset($_POST['submit'])) {
                                            $d = explode(' ', ($_POST['date']));
                                            $month = $d[0];
                                            $year = $d[1];
                                            $year2 = $d[1];
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        }
                                        $querytable = mysqli_query($conn, "SELECT count(*) from attendance where status='workingOnSite' and eid='$id' and date between '$date1' and '$date2'");//e.ename like '%%' and d.dname like '%%' and a.date<=2019-01-04   order by a.date");
                                        // $result=mysqli_fetch_array($querytable);
                                        while ($result = mysqli_fetch_array($querytable)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo $result[0]; ?></div>
                                            <?php
                                        }
                                        $y = $year2 - 1;
                                        $d1 = $y . '-12-26';
                                        $d2 = $year2 . '-12-25';
                                        $query2 = mysqli_query($conn, "SELECT count(*) from attendance where status='workingOnSite' and eid='$id' and date between '$d1' and '$d2'");
                                        while ($r = mysqli_fetch_array($query2)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo $r[0]; ?></div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle unPaidLeaveIcon">Unpaid Leave</div>
                                    <?php
                                    if (isset($_GET['id']) || isset($_POST['submit'])) {
                                        if (isset($_GET['id'])) {
                                            $month = date("m");
                                            if( date('d')>=26)
                                            {
                                                $month+=1;
                                            }
                                            $year = date("Y");
                                            $year2 = date("Y");
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        } else if (isset($_POST['submit'])) {
                                            $d = explode(' ', ($_POST['date']));
                                            $month = $d[0];
                                            $year = $d[1];
                                            $year2 = $d[1];
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        }
                                        $querytable = mysqli_query($conn, "SELECT count(*) from attendance where status='unPaidLeave' and eid='$id' and date between '$date1' and '$date2'");//e.ename like '%%' and d.dname like '%%' and a.date<=2019-01-04   order by a.date");
                                        // $result=mysqli_fetch_array($querytable);
                                        while ($result = mysqli_fetch_array($querytable)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo $result[0]; ?></div>
                                            <?php
                                        }
                                        $y = $year2 - 1;
                                        $d1 = $y . '-12-26';
                                        $d2 = $year2 . '-12-25';
                                        $query2 = mysqli_query($conn, "SELECT count(*) from attendance where status='unPaidLeave' and eid='$id' and date between '$d1' and '$d2'");
                                        while ($r = mysqli_fetch_array($query2)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo $r[0]; ?></div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle weeklyOffIcon">Weekly Off</div>
                                    <?php
                                    if (isset($_GET['id']) || isset($_POST['submit'])) {
                                        if (isset($_GET['id'])) {
                                            $month = date("m");
                                            if( date('d')>=26)
                                            {
                                                $month+=1;
                                            }
                                            $year = date("Y");
                                            $year2 = date("Y");
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        } else if (isset($_POST['submit'])) {
                                            $d = explode(' ', ($_POST['date']));
                                            $month = $d[0];
                                            $year = $d[1];
                                            $year2 = $d[1];
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        }
                                        //$chekWO;
                                        $q= mysqli_query($conn, "SELECT count(a.status)from attendance as a where DAYNAME(a.date)='Friday' and eid='$id' and date between '$date1' and '$date2' ");

                                        while ($res = mysqli_fetch_array($q)) {
                                            $chekWO=$res[0];
                                        }
                                        $woCount=0;
                                        $m1 = cal_days_in_month(CAL_GREGORIAN, $nmonth, $year);
                                        for($j=26;$j<=$m1;$j++)
                                        {
                                            $MstDate1 = $year . '-' . $nmonth . '-'.$j;
                                            $Mst = date_create($MstDate1);
                                            $day = date_format($Mst, 'D');
                                            if($day=='Fri')
                                            {
                                                $woCount++;
                                            }
                                        }
                                        for($j=1;$j<=25;$j++)
                                        {
                                            $MstDate1 = $year2 . '-' . $nmonth2 . '-'.$j;
                                            $Mst = date_create($MstDate1);
                                            $day = date_format($Mst, 'D');
                                            if($day=='Fri')
                                            {
                                                $woCount++;
                                            }
                                        }
                                        $querytable = mysqli_query($conn, "SELECT count(*) from attendance where status='weeklyOff' and eid='$id' and date between '$date1' and '$date2'");//e.ename like '%%' and d.dname like '%%' and a.date<=2019-01-04   order by a.date");
                                        // $result=mysqli_fetch_array($querytable);
                                        while ($result = mysqli_fetch_array($querytable)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo ($result[0]+$woCount)-($chekWO); ?></div>
                                            <?php
                                        }
                                        $y = $year2 - 1;
                                        $d1 = $y . '-12-26';
                                        $d2 = $year2 . '-12-25';
                                        $yearWO;
                                        $ywoCount;
                                        $yq= mysqli_query($conn, "SELECT count(status)from attendance as a where DAYNAME(a.date)='Friday' and eid='$id' and date between '$d1' and '$d2' ");

                                        while ($yres = mysqli_fetch_array($yq)) {
                                            $yearWO=$yres[0];
                                        }
                                        $Mst = date_create($d1);
                                        $Mst2 = date_create($d2);
                                        function getWorkingDays($startDate,$endDate){

                                            $days = false;
                                            $startDate = strtotime($startDate);
                                            $endDate = strtotime($endDate);

                                            if($startDate <= $endDate){
                                                $datediff = $endDate - $startDate;
                                                $days = floor($datediff / (60 * 60 * 24)); // Total Nos Of Days

                                                $sundays = intval($days / 7) + (date('N', $startDate) + $days % 7 >= 7); // Total Nos Of Sundays Between Start Date & End Date
                                                $saturdays = intval($days / 7) + (date('N', $startDate) + $days % 6 >= 6); // Total Nos Of Saturdays Between Start Date & End Date
                                                $thurs = intval($days / 7) + (date('N', $startDate) + $days % 4 >= 4);
                                                $wed = intval($days / 7) + (date('N', $startDate) + $days % 3 >= 3);
                                                $tue = intval($days / 7) + (date('N', $startDate) + $days % 2 >= 2);
                                                $mon= intval($days / 7) + (date('N', $startDate) + $days % 1 >= 1);

                                                $days = $days - ($sundays + $saturdays + $thurs + $wed + $tue + $mon); // Total Nos Of Days Excluding Weekends
                                            }
                                            return $days;
                                        }
                                        if($ywoCount=getWorkingDays($d1,$d2))

                                        $query2 = mysqli_query($conn, "SELECT count(*) from attendance where status='weeklyOff' and eid='$id' and date between '$d1' and '$d2'");
                                        while ($r = mysqli_fetch_array($query2)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo $r[0]+($ywoCount-$yearWO); ?></div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle maternityLeaveIcon">Maternity Leave
                                    </div>
                                    <?php
                                    if (isset($_GET['id']) || isset($_POST['submit'])) {
                                        if (isset($_GET['id'])) {
                                            $month = date("m");
                                            if( date('d')>=26)
                                            {
                                                $month+=1;
                                            }
                                            $year = date("Y");
                                            $year2 = date("Y");
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        } else if (isset($_POST['submit'])) {
                                            $d = explode(' ', ($_POST['date']));
                                            $month = $d[0];
                                            $year = $d[1];
                                            $year2 = $d[1];
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        }
                                        $querytable = mysqli_query($conn, "SELECT count(*) from attendance where status='maternityLeave' and eid='$id' and date between '$date1' and '$date2'");//e.ename like '%%' and d.dname like '%%' and a.date<=2019-01-04   order by a.date");
                                        // $result=mysqli_fetch_array($querytable);
                                        while ($result = mysqli_fetch_array($querytable)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo $result[0]; ?></div>
                                            <?php
                                        }
                                        $y = $year2 - 1;
                                        $d1 = $y . '-12-26';
                                        $d2 = $year2 . '-12-25';
                                        $query2 = mysqli_query($conn, "SELECT count(*) from attendance where status='maternityLeave' and eid='$id' and date between '$d1' and '$d2'");
                                        while ($r = mysqli_fetch_array($query2)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo $r[0]; ?></div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle annualLeaveIcon">Annual Leave</div>
                                    <?php
                                    if (isset($_GET['id']) || isset($_POST['submit'])) {
                                        if (isset($_GET['id'])) {
                                            $month = date("m");
                                            if( date('d')>=26)
                                            {
                                                $month+=1;
                                            }
                                            $year = date("Y");
                                            $year2 = date("Y");
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        } else if (isset($_POST['submit'])) {
                                            $d = explode(' ', ($_POST['date']));
                                            $month = $d[0];
                                            $year = $d[1];
                                            $year2 = $d[1];
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        }
                                        $querytable = mysqli_query($conn, "SELECT count(*) from attendance where status='annualLeave' and eid='$id' and date between '$date1' and '$date2'");//e.ename like '%%' and d.dname like '%%' and a.date<=2019-01-04   order by a.date");
                                        // $result=mysqli_fetch_array($querytable);
                                        while ($result = mysqli_fetch_array($querytable)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo $result[0]; ?></div>
                                            <?php
                                        }
                                        $y = $year2 - 1;
                                        $d1 = $y . '-12-26';
                                        $d2 = $year2 . '-12-25';
                                        $query2 = mysqli_query($conn, "SELECT count(*) from attendance where status='annualLeave' and eid='$id' and date between '$d1' and '$d2'");
                                        while ($r = mysqli_fetch_array($query2)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo $r[0]; ?></div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle compassionateIcon">Compassionate</div>
                                    <?php
                                    if (isset($_GET['id']) || isset($_POST['submit'])) {
                                        if (isset($_GET['id'])) {
                                            $month = date("m");
                                            if( date('d')>=26)
                                            {
                                                $month+=1;
                                            }
                                            $year = date("Y");
                                            $year2 = date("Y");
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        } else if (isset($_POST['submit'])) {
                                            $d = explode(' ', ($_POST['date']));
                                            $month = $d[0];
                                            $year = $d[1];
                                            $year2 = $d[1];
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        }
                                        $querytable = mysqli_query($conn, "SELECT count(*) from attendance where status='compassionate' and eid='$id' and date between '$date1' and '$date2'");//e.ename like '%%' and d.dname like '%%' and a.date<=2019-01-04   order by a.date");
                                        // $result=mysqli_fetch_array($querytable);
                                        while ($result = mysqli_fetch_array($querytable)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo $result[0]; ?></div>
                                            <?php
                                        }
                                        $y = $year2 - 1;
                                        $d1 = $y . '-12-26';
                                        $d2 = $year2 . '-12-25';
                                        $query2 = mysqli_query($conn, "SELECT count(*) from attendance where status='compassionate' and eid='$id' and date between '$d1' and '$d2'");
                                        while ($r = mysqli_fetch_array($query2)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo $r[0]; ?></div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle sickLeaveIcon">Sick Leave</div>
                                    <?php
                                    if (isset($_GET['id']) || isset($_POST['submit'])) {
                                        if (isset($_GET['id'])) {
                                            $month = date("m");
                                            if( date('d')>=26)
                                            {
                                                $month+=1;
                                            }
                                            $year = date("Y");
                                            $year2 = date("Y");
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        } else if (isset($_POST['submit'])) {
                                            $d = explode(' ', ($_POST['date']));
                                            $month = $d[0];
                                            $year = $d[1];
                                            $year2 = $d[1];
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        }
                                        $querytable = mysqli_query($conn, "SELECT count(*) from attendance where status='sickLeave' and eid='$id' and date between '$date1' and '$date2'");//e.ename like '%%' and d.dname like '%%' and a.date<=2019-01-04   order by a.date");
                                        // $result=mysqli_fetch_array($querytable);
                                        while ($result = mysqli_fetch_array($querytable)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo $result[0]; ?></div>
                                            <?php
                                        }
                                        $y = $year2 - 1;
                                        $d1 = $y . '-12-26';
                                        $d2 = $year2 . '-12-25';
                                        $query2 = mysqli_query($conn, "SELECT count(*) from attendance where status='sickLeave' and eid='$id' and date between '$d1' and '$d2'");
                                        while ($r = mysqli_fetch_array($query2)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo $r[0]; ?></div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle paternityLeaveIcon">Paternity Leave
                                    </div>
                                    <?php
                                    if (isset($_GET['id']) || isset($_POST['submit'])) {
                                        if (isset($_GET['id'])) {
                                            $month = date("m");
                                            if( date('d')>=26)
                                            {
                                                $month+=1;
                                            }
                                            $year = date("Y");
                                            $year2 = date("Y");
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        } else if (isset($_POST['submit'])) {
                                            $d = explode(' ', ($_POST['date']));
                                            $month = $d[0];
                                            $year = $d[1];
                                            $year2 = $d[1];
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        }
                                        $querytable = mysqli_query($conn, "SELECT count(*) from attendance where status='paternityLeave' and eid='$id' and date between '$date1' and '$date2'");//e.ename like '%%' and d.dname like '%%' and a.date<=2019-01-04   order by a.date");
                                        // $result=mysqli_fetch_array($querytable);
                                        while ($result = mysqli_fetch_array($querytable)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo $result[0]; ?></div>
                                            <?php
                                        }
                                        $y = $year2 - 1;
                                        $d1 = $y . '-12-26';
                                        $d2 = $year2 . '-12-25';
                                        $query2 = mysqli_query($conn, "SELECT count(*) from attendance where status='paternityLeave' and eid='$id' and date between '$d1' and '$d2'");
                                        while ($r = mysqli_fetch_array($query2)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo $r[0]; ?></div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle businessTripIcon">Business Trip</div>
                                    <?php
                                    if (isset($_GET['id']) || isset($_POST['submit'])) {
                                        if (isset($_GET['id'])) {
                                            $month = date("m");
                                            if( date('d')>=26)
                                            {
                                                $month+=1;
                                            }
                                            $year = date("Y");
                                            $year2 = date("Y");
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        } else if (isset($_POST['submit'])) {
                                            $d = explode(' ', ($_POST['date']));
                                            $month = $d[0];
                                            $year = $d[1];
                                            $year2 = $d[1];
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        }
                                        $querytable = mysqli_query($conn, "SELECT count(*) from attendance where status='businessTrip' and eid='$id' and date between '$date1' and '$date2'");//e.ename like '%%' and d.dname like '%%' and a.date<=2019-01-04   order by a.date");
                                        // $result=mysqli_fetch_array($querytable);
                                        while ($result = mysqli_fetch_array($querytable)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo $result[0]; ?></div>
                                            <?php
                                        }
                                        $y = $year2 - 1;
                                        $d1 = $y . '-12-26';
                                        $d2 = $year2 . '-12-25';
                                        $query2 = mysqli_query($conn, "SELECT count(*) from attendance where status='businessTrip' and eid='$id' and date between '$d1' and '$d2'");
                                        while ($r = mysqli_fetch_array($query2)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo $r[0]; ?></div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle publicHolidayIcon">Public Holiday</div>
                                    <?php
                                    if (isset($_GET['id']) || isset($_POST['submit'])) {
                                        if (isset($_GET['id'])) {
                                            $month = date("m");
                                            if( date('d')>=26)
                                            {
                                                $month+=1;
                                            }
                                            $year = date("Y");
                                            $year2 = date("Y");
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        } else if (isset($_POST['submit'])) {
                                            $d = explode(' ', ($_POST['date']));
                                            $month = $d[0];
                                            $year = $d[1];
                                            $year2 = $d[1];
                                            $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                            if ($nmonth <= 0) {
                                                $nmonth = 12;
                                                $year -= 1;
                                            }
                                            $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                            $date1 = $year . '-' . $nmonth . '-26';
                                            $date2 = $year2 . '-' . $nmonth2 . '-25';
                                        }
                                        $phCount=0;
                                        $phcheck=0;

                                        $m1 = cal_days_in_month(CAL_GREGORIAN, $nmonth, $year);
                                        for($j=26;$j<=$m1;$j++)
                                        {
                                            $MstDate1 = $year . '-' . $nmonth . '-'.$j;

                                                $Mst = date_create($MstDate1);
                                            if(strpos($Holidays,date_format($Mst, 'Y-m-d')))
                                            {
                                                $day = date_format($Mst, 'Y-m-d');
                                                $phCount++;
                                                $query=mysqli_query($conn,"Select count(status) from attendance where status!='publicHoliday' and date ='$day' and eid='$id'");
                                                while ($r = mysqli_fetch_array($query))
                                                {
                                                    $phcheck+=$r[0];
                                                }
                                            }
                                        }
                                        for($j=1;$j<=25;$j++)
                                        {
                                            $MstDate1 = $year2 . '-' . $nmonth2 . '-'.$j;
                                            $Mst = date_create($MstDate1);
                                            if(strpos($Holidays,date_format($Mst, 'Y-m-d')))
                                            {
                                                $day = date_format($Mst, 'Y-m-d');
                                                $phCount++;
                                                $query=mysqli_query($conn,"Select count(status) from attendance where status!='publicHoliday' and date ='$day' and eid='$id'");
                                                while ($r = mysqli_fetch_array($query))
                                                {
                                                    $phcheck+=$r[0];
                                                }
                                            }
                                        }

                                        $querytable = mysqli_query($conn, "SELECT count(*) from attendance where status='publicHoliday' and eid='$id' and date between '$date1' and '$date2'");
                                        while ($result = mysqli_fetch_array($querytable)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo $result[0] + ($phCount-$phcheck); ?></div>
                                            <?php
                                        }
                                        $y = $year2 - 1;
                                        $d1 = $y . '-12-26';
                                        $d2 = $year2 . '-12-25';
                                        $yphCount=0;
                                        $yphcheck=0;
                                        $Mst = date_create($d1);

                                        if($daysInYear=getDaysInYear($d1,$d2));

                                        for($o=26;$o<=31;$o++)
                                        {
                                            $MstDate1 = $y . '-12-'.$o;

                                            $m = date_create($MstDate1);
                                            if(strpos($Holidays,date_format($m, 'Y-m-d')))
                                            {
                                                $day = date_format($m, 'Y-m-d');
                                                $yphCount++;
                                                $query=mysqli_query($conn,"Select count(status) from attendance where status!='publicHoliday' and date ='$day' and eid='$id'");
                                                while ($r = mysqli_fetch_array($query))
                                                {
                                                    $yphcheck+=$r[0];
                                                }
                                            }
                                        }
                                        for($k=1;$k<=($daysInYear);$k++)
                                        {
                                            if(strpos($Holidays,date_format($Mst, 'Y-m-d')))
                                            {
                                                $day = date_format($Mst, 'Y-m-d');
                                                $yphCount++;
                                                $query=mysqli_query($conn,"Select count(status) from attendance where status!='publicHoliday' and date ='$day' and eid='$id'");
                                                while ($r = mysqli_fetch_array($query))
                                                {
                                                    $yphcheck+=$r[0];
                                                }
                                            }
                                            date_add($Mst, date_interval_create_from_date_string('1 days'));
                                        }


                                        $query2 = mysqli_query($conn, "SELECT count(*) from attendance where status='publicHoliday' and eid='$id' and date between '$d1' and '$d2'");
                                        while ($r = mysqli_fetch_array($query2)) {
                                            ?>
                                            <div class="col-sm-2 text-center align-middle"><?php echo $r[0]+($yphCount-$yphcheck); ?></div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <div class="col-sm-2 text-center align-middle">0</div>
                                        <?php
                                    }
                                    ?>
                                </li>
                            </ul>
                        </div>
                    </div>


                </div><!-- MD12 -->
                <!-- MODAL -->
                <div class="modal" id="myModal2">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Warning</h5>
                            </div>
                            <div class="modal-body">
                               Please select an Employee
                            </div>
                            <div class="modal-footer">
                                <form action = "" method = "get">
                                    <input type="submit" data-dismiss="modal" class="btn btn-danger roundBtn"   value = "Ok"/>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
        if (isset($_GET['id']) || isset($_POST['submit']))
        {
            $d = explode(' ', date('F Y'));
            $month = $d[0];
            $year = $d[1];
            $MstDate1 = date_create(($year . '-' . $nmonth . '-1'));
            if(date('d')>=26)
            {
                date_add($MstDate1, date_interval_create_from_date_string('1 months'));
                $date = date_format($MstDate1, 'F Y');
            }
        ?>
        <a target = "_blank" href="print-report.php?id=<?php echo $id; ?>&date=<?php echo isset($_POST['date']) ? $_POST['date'] : $date;  ?>"
           class="printBtn">Print Report</a>
        <?php } ?>
    </section>
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
<script>
    $(document).ready(function () {
        $('.select-styled').first().empty();
        $('.select-styled').first().append('--Select--');
    });
    function submonth(){
        if(curMonth-1<=0){
            curMonth = 12;
            curYear--;
        }else {
            curMonth --;
        }
        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        document.getElementById('dt').value = monthNames[curMonth-1]+" "+curYear;
        $('.abc').click()
    }
    function addmonth(){
        if(curMonth+1>12){
            curMonth = 1;
            curYear++;
        }else {
            curMonth ++;
        }
        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        document.getElementById('dt').value = monthNames[curMonth-1]+" "+curYear;
        $('.abc').click()
    }
    function subyear()
    {
        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        curYear--;
        document.getElementById('dt').value =monthNames[curMonth-1]+" "+curYear;
        $('.abc').click();
    }
    function addyear()
    {
        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        curYear++;
        document.getElementById('dt').value =monthNames[curMonth-1]+" "+curYear;
        $('.abc').click();
    }
    $(document).ready(function () {
        $('.select-styled').first().bind("DOMSubtreeModified", function () {
            var dname = this.innerHTML;

            if (dname != "") {

                $(".select:eq(1)").remove();

                $("#empDiv").append('  <div class="select"><select name="name" id="depar" class="select-hidden"> <option value="" disable="" selected=""> -- Select --</option> </select> <div class="select-styled"> -- Select --</div> <ul class="select-options" style="display: none;"> <li rel=""> -- Select --</li> </ul> </div>');
                $.ajax({
                    url: 'getUsers.php',
                    type: 'post',
                    data: {depart: dname},
                    dataType: 'json',

                    success: function (response) {

                        var len = response.length;
                        $(".select:eq(1)").remove();

                        $("#empDiv").append('<select id="emp" name="name" hidden><option value="" disbale selected></option>');

                        for (var i = 0; i < len; i++) {
                            var id = response[i]['eid'];
                            var name = response[i]['ename'];

                            $("#emp").append("<option value='" + id + "'>" + name + "</option>");

                        }
                        $("#empDiv").append("</select>");

                        prepareSelect(1);
                        $('.select-styled:eq(1)').empty();
                        $('.select-styled:eq(1)').append('--Select--');
                    }
                });
            }
        });
    });
</script>
</body>
</html>
