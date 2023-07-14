
<?php
function opencon(){
//
//$host="localhost";
//$user="bytecrea_zoom";
//$pass="Zoomdb123;";
//$db="bytecrea_zoomdb";
//$conn=new mysqli($host,$user,$pass,$db) or die("Unable to connect");

    $conn=new mysqli("localhost","root","","ams") or die("Unable to connect");
return $conn;
}
function closecon(){
	//$conn->close();
}
?>
