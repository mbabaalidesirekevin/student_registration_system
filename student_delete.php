<?php
include 'connect.php';
if(isset($_GET['deleteid'])){
    $id =$_GET['deleteid'];
    //delete query

    $sql = "delete from `studenttable` where student_id  = '$id'";
    $result = mysqli_query($con,$sql);
    if(!$result){
        die(mysqli_error($con));
    }else{
        header('location:students.php');
    }

}

?>