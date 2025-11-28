<?php 

$id = $_GET['id'];
$status = $_GET['status'];

include '../connect.php';

$sql = "update orders set status = '$status' where id = '$id'";

mysqli_query($connect, $sql);

header("Location: index.php");

exit();