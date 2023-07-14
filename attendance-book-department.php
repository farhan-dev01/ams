<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Attendance System | Attendance Book</title>
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
        <section class="pageTitle row mb-0">
            <div class="col-md-10">
                <h3 class="d-inline">Attendance Book</h3>
                <span>DEPARTMENT</span>
            </div><!-- md12 -->
        </section><!-- Page Title-->
<br><br>
      <section class="blockSection row d-flex align-items-center h-100">
          <div class="col-sm-10 offset-sm-1 col-md-10 offset-md-1 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2" style="height: 400px;">
            <div class="row mb-4" >
              <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                  <div class="d-flex align-items card boxShadow round">
                      <div class="card-body py-4">
                          <img src="content/img/employee.png" class="rounded d-block" alt="" />
                          <div class="form-group text-center">
                              <button onclick="window.location.href='all-employee-list-t.php'" class="btn btn-primary roundBtn" type="button">EMPLOYEE</button>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                  <div class="d-flex align-items card boxShadow round">
                      <div class="card-body py-4">
                          <img src="content/img/mark-attendance.png" class="rounded d-block" alt="" />
                          <div class="form-group text-center">
                              <button onclick="window.location.href='mark-attendance-t.php'" class="btn btn-primary roundBtn" type="button">MARK ATTENDANCE</button>
                          </div>
                      </div>
                  </div>
              </div>
                <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                    <div class="d-flex align-items card boxShadow round">
                        <div class="card-body py-4">
                            <img src="content/img/view-attendance.png" class="rounded d-block" alt="" />
                            <div class="form-group text-center">
                                <button onclick="window.location.href='Records-t.php'" class="btn btn-primary roundBtn" type="button">ATTENDANCE RECORDS</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- ROW -->
          </div>
      </section>

    </section>
  </div><!-- Page Wrapper Ends -->
  <br><br>
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
