<?php
$host="localhost";
$user="root";
$pass="";
$db="smart_assessment";

$con=new mysqli($host,$user,$pass,$db);

if($con->connect_error){
    die("Connection failed");
}
?>