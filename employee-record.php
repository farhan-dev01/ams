<!doctype html>

<?php
require_once 'db_connection.php';
$conn = opencon();
$per_page = 10;
$pages_query = mysqli_query($conn, "select count('eid') from employee");
$records = mysqli_fetch_array($pages_query);
$records = $records[0];
$pages = ceil($records / $per_page);
$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;
$sno = $start + 1;

?>
<?php
require_once 'db_connection.php';
$conn = opencon();
$records;
$holidays;

$getDate=isset($_POST['date']) ? $_POST['date'] : date('F Y');
$d=explode(' ',$getDate);
$month =$d[0];
$year =$d[1];
$nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
if($getDate!=isset($_POST['date'])  && date('d')>=26)
{
    $nmonth+=1;

}

if ($nmonth <= 0) {
    $nmonth = 12;
    $year -= 1;
}
$MstDate1 = date_create(($year . '-' . $nmonth . '-26'));
date_add($MstDate1, date_interval_create_from_date_string('1 months'));
date_sub($MstDate1, date_interval_create_from_date_string('1 days'));
$date = date_format($MstDate1, 'Y-m-d');

require_once 'db_connection.php';
$conn = opencon();
$querytable = mysqli_query($conn, "SELECT a.date,e.eid,a.status from attendance as a join employee as e on a.eid = e.eid JOIN department 
as d on e.deptno = d.deptno where a.date BETWEEN '" . ($year . "-" . ($nmonth)) . "-26' and '$date' order by a.eid,a.DATE ");

$records = mysqli_fetch_all($querytable);
require_once 'db_connection.php';
$conn = opencon();
$querytable = mysqli_query($conn, "SELECT `startdate`,`enddate`,`image`,`reason` from holidays");

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
    <script src="content/js/jquery-1.12.0.min.js"></script>


    <style>
        #pic {

            width: 30px;
            height: 30px;
        }

        #calender {
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
            $(function () {
                $('.date-picker2').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: true,
                    dateFormat: 'MM yy',
                    onClose: function (dateText, inst) {
                        $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
                        // $('#form').submit();
                    }
                });
            }));
        $(document).ready(
            $(function () {
                $('.date-picker').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: true,
                    dateFormat: 'MM yy',
                    onClose: function (dateText, inst) {
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
    <?php echo '<script>var curRecords = ' . json_encode($records) . '</script>'; ?>
    <?php echo '<script>var holidays = ' . json_encode($holidays) . '</script>'; ?>
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
                                    <label for="monthEnd">Month</label>
                                    <input class="form-control round monthEnd dateIcon date-picker2" readonly type="text"
                                           id="monthEnd" name="date" placeholder=""
                                           value="<?php
                                           $d = explode(' ', date('F Y'));
                                           $month = $d[0];
                                           $year = $d[1];
                                           $MstDate1 = date_create(($year . '-' . $nmonth . '-25'));
                                           if(date('d')>=26)
                                           {
                                               date_add($MstDate1, date_interval_create_from_date_string('1 months'));
                                               $dpdate = date_format($MstDate1, 'F Y');
                                           }
                                           echo isset($_POST['date']) ? $_POST['date'] : $dpdate;
                                           ?>">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="depttFilter">Department</label>
                                    <select name="dname" id="department">
                                        <!--                                        <option value="" disable selected> -- Select --</option>-->
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
                                            <option value="" disable="" selected=""> -- Select --</option>
                                        </select>
                                        <div class="select-styled"> -- Select --</div>
                                        <ul class="select-options" style="display: none;">
                                            <li rel=""> -- Select --</li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                            <div class="btnWrap">
                                <button type="button" data-dismiss="filterSection" name="submit" class="btn btn-primary roundBtn
                            applyBtn">Cancel
                                </button>
                                <button type="submit"  onclick="if($('.select-styled:eq(0)').text()!='--Select--'){}
                                else{document.getElementById('calender').value =document.getElementById('monthEnd').value ;$('#form').submit(); return false;}" name="submit" class="btn btn-primary roundBtn applyBtn">Apply
                                </button>

                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- Filter Section -->

            <form method="POST" id="form">
                <section class="pageTitle row">
                    <div class="col-md-4">
                        <h3 class="d-inline">View Attendance</h3>
                    </div><!-- md12 -->

                    <div class="col-md-4">
                    </div>

                    <div class="col-md-4">
                        <a href="javascript:void(0)" class="btn btn-primary roundBtn float-right addEmployee">FILTER</a>
                        <a href="edit-attendance.php" class="btn btn-primary roundBtn float-right addEmployee">EDIT/DELETE</a>
                    </div>
                </section><!-- Page Title-->
                <!--                <div class="monthWrap">-->
                <!--                    <a href="javascript:void(0)"></a>-->
                <!--                    <a href="javascript:void(0)"> --><?php //echo isset($_POST['date']) ? $_POST['date'] : date('F Y'); ?><!--</a>-->
                <!--                    <a href="javascript:void(0)"></a>-->
                <!--                </div>-->
                <div class="form-group col-md-4 monthWrap">
                    <input class="form-control round monthEnd dateIcon date-picker" name="date" readonly
                           type="text"
                           value="<?php
                           $d = explode(' ', date('F Y'));
                           $month = $d[0];
                           $year = $d[1];
                           $MstDate1 = date_create(($year . '-' . $nmonth . '-25'));
                           if(date('d')>=26)
                           {
                               date_add($MstDate1, date_interval_create_from_date_string('1 months'));
                               $dpdate = date_format($MstDate1, 'F Y');
                           }
                           echo isset($_POST['date']) ? $_POST['date'] : $dpdate;
                           ?>"
                           id="calender" placeholder="">
                </div>
            </form>
            <form method="post" action="ExportToExcel/Export.php?year=<?php echo date('Y'); ?>">
                <section class="">
                    <section class="blockSection ">
                        <div class="table-responsive">

                            <ul class="employeListHeading clearfix">
                                <li class="align-middle">EMPLOYEE NAME</li>
                                <li class="align-middle">EMP ID</li>

                                <?php

                                $getDate=isset($_POST['date']) ? $_POST['date'] : date('F Y');
                                $d=explode(' ',$getDate);
                                $month =$d[0];
                                $year =$d[1];
                                $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                if($getDate!=isset($_POST['date'])  && date('d')>=26)
                                {
                                    $nmonth+=1;

                                }

                                $stdate1 = ($year . '-' . $nmonth . '-' . 26);

                                $MstDate1 = date_create($stdate1);
                                $MendDate1 = date_create($stdate1);
                                date_add($MendDate1, date_interval_create_from_date_string('1 months'));

                                $datediff1 = date_diff($MendDate1, $MstDate1)->days;

                                for ($i = 1; $i <= $datediff1; $i++) {
                                    $date = date_format($MstDate1, 'Y-m-d');

                                    echo '<li><label>' . date_format($MstDate1, 'd') . '</label><span>' . date_format
                                        ($MstDate1, 'D') . '</span></li>';

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
                                        $dname = $_POST['dname'];
                                        $query = mysqli_query($conn,
                                            "SELECT employee.*,department.dname from employee join department on employee.deptno=department.deptno where employee.eid like '%" .
                                            $ename . "%' and department.deptno like '%" . $dname . "%' order by employee.eid limit $start,$per_page ");
                                    } else {
                                        $query = mysqli_query($conn,
                                            "SELECT employee.*,department.dname FROM employee join department on employee.deptno=department.deptno order by employee.eid limit $start,$per_page") or
                                        die(mysqli_error($conn));
                                    }
                                    $sno = 1;
                                    while ($result = mysqli_fetch_array($query)) {
                                        ?>

                                        <ul class="employeeList clearfix">
                                            <li class="bg-white round boxShadow">
                                                <ul class="employeeListDesc clearfix align-middle">
                                                    <li class="employeeImage clearfix"><img
                                                                src=<?php echo $result['eimage']; ?> class="rounded-circle"
                                                                alt="" /><label
                                                                class="text-left"><?php echo $result['ename']; ?></label>
                                                    </li>
                                                    <li class="employeeID"><?php echo $result['eid']; ?><input
                                                                type="hidden" value="<?php echo $result['eid']; ?>"
                                                                name="eid[]"/></li>


                                                    <?php
                                                    $a = ['present', 'absent', 'workingOnSite', 'unPaidLeave', 'weeklyOff',
                                                        'maternityLeave', 'annualLeave', 'compassionate', 'sickLeave',
                                                        'paternityLeave', 'businessTrip'];
                                                    $icon = "";


                                                    $getDate=isset($_POST['date']) ? $_POST['date'] : date('F Y');
                                                    $d=explode(' ',$getDate);
                                                    $month =$d[0];
                                                    $year =$d[1];
                                                    $nmonth = date("m", strtotime($year.'-'.$month.'-1'))-1;
                                                    if($getDate!=isset($_POST['date'])  && date('d')>=26)
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

                                                        <li class=""><a id="a<?php echo $result['eid']
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
                                ?>
                            </div>
                        </div>

                        <!--                        <div class="form-group text-center">-->
                        <!--                            <button class="btn btn-primary px-5 border-0 roundBtn" type="sumbit" name="export">Export to Excel-->
                        <!--                            </button>-->
                        <!--                        </div>-->

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
                    <!--                    <a href="ExportToExcel/Export.php?year=-->
                    <?php //echo date('Y'); ?><!--" class="printBtn">Export Data</a>-->
                    <div class="">
                        <ul class="iconDescList clearfix">
                            <li class="presentIcon"><label>Present</label></li>
                            <li class="workingOnSiteIcon"><label>Work On site</label></li>
                            <li class="weeklyOffIcon"><label>Weekly Off</label></li>
                            <li class="annualLeaveIcon"><label>Annual Leave</label></li>
                            <li class="sickLeaveIcon"><label>Sick Leave</label></li>
                            <li class="businessTripIcon"><label>Business Trip</label></li>
                            <li class="absentIcon"><label>Absent</label></li>
                            <li class="unPaidLeaveIcon"><label>Unpaid Leave</label></li>
                            <li class="maternityLeaveIcon"><label>Maternity Leave</label></li>
                            <li class="compassionateIcon"><label>Compassionate</label></li>
                            <li class="paternityLeaveIcon"><label>Paternity Leave</label></li>
                            <li class="publicHolidayIcon"><label>Public Holidays</label></li>
                        </ul>
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
<script src="content/js/jquery.popupoverlay.min.js">
</script>
<script src="content/js/moment.js"></script>
<script>
    $(document).ready(function () {

        // Initialize the plugin
        $('#JPO').popup();

        // Set default `pagecontainer` for all popups (optional, but recommended for screen readers and iOS*)
        $.fn.popup.defaults.pagecontainer = '#page'
    });
</script>
<script>
    $(document).on('click', "#JPO", function (e) {
        $('#JPO').fadeOut();
    });

    $(document).ready(function () {

        curRecords.forEach(function (element) {
            try {
                document.getElementById('a' + element[1] + element[0]).className = 'icon ' + element[2] + 'Icon';
                document.getElementById('a' + element[1] + element[0]).parentNode.className = "JPO_open";


            } catch (exception) {

            }
        });
        holidays.forEach(function (element) {
            try {
                var startDate = new Date(element[0]);
                var endDate = new Date(element[1]);
                var end2 = endDate.setDate(endDate.getDate() + 1);
                var image = element[2];
                var title=element[3];

                while (!moment(startDate).isSame(end2)) {

                    var size = $(".a" + moment(startDate).format('YYYY-MM-DD')).length;
                    var e = document.getElementsByClassName("a" + moment(startDate).format('YYYY-MM-DD'));

                    for (var x = 0; x < size; x++) {
                        document.getElementsByClassName("a" + moment(startDate).format('YYYY-MM-DD'))[0].title = title;
                        document.getElementsByClassName("a" + moment(startDate).format('YYYY-MM-DD'))[0].parentNode.className = "";
                        e[x].style.cssText
                            = "background: url('" + image + "') no-repeat left center; opacity: 2;";

                    }
                    startDate = new Date(moment(startDate).add(1, "days").format('YYYY-MM-DD'));
                }
            } catch (exception) {
                console.log(exception);
            }

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

                        $("#empDiv").append('<select id="emp" name="name" hidden><option value="" disable selected></option>');

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
