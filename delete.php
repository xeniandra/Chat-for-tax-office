<?php
session_start();
require_once "connection.php"; //подключение

if(isset($_GET['id']))
{   
    echo $_GET['id'];
    $id_user = $_GET['id'];
    $del = "DELETE FROM `user` WHERE `id_user` = '$id_user'";
    $resultDel = mysqli_query($link, $del) or die("Ошибка " . mysqli_error($link));
    if ($resultDel) {
    	echo "<script> location.href = 'list.php'</script>";
    }   
}
