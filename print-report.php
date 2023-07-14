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

    <style>
        #pic {

            width:150px;
            height:150px;
        }

    </style>
</head>

<body class="mp-pusher" id="mp-pusher">
<!--[if lte IE 9]>
<![endif]-->
<header>
        <section class="pageContent container h-75">
            <section class="pageTitle row">
                <div class="col-md-4">
                    <h3 class="d-inline">Attendance Report</h3>
                </div><!-- md12 -->

                <div class="col-md-4">
                </div>
            </section><!-- Page Title-->
    
            <section class="blockSection row d-flex align-items-center h-100">
            <div class="reportSection w-100">
                <div class="col-md-12">
                    <div class="leftColumn round boxShadow bg-white float-left w-25">

                        <div class="empDesc text-center">
                          <?php
                          require_once 'db_connection.php';
                          $conn=opencon();
                            if(isset($_GET['id'])) {
                                $id = addslashes($_GET['id']);
                            }
                            $query= mysqli_query($conn,"SELECT * from employee where eid='$id'");

                            while ($row=mysqli_fetch_array($query))
                            {
                                $naam=$row['ename'];
                                ?>
                            <img src="<?php echo $row['eimage'];?>" class="rounded-circle" alt="" id="pic" />
                            <h5 class="d-block"><?php echo $row['ename'];?></h5>
                            <span class="d-block"><?php echo $row['eid'];?></span>
                            <?php
                          }?>
                        </div>
                    </div>

                    <div class="rightColumn round boxShadow bg-white float-right w-75">
                        <div class="panel panel-primary">
                            <div class="panel-heading col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="row py-2">
                                    <!-- <div class="col-md-8 text-left align-middle text-white">Present</div> -->
                                    <div class="offset-sm-8 offset-md-8 offset-lg-8 offset-xl-8 col-sm-2 col-md-2 col-lg-2 col-xl-2 text-center align-middle text-white font-weight-bold" style="padding-left: 0px; padding-right: 0px;"><span class="prevIcon"></span>
                                        <?php
                                        if(isset($_GET['id'])) {

                                            $d = explode(' ', (isset($_GET['date']) ? $_GET['date'] : date('F Y')));
                                            $month = $d[0];
                                            $year = $d[1];
                                            echo $month;
}
                                        ?>
                                        <span class="nextIcon"></span></div>
                                    <div class="col-sm-2 text-center align-middle text-white font-weight-bold"><span class="prevIcon"></span>
                                        <?php
                                        if(isset($_GET['id'])) {

                                            $d = explode(' ', (isset($_GET['date']) ? $_GET['date'] : date('F Y')));
                                            $month = $d[0];
                                            $year = $d[1];
                                            echo $year;
                                        }
                                        ?><span class="nextIcon"></span></div>
                                </div>
                            </div>
                            <ul class="col-sm-12 col-md-12 col-lg-12 col-xl-12 arList d-block py-0 m-0 ">
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle presentIcon">Present</div>
                                    <?php
                                    if(isset($_GET['id'])) {
                                        $d = explode(' ', (isset($_GET['date']) ? $_GET['date'] : date('F Y')));
                                        $month = $d[0];
                                        $year = $d[1];
                                        $year2 = $d[1];
                                         $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                        if($nmonth<=0)
                                        {
                                            $nmonth=12;
                                            $year-=1;
                                        }
                                       $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                        $date1 = $year . '-' . $nmonth . '-26' ;
                                        $date2 = $year2 . '-' . $nmonth2 . '-25';
                                    }
                                    $querytable= mysqli_query($conn,"SELECT count(*) from attendance where status='present' and eid='$id' and date between '$date1' and '$date2'");

                                    while ($result=mysqli_fetch_array($querytable))
                                    {
                                    ?>
                                    <div class="col-sm-2 text-center align-middle"><?php echo $result[0]; ?></div>
                                    <?php
                                    }
                                    $y=$year2-1;
                                    $d1 = $y . '-12-26' ;
                                    $d2 = $year2 . '-12-25';
                                    $query2= mysqli_query($conn,"SELECT count(*) from attendance where status='present' and eid='$id' and date between '$d1' and '$d2'");
                                    while ($r=mysqli_fetch_array($query2)) {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle"><?php echo $r[0]; ?></div>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle absentIcon">Absent</div>
                                    <?php
                                    if(isset($_GET['id'])) {
                                        $d = explode(' ', (isset($_GET['date']) ? $_GET['date'] : date('F Y')));
                                        $month = $d[0];
                                        $year = $d[1];
                                        $year2 = $d[1];
                                         $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                        if($nmonth<=0)
                                        {
                                            $nmonth=12;
                                            $year-=1;
                                        }
                                       $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                        $date1 = $year . '-' . $nmonth . '-26' ;
                                        $date2 = $year2 . '-' . $nmonth2 . '-25';
                                    }
                                    $querytable= mysqli_query($conn,"SELECT count(*) from attendance where status='absent' and eid='$id' and date between '$date1' and '$date2'");//e.ename like '%%' and d.dname like '%%' and a.date<=2019-01-04   order by a.date");
                                    // $result=mysqli_fetch_array($querytable);
                                    while ($result=mysqli_fetch_array($querytable))
                                    {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle"><?php echo $result[0]; ?></div>
                                        <?php
                                    }
                                    $y=$year2-1;
                                    $d1 = $y . '-12-26' ;
                                    $d2 = $year2 . '-12-25';
                                    $query2= mysqli_query($conn,"SELECT count(*) from attendance where status='absent' and eid='$id' and date between '$d1' and '$d2'");
                                    while ($r=mysqli_fetch_array($query2)) {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle"><?php echo $r[0]; ?></div>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle workingOnSiteIcon">Working on Site</div>
                                    <?php
                                        if (isset($_GET['id'])) {
                                            $d = explode(' ', (isset($_GET['date']) ? $_GET['date'] : date('F Y')));
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
                                    $y=$year2-1;
                                    $d1 = $y . '-12-26' ;
                                    $d2 = $year2 . '-12-25';
                                    $query2= mysqli_query($conn,"SELECT count(*) from attendance where status='workingOnSite' and eid='$id' and date between '$d1' and '$d2'");
                                    while ($r=mysqli_fetch_array($query2)) {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle"><?php echo $r[0]; ?></div>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle unPaidLeaveIcon">Unpaid Leave</div>
                                    <?php
                                    if(isset($_GET['id'])) {
                                        $d = explode(' ', (isset($_GET['date']) ? $_GET['date'] : date('F Y')));
                                        $month = $d[0];
                                        $year = $d[1];
                                        $year2 = $d[1];
                                         $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                        if($nmonth<=0)
                                        {
                                            $nmonth=12;
                                            $year-=1;
                                        }
                                       $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                        $date1 = $year . '-' . $nmonth . '-26' ;
                                        $date2 = $year2 . '-' . $nmonth2 . '-25';
                                    }

                                    $querytable= mysqli_query($conn,"SELECT count(*) from attendance where status='unPaidLeave' and eid='$id' and date between '$date1' and '$date2'");//e.ename like '%%' and d.dname like '%%' and a.date<=2019-01-04   order by a.date");
                                    // $result=mysqli_fetch_array($querytable);
                                    while ($result=mysqli_fetch_array($querytable))
                                    {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle"><?php echo $result[0]; ?></div>
                                        <?php
                                    }
                                    $y=$year2-1;
                                    $d1 = $y . '-12-26' ;
                                    $d2 = $year2 . '-12-25';
                                    $query2= mysqli_query($conn,"SELECT count(*) from attendance where status='unPaidLeave' and eid='$id' and date between '$d1' and '$d2'");
                                    while ($r=mysqli_fetch_array($query2)) {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle"><?php echo $r[0]; ?></div>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle weeklyOffIcon">Weekly Off</div>
                                    <?php
                                    if(isset($_GET['id'])) {
                                        $d = explode(' ', (isset($_GET['date']) ? $_GET['date'] : date('F Y')));
                                        $month = $d[0];
                                        $year = $d[1];
                                        $year2 = $d[1];
                                         $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                        if($nmonth<=0)
                                        {
                                            $nmonth=12;
                                            $year-=1;
                                        }
                                       $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                        $date1 = $year . '-' . $nmonth . '-26' ;
                                        $date2 = $year2 . '-' . $nmonth2 . '-25';
                                    }
                                    $chekWO;
                                    $q= mysqli_query($conn, "SELECT count(status)from attendance as a where  DAYNAME(a.date)='Friday' and eid='$id' and date between '$date1' and '$date2' ");

                                    while ($res = mysqli_fetch_array($q)) {
                                        $chekWO=$res[0];
                                    }
                                    $woCount=0;
                                    $m1 = cal_days_in_month(CAL_GREGORIAN, $nmonth, $year);
                                    $m2 = cal_days_in_month(CAL_GREGORIAN, $nmonth2, $year2);
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
                                    $querytable= mysqli_query($conn,"SELECT count(*) from attendance where status='weeklyOff' and eid='$id' and date between '$date1' and '$date2'");//e.ename like '%%' and d.dname like '%%' and a.date<=2019-01-04   order by a.date");
                                    // $result=mysqli_fetch_array($querytable);
                                    while ($result=mysqli_fetch_array($querytable))
                                    {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle"><?php echo ($result[0]+$woCount)-($chekWO); ?></div>
                                        <?php
                                    }
                                    $y=$year2-1;
                                    $d1 = $y . '-12-26' ;
                                    $d2 = $year2 . '-12-25';
                                    $yearWO;
                                    $ywoCount;
                                    $yq= mysqli_query($conn, "SELECT count(status)from attendance as a where  DAYNAME(a.date)='Friday' and eid='$id' and date between '$d1' and '$d2' ");

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
                                    $query2= mysqli_query($conn,"SELECT count(*) from attendance where status='weeklyOff' and eid='$id' and date between '$d1' and '$d2'");
                                    while ($r=mysqli_fetch_array($query2)) {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle"><?php echo ($r[0]+$ywoCount)-$yearWO; ?></div>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle maternityLeaveIcon">Maternity Leave</div>
                                    <?php
                                    if(isset($_GET['id'])) {
                                        $d = explode(' ', (isset($_GET['date']) ? $_GET['date'] : date('F Y')));
                                        $month = $d[0];
                                        $year = $d[1];
                                        $year2 = $d[1];
                                         $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                        if($nmonth<=0)
                                        {
                                            $nmonth=12;
                                            $year-=1;
                                        }
                                       $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                        $date1 = $year . '-' . $nmonth . '-26' ;
                                        $date2 = $year2 . '-' . $nmonth2 . '-25';
                                    }
                                    $querytable= mysqli_query($conn,"SELECT count(*) from attendance where status='maternityLeave' and eid='$id' and date between '$date1' and '$date2'");//e.ename like '%%' and d.dname like '%%' and a.date<=2019-01-04   order by a.date");
                                    // $result=mysqli_fetch_array($querytable);
                                    while ($result=mysqli_fetch_array($querytable))
                                    {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle"><?php echo $result[0]; ?></div>
                                        <?php
                                    }
                                    $y=$year2-1;
                                    $d1 = $y . '-12-26' ;
                                    $d2 = $year2 . '-12-25';
                                    $query2= mysqli_query($conn,"SELECT count(*) from attendance where status='maternityLeave' and eid='$id' and date between '$d1' and '$d2'");
                                    while ($r=mysqli_fetch_array($query2)) {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle"><?php echo $r[0]; ?></div>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle annualLeaveIcon">Annual Leave</div>
                                    <?php
                                    if(isset($_GET['id'])) {
                                        $d = explode(' ', (isset($_GET['date']) ? $_GET['date'] : date('F Y')));
                                        $month = $d[0];
                                        $year = $d[1];
                                        $year2 = $d[1];
                                         $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                        if($nmonth<=0)
                                        {
                                            $nmonth=12;
                                            $year-=1;
                                        }
                                       $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                        $date1 = $year . '-' . $nmonth . '-26' ;
                                        $date2 = $year2 . '-' . $nmonth2 . '-25';
                                    }
                                    $querytable= mysqli_query($conn,"SELECT count(*) from attendance where status='annualLeave' and eid='$id' and date between '$date1' and '$date2'");//e.ename like '%%' and d.dname like '%%' and a.date<=2019-01-04   order by a.date");
                                    // $result=mysqli_fetch_array($querytable);
                                    while ($result=mysqli_fetch_array($querytable))
                                    {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle"><?php echo $result[0]; ?></div>
                                        <?php
                                    }
                                    $y=$year2-1;
                                    $d1 = $y . '-12-26' ;
                                    $d2 = $year2 . '-12-25';
                                    $query2= mysqli_query($conn,"SELECT count(*) from attendance where status='annualLeave' and eid='$id' and date between '$d1' and '$d2'");
                                    while ($r=mysqli_fetch_array($query2)) {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle"><?php echo $r[0]; ?></div>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle compassionateIcon">Compassionate</div>
                                    <?php
                                    if(isset($_GET['id'])) {
                                        $d = explode(' ', (isset($_GET['date']) ? $_GET['date'] : date('F Y')));
                                        $month = $d[0];
                                        $year = $d[1];
                                        $year2 = $d[1];
                                         $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                        if($nmonth<=0)
                                        {
                                            $nmonth=12;
                                            $year-=1;
                                        }
                                       $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                        $date1 = $year . '-' . $nmonth . '-26' ;
                                        $date2 = $year2 . '-' . $nmonth2 . '-25';
                                    }

                                    $querytable= mysqli_query($conn,"SELECT count(*) from attendance where status='compassionate' and eid='$id' and date between '$date1' and '$date2'");//e.ename like '%%' and d.dname like '%%' and a.date<=2019-01-04   order by a.date");
                                    // $result=mysqli_fetch_array($querytable);
                                    while ($result=mysqli_fetch_array($querytable))
                                    {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle"><?php echo $result[0]; ?></div>
                                        <?php
                                    }
                                    $y=$year2-1;
                                    $d1 = $y . '-12-26' ;
                                    $d2 = $year2 . '-12-25';
                                    $query2= mysqli_query($conn,"SELECT count(*) from attendance where status='compassionate' and eid='$id' and date between '$d1' and '$d2'");
                                    while ($r=mysqli_fetch_array($query2)) {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle"><?php echo $r[0]; ?></div>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle sickLeaveIcon">Sick Leave</div>
                                    <?php
                                    if(isset($_GET['id'])) {
                                        $d = explode(' ', (isset($_GET['date']) ? $_GET['date'] : date('F Y')));
                                        $month = $d[0];
                                        $year = $d[1];
                                        $year2 =$d[1];
                                         $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                        if($nmonth<=0)
                                        {
                                            $nmonth=12;
                                            $year-=1;
                                        }
                                       $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                        $date1 = $year . '-' . $nmonth . '-26' ;
                                        $date2 = $year2 . '-' . $nmonth2 . '-25';
                                    }
                                    $querytable= mysqli_query($conn,"SELECT count(*) from attendance where status='sickLeave' and eid='$id' and date between '$date1' and '$date2'");//e.ename like '%%' and d.dname like '%%' and a.date<=2019-01-04   order by a.date");
                                    // $result=mysqli_fetch_array($querytable);
                                    while ($result=mysqli_fetch_array($querytable))
                                    {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle"><?php echo $result[0]; ?></div>
                                        <?php
                                    }
                                    $y=$year2-1;
                                    $d1 = $y . '-12-26' ;
                                    $d2 = $year2 . '-12-25';
                                    $query2= mysqli_query($conn,"SELECT count(*) from attendance where status='sickLeave' and eid='$id' and date between '$d1' and '$d2'");
                                    while ($r=mysqli_fetch_array($query2)) {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle"><?php echo $r[0]; ?></div>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle paternityLeaveIcon">Paternity Leave</div>
                                    <?php
                                    if(isset($_GET['id'])) {
                                        $d = explode(' ', (isset($_GET['date']) ? $_GET['date'] : date('F Y')));
                                        $month = $d[0];
                                        $year = $d[1];
                                        $year2 =$d[1];
                                         $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                        if($nmonth<=0)
                                        {
                                            $nmonth=12;
                                            $year-=1;
                                        }
                                       $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                        $date1 = $year . '-' . $nmonth . '-26' ;
                                        $date2 = $year2 . '-' . $nmonth2 . '-25';
                                    }
                                    $querytable= mysqli_query($conn,"SELECT count(*) from attendance where status='paternityLeave' and eid='$id' and date between '$date1' and '$date2'");//e.ename like '%%' and d.dname like '%%' and a.date<=2019-01-04   order by a.date");
                                    // $result=mysqli_fetch_array($querytable);
                                    while ($result=mysqli_fetch_array($querytable))
                                    {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle"><?php echo $result[0]; ?></div>
                                        <?php
                                    }
                                    $y=$year2-1;
                                    $d1 = $y . '-12-26' ;
                                    $d2 = $year2 . '-12-25';
                                    $query2= mysqli_query($conn,"SELECT count(*) from attendance where status='paternityLeave' and eid='$id' and date between '$d1' and '$d2'");
                                    while ($r=mysqli_fetch_array($query2)) {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle"><?php echo $r[0]; ?></div>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle businessTripIcon">Business Trip</div>
                                    <?php
                                    if(isset($_GET['id'])) {
                                        $d = explode(' ', (isset($_GET['date']) ? $_GET['date'] : date('F Y')));
                                        $month = $d[0];
                                        $year = $d[1];
                                        $year2 = $d[1];
                                         $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                        if($nmonth<=0)
                                        {
                                            $nmonth=12;
                                            $year-=1;
                                        }
                                       $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                        $date1 = $year . '-' . $nmonth . '-26' ;
                                        $date2 = $year2 . '-' . $nmonth2 . '-25';
                                    }
                                    $querytable= mysqli_query($conn,"SELECT count(*) from attendance where status='businessTrip' and eid='$id' and date between '$date1' and '$date2'");//e.ename like '%%' and d.dname like '%%' and a.date<=2019-01-04   order by a.date");
                                    // $result=mysqli_fetch_array($querytable);
                                    while ($result=mysqli_fetch_array($querytable))
                                    {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle"><?php echo $result[0]; ?></div>
                                        <?php
                                    }
                                    $y=$year2-1;
                                    $d1 = $y . '-12-26' ;
                                    $d2 = $year2 . '-12-25';
                                    $query2= mysqli_query($conn,"SELECT count(*) from attendance where status='businessTrip' and eid='$id' and date between '$d1' and '$d2'");
                                    while ($r=mysqli_fetch_array($query2)) {
                                        ?>
                                        <div class="col-sm-2 text-center align-middle"><?php echo $r[0]; ?></div>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <li class="row border-bottom py-2">
                                    <div class="col-md-8 text-left align-middle publicHolidayIcon">Public Holiday</div>
                                    <?php
                                    if(isset($_GET['id'])) {
                                        $d = explode(' ', (isset($_GET['date']) ? $_GET['date'] : date('F Y')));
                                        $month = $d[0];
                                        $year = $d[1];
                                        $year2 =$d[1];
                                        $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                        if($nmonth<=0)
                                        {
                                            $nmonth=12;
                                            $year-=1;
                                        }
                                        $nmonth2 = date("m", strtotime($year.'-'.$month.'-1'));
                                        $date1 = $year . '-' . $nmonth . '-26' ;
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
                                    ?>
                                </li>

                            </ul>
                        </div>
                    </div>


                </div><!-- MD12 -->
                
            </div>
        </section>
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
</body>
<script type="text/javascript">
    window.print();
    window.close();
</script>
</html>
