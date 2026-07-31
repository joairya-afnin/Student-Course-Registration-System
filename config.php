<?php
$host="localhost";
$user="root";
$password="JOha123@#!";
$db="student_course_registration";

$conn=mysqli_connect($host,$user,$password,$db);

if(!$conn){
    die("Connection Failed: ".mysqli_connect_error());
}
?>

