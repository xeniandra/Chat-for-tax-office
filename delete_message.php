<?php
session_start();
require_once "connection.php"; //подключение

if(isset($_GET['id'])) //удалить конкретное сообщение
{   
    $id_mess = $_GET['id'];
    $del = "DELETE FROM `message` WHERE `id_message` = '$id_mess'";
    $resultDel = mysqli_query($link, $del) or die("Ошибка " . mysqli_error($link));
    if ($resultDel) {
    	header('Location: chat.php');
    }   
}

if(isset($_GET['del_all_mess'])) //кнопка очистить чат
{   
    $del2 = "DELETE FROM `message`";
    $resultDel2 = mysqli_query($link, $del2) or die("Ошибка " . mysqli_error($link));
    if ($resultDel2) {
    	header('Location: chat.php');
    } 
}
