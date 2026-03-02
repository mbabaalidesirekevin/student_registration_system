<?php
//connectingg to the database
$con = new mysqli("localhost","root","","srs");
if(!$con){
    die(mysqli_error($con));
}
?>