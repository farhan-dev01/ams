<?php

require_once 'db_connection.php';
$conn = opencon();
$users_arr;
$departid = $_POST['depart'];
$query = mysqli_query($conn, "SELECT e.* from employee as e join department as d on d.deptno=e.deptno where d.dname='$departid' order by ename");

while ($result = mysqli_fetch_array($query)) {
    $userid = $result['eid'];
    $name = $result['ename'];

    $users_arr[] = array("eid" => $userid, "ename" => $name);

}
// encoding array to json format
echo json_encode($users_arr);

?>