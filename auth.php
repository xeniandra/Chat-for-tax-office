<?php
session_start();
require_once 'connection.php';


if (isset($_SESSION['username'])) 
{
        $login = $_SESSION['username'];
        $query_role = "SELECT id_role FROM user WHERE login = '$login'";
        $result_role = mysqli_query($link, $query_role);
        $role_data = mysqli_fetch_row($result_role);
        $role = $role_data[0];

        if ($role == 2) 
        {
            header('Location: chat.php');
            exit();
        }

        if ($role == 1) 
        {
            header('Location: chat.php');
            exit();
        }
    }

/* СКРИПТ АВТОРИЗАЦИИ */

$login = $_POST['auth-login'];
$password = $_POST['auth-pass'];
$button_auth = $_POST['auth-button'];

$query = "SELECT `login`, `password` FROM `user`";
$result = mysqli_query($link, $query);

while ($row = mysqli_fetch_row($result)) {
  if (($login == $row[0]) and ($password == $row[1])) 
  {
    session_start();
    $_SESSION['username'] = $row[0];
    header('Location: auth.php');
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="styles/auth.css">
  <meta charset="utf-8">
	<title> Авторизация </title>
</head>
<body>

  <div class="form">
    <form class="login-form" method="POST">
      <?php
        if (isset($_POST['auth-button'])) {

        //переменные с формы
        $login = $_POST['login'];
        $password = $_POST['password'];
        $resultLogin = mysqli_num_rows(mysqli_query($link, "SELECT `login`, `password` FROM `user` WHERE `login` = '$login'"));
        $resultPassword = mysqli_num_rows(mysqli_query($link, "SELECT `password` FROM `user` WHERE `password` = '$password'"));
                if((!$resultLogin) || (!$resultPassword)) {
                    echo "<p style='
                      align-items: center; 
                      color: #f33; 
                      font-weight: bolder; 
                      background-color: white; 
                      padding: 2%; 
                      border-radius: 5px;
                      border: 1px solid #ff4d4d;'> Неправильный логин или пароль! </p>";
                }  
        }
      ?>

      <input type="text" name="auth-login" required placeholder="Ваш логин..."/>
      <input type="password" name="auth-pass" required placeholder="Ваш пароль..."/>
      <input type="submit" name="auth-button" required class="voiti" value="ВОЙТИ">

    </form>
  </div>
</body>
</html>