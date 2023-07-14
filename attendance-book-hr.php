<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Dashboard</title>
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
<header style="
    background-color: ghostwhite;">
       <?php include 'header_hr.php'; ?>

    <section class="pageContent container h-75">
        <section class="pageTitle row mb-0">
            <div class="col-md-10">
                <h3 class="d-inline">Attendance Book</h3><br><br><br><br><br><br>
                <!-- <span>DEPARTMENT</span> -->
            </div><!-- md12 -->
        </section><!-- Page Title-->

      <section class="blockSection row d-flex align-items-center h-100">
          <div class="col-sm-10 offset-sm-1 col-md-10 offset-md-1 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
            <div class="row mb-4">
              <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                  <div class="d-flex align-items card boxShadow round">
                      <div class="card-body py-4">
                          <img src="content/img/employee.png" class="rounded mx-auto d-block mb-4" alt="" />
                          <div class="form-group text-center">
                              <button onclick="window.location.href='all-employee-list.php'" class="btn btn-primary roundBtn" type="button">EMPLOYEE</button>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                  <div class="d-flex align-items card boxShadow round">
                      <div class="card-body py-4">
                          <img src="content/img/mark-attendance.png" class="rounded mx-auto d-block mb-4" alt="" />
                          <div class="form-group text-center">
                              <button onclick="window.location.href='mark-attendance.php'" class="btn btn-primary roundBtn" type="button" id="markAttendance">ATTENDANCE</button>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                  <div class="d-flex align-items card boxShadow round">
                      <div class="card-body py-4">
                          <img src="content/img/add-department.png" class="rounded mx-auto d-block mb-4" alt="" />
                          <div class="form-group text-center">
                              <button onclick="window.location.href='all-departments.php'" class="btn btn-primary roundBtn" type="button">DEPARTMENT</button>
                          </div>
                      </div>
                  </div>
                </div>
            </div><!-- ROW -->
          
            <div class="row mb-4">
                <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                    <div class="d-flex align-items card boxShadow round">
                        <div class="card-body py-4">
                            <img src="content/img/add-holiday.png" class="rounded mx-auto d-block mb-4" alt="" />
                            <div class="form-group text-center">
                                <button onclick="window.location.href='all-holiday.php'" class="btn btn-primary roundBtn" type="button">HOLIDAYS</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                    <div class="d-flex align-items card boxShadow round">
                        <div class="card-body py-4">
                            <img src="content/img/view-attendance.png" class="rounded mx-auto d-block mb-4" alt="" />
                            <div class="form-group text-center">
                                <button onclick="window.location.href='Records.php'" class="btn btn-primary roundBtn" type="button">ATTENDANCE RECORDS</button>
                            </div>
                        </div>
                    </div>
                </div>
              <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                  <div class="d-flex align-items card boxShadow round">
                      <div class="card-body py-4">
                          <img src="content/img/employee.png" class="rounded mx-auto d-block mb-4" alt="" />
                          <div class="form-group text-center">
                              <button onclick="window.location.href='users_teamhead.php'" class="btn btn-primary roundBtn" type="button">USERS</button>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </section>

    </section>


  </div><!-- Page Wrapper Ends -->
    <footer class="pageFooter">
        <p>Design and Develop by Zoom Arts & Design</p>
    </footer> <!-- Page Footer -->
</header>
    <!-- Page Footer -->
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
