<?php

include('DataBase.php');
include('User.php');
$dbb = new DataBase();
session_start();


// Include the database configuration file  
// Get image data from database 
$id = $_POST['id'];
$userid = getUserId($_SESSION['username']);
$sql = "SELECT * FROM `{$userid}files` WHERE FileId ='{$id}'"; // 1
$res = $dbb->sqli()->query($sql);
while($row = $res->fetch_assoc())
{ 
    $type = pathinfo($row['TheFileName'], PATHINFO_EXTENSION);
    $name = $row['TheFileName'];
    $size =  $row['FileSize'];
    $image = $row['FileData'];
}
header("Content-type: ".$type);
header('Content-Disposition: attachment; filename="'.$name.'"');
header("Content-Transfer-Encoding: binary"); 
header('Expires: 0');
header('Pragma: no-cache');
header("Content-Length: ".$size);

echo $image;
exit();

