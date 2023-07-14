<!doctype html>


<?php

$records;
$holidays;
$getDate=isset($_GET['date']) ? $_GET['date'] : date('F Y');
$d=explode(' ',$getDate);
$month =$d[0];
$year =$d[1];
$nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
if($getDate!=isset($_GET['date'])  && date('d')>=26)
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

$querytable= mysqli_query($conn,"SELECT `startdate`,`enddate`,`image`,`reason` from holidays");

$holidays = mysqli_fetch_all($querytable);

$dpdate=date('F Y');
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

            width: 30px;
            height: 30px;
        }
        #calender
        {
            background: transparent;
            border-top: transparent !important;
            border-left: transparent !important;
            border-right: transparent !important;
            border-bottom: transparent !important;
            font-weight: bold;
            color: rebeccapurple;
            padding-left: 0;
        }
    </style>
    <link rel="stylesheet" href="content/font-awesome/css/font-awesome.css">
    <script type="text/javascript">
        $(document).ready(
            $(function() {
                $('.date-picker').datepicker( {
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: true,
                    dateFormat: 'MM yy',
                    onClose: function(dateText, inst) {
                        $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
                        $('#form').submit();
                    }
                });
            }));
    </script>
    <style>
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

    <script>

        var inputname = "";

    </script>


    <?php
    include 'header_hr.php';
    ?>
    <?php echo '<script>var curRecords = '.json_encode($records).'</script>';?>
    <?php echo '<script>var holidays = '.json_encode($holidays).'</script>';?>
    <div class="pageWrapper bg-light h-50">
        <section class="pageContent container">

            <form method="get" id = "form"  >
                <section class="pageTitle row">
                    <div class="col-md-4">
                        <h3 class="d-inline">Employee Record</h3>
                    </div><!-- md12 -->
                </section><!-- Page Title-->
                <div class="form-group col-md-4 monthWrap">
                    <input type="hidden" name="s" value="<?php echo  isset($_GET['s']) ? $_GET['s']:'' ;?>"/>
                    <input id="calender" class="form-control round monthStart dateIcon date-picker" readonly
                           type="text" name="date" value="<?php
                    $d = explode(' ', date('F Y'));
                    $month = $d[0];
                    $year = $d[1];
                    $MstDate1 = date_create(($year . '-' . $nmonth . '-25'));
                    if(date('d')>=26)
                    {
                        date_add($MstDate1, date_interval_create_from_date_string('1 months'));
                        $dpdate = date_format($MstDate1, 'F Y');
                    }
                    echo isset($_GET['date']) ? $_GET['date'] : $dpdate;
                    ?>" onchange="this.form.submit()">
                </div>
            </form>
            <form method="post">
                <section class="">
                    <section class="blockSection ">
                        <div class="table-responsive">

                            <ul class="employeListHeading clearfix">
                                <li class="align-middle">EMPLOYEE NAME</li>
                                <li class="align-middle">EMP ID</li>

                                <?php
                                $getDate=isset($_GET['date']) ? $_GET['date'] : date('F Y');
                                $d=explode(' ',$getDate);
                                $month =$d[0];
                                $year =$d[1];
                                $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                if($getDate!=isset($_GET['date'])  && date('d')>=26)
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
                                    if (isset($_GET['s'])) {
                                        $ename = $_GET['s'];

                                        $query = mysqli_query($conn,
                                            "SELECT e.* from employee as e where e.ename like '%".$ename."%'");
                                    } else {
                                        $query = mysqli_query($conn,
                                            "SELECT employee.*,department.dname FROM employee join department on employee.deptno=department.deptno order by employee.eid") or
                                        die(mysqli_error($conn));
                                    }
                                $query1 = mysqli_query($conn, "SELECT ename FROM employee where ename like '%".$ename."%'") or die(mysqli_error($conn));
                                $row = $query1->fetch_array();
                                $count = $query1->num_rows;
                                if ($count >= 1) {
                                    $sno = 1;
                                    while ($result = mysqli_fetch_array($query)) {
                                        ?>

                                        <ul class="employeeList clearfix">
                                            <li class="bg-white round boxShadow">
                                                <ul class="employeeListDesc clearfix align-middle">
                                                    <li class="employeeImage clearfix"><img
                                                                src=<?php echo $result['eimage']; ?> class="rounded-circle"
                                                                alt=""/><a
                                                                href="attendance-report.php?id=<?php echo $result['eid']; ?>"><label
                                                                    style="cursor:pointer"><?php echo $result['ename']; ?></label></a>
                                                    </li>
                                                    <li class="employeeID"><?php echo $result['eid']; ?><input
                                                                type="hidden" value="<?php echo $result['eid']; ?>"
                                                                name="eid[]"/></li>


                                                    <?php
                                                    $a = ['present', 'absent', 'workingOnSite', 'unPaidLeave', 'weeklyOff',
                                                        'maternityLeave', 'annualLeave', 'compassionate', 'sickLeave',
                                                        'paternityLeave', 'businessTrip'];
                                                    $icon = "";


                                                    $getDate=isset($_GET['date']) ? $_GET['date'] : date('F Y');
                                                    $d=explode(' ',$getDate);
                                                    $month =$d[0];
                                                    $year =$d[1];
                                                    $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                                    if($getDate!=isset($_GET['date'])  && date('d')>=26)
                                                    {
                                                        $nmonth+=1;

                                                    }

                                                    $stdate1 = ($year . '-' . $nmonth . '-' . 26);

                                                    $MstDate1 = date_create($stdate1);
                                                    $MendDate1 = date_create($stdate1);
                                                    date_add($MendDate1, date_interval_create_from_date_string('1 months'));

                                                    $datediff1 = date_diff($MendDate1, $MstDate1)->days;

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
                                                                                onclick="inputname = '<?php echo $result['eid'] . $datee; ?>';"
                                                            ></a><input type="hidden" value="" name="<?php echo 'status'
                                                                . $result['eid'];
                                                            ?>[]" id="<?php echo $result['eid']
                                                                . $datee;
                                                            ?>"/></li>


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
                                else
                                {
                                    echo '<div class="modal" id="myModalsu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Record</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               No Record Found
            </div>
            <div class="modal-footer">
                <form action = "#">

                    <a href="attendance-book-hr.php" class="btn btn-danger px-5 border-0 roundBtn">OK</a>
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
                                ?>
                            </div>
                        </div>
                        <form/>

                        <!-- Page Footer -->
                        <!-- SCRIPTS -->
                        <footer class="pageFooter">
                            <p>Design and Develop by Zoom Arts & Design</p>
                        </footer> <!-- Page Footer -->
</header>

</body>
<!-- SCRIPTS -->


<!-- Include jQuery Popup Overlay -->
<script src="content/js/jquery.popupoverlay.min.js">
</script>

<script>
    $(document).ready(function() {

        // Initialize the plugin
        $('#JPO').popup();

        // Set default `pagecontainer` for all popups (optional, but recommended for screen readers and iOS*)
        $.fn.popup.defaults.pagecontainer = '#page'
    });
</script>
<script src="content/js/moment.js"> </script>
<script>
    $(document).on('click',"#JPO", function(e) {
        $('#JPO').fadeOut();
    });

    $(document).ready(function () {

        curRecords.forEach(function(element) {
            try{
                document.getElementById('a' + element[1] + element[0]).className = 'icon ' + element[2] + 'Icon';
                document.getElementById('a' + element[1] + element[0]).parentNode.className = "JPO_open";


            }
            catch(exception)
            {

            }
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
                        document.getElementsByClassName("a"+moment(startDate).format('YYYY-MM-DD'))[0].parentNode.className = "";
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

</body>
</html>
