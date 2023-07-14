<?php
session_start();
if(!isset($_SESSION['login_id2']))
{echo '<script>
window.location.assign("index.php");
</script>';}
if(isset($_SESSION['login_id2']) ){
    $user_id = $_SESSION['login_id2'];
}
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    echo '<script>alert("Your session has expired");
window.location.assign("index.php");
</script>';
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
?>