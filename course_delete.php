<?php
include 'connect.php';

if (!isset($_GET['id'])) {
    header('Location: courses.php');
    exit;
}
$id = intval($_GET['id']);

$sql = "DELETE FROM courses WHERE id = $id";
mysqli_query($con, $sql);
header('Location: courses.php');
exit;
