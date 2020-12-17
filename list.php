<?php
session_start();
require_once "connection.php"; //подключение

if (empty($_SESSION['username'])) {
    header('Location: auth.php');
    exit();
}
$SESSIONname = $_SESSION['username'];


if (isset($_SESSION['username'])) {
        $login = $_SESSION['username'];
        $query_role = "SELECT id_role FROM user WHERE login = '$SESSIONname'";
        $result_role = mysqli_query($link, $query_role);
        $role_data = mysqli_fetch_row($result_role);
        $role = $role_data[0];
}
?>

<!DOCTYPE html>
<html>
<head>
	<title> Список пользователей </title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="styles/list.css">
</head>
<body>
<!-- кнопка "обратно к чату" -->
<div class="backb">
  <a href="chat.php" class="back_to_button"> Назад к чату </a>   
</div>

<!-- Информация о пользователе -->
<div class="other">

    <div class='infa'>
      <img class="picture">
      <ul>
        <li class="people"></li>
        <li class="post"></li>
        <li class="department"></li>
        <li class="email"></li>
        <li class="phone"></li>
      </ul>
    </div> 

<!-- Список пользователей -->
    <div class="dobavit"> 
      <h1>Список пользователей</h1>

        <form method="GET" action="">
          <input type="text" placeholder="Поиск..." name="spisok_search" class="search" required>
          <input type="submit" value="" class="otpravit"> </input>
        </form>

          <?php
            $query = "SELECT `surname`, `name`, `fathername`, `picture`, `id_user`, `post`, `department`, `email`, `phone`, `id_role` 
                      FROM `user` WHERE `id_role` = 2";

            if(isset($_GET['spisok_search']))
            {   
              $fio = $_GET['spisok_search'];
              $fio = preg_replace('/ /', '', $fio);
              $query = "SELECT `surname`, `name`, `fathername`, `picture`, `id_user`, `post`, `department`, `email`, `phone` 
                        FROM user WHERE (concat(`surname`, `name` ,`fathername`) LIKE '%$fio%') AND (`id_role` = 2)";

              if (mysqli_num_rows(mysqli_query($link, $query)) <= 0){
                echo "<p style='color: #4f4d63; font-size: larger; '>Пользователь не найден!</p><br><br><br>";
              }  

              echo "<a href='list.php' class='show'> Показать всех пользователей </a>";
            }
              
            $row = mysqli_query($link, $query);
            while ($row_data = mysqli_fetch_array($row)) {
              $result1 = $row_data[0]; 
              $result2 = $row_data[1]; 
              $result3 = $row_data[2];

                echo "	<div class='msg'>";
                    echo "<p class='people'> $result1 $result2 $result3 </p>";
                    echo "<p style='display: none' class='post'> $row_data[5] </p>";
                    echo "<p style='display: none' class='department'> $row_data[6] </p>";
                    echo "<p style='display: none' class='email'> $row_data[7] </p>";
                    echo "<p style='display: none' class='phone'> $row_data[8] </p>";
                    echo "<p style='display: none' class='img'> $row_data[3] </p>";
                  
                    if ($role == 1) {
                      echo "<a href='delete.php?id=".$row_data[4]."'>удалить</a>";
                      echo "<a href='change.php?id=".$row_data[4]."'>изменить</a>";
                }echo " </div>";
            }
          ?>
    </div>
</div>

         
  <script>
     //По клику показывать блок с инфой о юзере
		document.querySelectorAll('.msg').forEach(function(element)
		{
			element.querySelector('.people').addEventListener('click', () =>
			{
				document.querySelector('.infa').style.display = 'flex';
				document.querySelector('.infa .people').textContent = element.querySelector('.people').textContent;
				document.querySelector('.infa .post').textContent = 'Должность:' + element.querySelector('.post').textContent;
				document.querySelector('.infa .department').textContent = 'Отдел:' + element.querySelector('.department').textContent;
				document.querySelector('.infa .email').textContent = 'Электронная почта:' + element.querySelector('.email').textContent;
				document.querySelector('.infa .phone').textContent = 'Телефон:' + element.querySelector('.phone').textContent;
				document.querySelector('.infa img').src = element.querySelector('.img').textContent;
			});
		});
  </script>

</body>
</html>