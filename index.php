<?php
session_start();

if (empty($_SESSION['username'])) 
{
    header('Location: auth.php');
    exit();
}

else 
{
    header('Location: chat.php');
    exit();
}
