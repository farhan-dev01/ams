<?php
include 'teamhead_global.php';
require_once 'db_connection.php';
$conn=opencon();
$result = mysqli_query($conn, "SELECT t.*,d.* FROM teamhead_user as t join department as d on t.deptno=d.deptno WHERE username='$user_id'");
while ($row = mysqli_fetch_array($result)) {
    $dept=$row['deptno'];
    $dname= $row['dname'];
    ?>
<?php }
$conn=opencon();
$r = mysqli_query($conn, "SELECT GROUP_CONCAT(ename) as names FROM `employee` where deptno='$dept' ");
if($row = mysqli_fetch_array($r)) {

    $a = $row[0];

}
?>
<!--        <script src="//code.jquery.com/jquery-2.1.4.min.js"></script>-->
    <script type="text/javascript" src="content/typeahead.js"></script>
    <link href="content/typeahead.css"  rel="stylesheet" />
    <script type="text/javascript" rel="stylesheet">
        $(document).ready(function(){
            <?php
            echo 'var countries = "'.$a.'".split(",");';
            ?>
            var countries_suggestion = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.whitespace,
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                local: countries
            });

            $('.typeahead').typeahead(
                { minLength: 1 },
                { source: countries_suggestion }
            );
            $('.typeahead').on('typeahead:selected', function(evt, item) {
                $('#search').val(item);
                $('.headerSearchForm').submit();
            });

            $(document).keyup(function(event) {
                if (event.keyCode == 13) {
                    $('.headerSearchForm').submit();
                }
            });
        });
    </script>
    <style>
        .typeahead { border: 1px solid #CCCCCC;border-radius: 4px;padding: 8px 12px;width: 300px;}
        .tt-menu { width:300px; }
        span.twitter-typeahead .tt-suggestion {padding: 10px 20px;	border-bottom:#CCC 1px solid;cursor:pointer;}
        span.twitter-typeahead .tt-suggestion:last-child { border-bottom:0px; }
        .bgColor {max-width: 440px;height: 200px;background-color: #c3e8cb;padding: 40px 70px;border-radius:4px;margin:20px auto;}
        .demo-label {font-size:1.5em;color: #686868;font-weight: 500;}
    </style>
    <div >
        <nav id="mp-menu" class="mp-menu">
            <div class="mp-level">
                <a href="" class="menuLogo d-block"><img src="content/img/zoomWhite.png" class="img-responsive" alt="" /></a>
                <ul>
                    <li class="icon"><a class="icon" href="attendance-book-department.php">Dashboard</a></li>
                    <li class="icon"><a class="icon" href="profile-t.php?uid=<?php echo $user_id;?>">Profile</a></li>
                    <li class="icon">
                        <a class="icon" href="#">Employee</a>
                        <div class="mp-level">
                            <a href="" class="menuLogo d-block"><img src="content/img/zoomWhite.png" class="img-responsive" alt="" /></a>
                            <a class="mp-back" href="#">back</a>
                            <ul>
                                <li><a href="add-employee-t.php">Add Employee</a></li>
                                <li><a href="all-employee-list-t.php">All Employee</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="icon">
                        <a class="icon" href="#">Attendance</a>
                        <div class="mp-level">
                            <a href="" class="menuLogo d-block"><img src="content/img/zoomWhite.png" class="img-responsive" alt="" /></a>
                            <a class="mp-back" href="#">back</a>
                            <ul>
                                <li><a href="mark-attendance-t.php">Mark Attendance</a></li>
                                <li><a href="employee-record-t.php">View Attendance</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="icon">
                        <a class="icon" href="#">Reports</a>
                        <div class="mp-level">
                            <a href="" class="menuLogo d-block"><img src="content/img/zoomWhite.png" class="img-responsive" alt="" /></a>
                            <a class="mp-back" href="#">back</a>
                            <ul>
                                <li><a href="Records-t.php">Attendance Report</a></li>
                                <li><a href="attendance-report-t.php">Employee Report</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </nav><!-- /mp-menu -->
    </div><!-- /pusher -->
<!--    style="height:70px; position: -ms-device-fixed"-->

    <header class="pageHeader bg-white clearfix"  >
        <p class="float-left"><a href="#" id="trigger" class="menu-trigger"></a></p>
        <a href="" class="pageLogo float-left"><img src="content/img/zoom.png" class="img-responsive d-block" alt="" /></a>
        <div class="container">
            <div class="row">
                <form class="col-md-4 col-lg-4 col-xl-4 headerSearchForm" method="get">
                    <div class="form-group mb-0">
                        <input class="form-control bg-white light border-0 formField typeahead" id="search" type="text" name="search2" placeholder="Search Peoples" />
                    </div>
                </form>
                <div class="col-md-3 offset-md-5 col-lg-3 offset-lg-5 col-xl-3 offset-xl-5  headerBtnWrapper">
                    <a href="attendance-book-department.php" class="btn btn-primary roundBtn float-left">Dashboard</a>
                    <a href="logout.php" class="float-right logOutLink">Log out</a>
                </div>
            </div>
        </div>
    </header>
<?php
if(isset($_GET['search2']))
{
    $search2= addslashes($_GET['search2']);

    echo '<script type="text/javascript">window.location.assign("emp-record-t.php?s2='.$search2.'");</script>';
}
?>