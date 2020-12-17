<?php
session_start();
require_once 'connection.php';

if (empty($_SESSION['username'])) {
    header('Location: auth.php');
    exit();
}
$SESSIONname = $_SESSION['username'];  

include "send.php";

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
	<title> Чат для налоговой</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="styles/chat.css">
</head>
<body>

<!-- Текущий пользователь с которого был совершен вход -->
<div class="box_1">
	<div class="block">
		<?php
            $query = "SELECT `login`, `surname`, `name`, `fathername`, `picture` FROM user WHERE `login` = '$SESSIONname'";
            $row = mysqli_query($link, $query);
            $row_data = mysqli_fetch_row($row);
			$result1 = $row_data[1]; 
			$result2 = $row_data[2]; 
			$result3 = $row_data[3]; 
			$result4 = $row_data[4];
		?>

		<img src="<?php echo "$result4"; ?>" class="picture">

		<?php
            echo "<p class='name' style='color: white; font-weight: bolder;'> $result1 $result2 $result3 </p><br>";
		?>

		<form id="log-out" method="GET" action="logout.php">
            <input type="submit" name="exit-button" class="pic" value="Выход">
        </form>
	</div>

	<div class="block_2">
		<a href="list.php"> Список пользователей </a>
		<?php if ($role == 1) {
		echo '<a href="add.php" class="left"> Добавить пользователя </a>';
		echo '<form method="GET" action="delete_message.php">';
		echo '<input type="submit" value="Очистить чат" name="del_all_mess" class="left"> </input>
		</form>';		
		}
		?>

    </div>
</div>

<!-- Панель чата  -->  
<div class="box_2">
	<div class="messages">

            <?php
            
            $query = "SELECT `surname`, `name`, `fathername`, `picture`, `date`, `text`, 
						user.id_user, message.id_message, `post`, `department`, `email`, `phone`, `login`
						FROM `user`, `message` 
						WHERE user.id_user = message.id_user 
						ORDER BY `id_message`";

			$result = mysqli_query($link, $query);
			echo "<div class='message'>";
			
				if ($result) {
					while ($row_data = mysqli_fetch_row($result)) {
						echo "<div class='msg'>";
							echo "<a href='#' class='nameChat'> $row_data[0] $row_data[1] $row_data[2] </a> 
								<span class ='date' style='color: #312f2fa6;'> $row_data[4] </span>";
							echo "<p style='display: none;' class='id'> $row_data[6] </p>";		
							echo "<p class='text'> $row_data[5] </p>";
							if ($role == 1)
							{
								echo "<a href='delete_message.php?id=".$row_data[7]."' class='delete'> удалить </a>";
							}
							echo "<p style='display: none' class='post'> $row_data[8] </p>";
							echo "<p style='display: none' class='department'> $row_data[9] </p>";
							echo "<p style='display: none' class='email'> $row_data[10] </p>";
							echo "<p style='display: none' class='phone'> $row_data[11] </p>";
							echo "<p style='display: none' class='img'> $row_data[3] </p>";
						echo "</div>";
					}
					mysqli_free_result($result);
				}

            echo "</div>";
            ?>

	</div>

	<form method="GET" action="send.php">
		<textarea placeholder="Введите сообщение..." name="chatMsg" rows="5" class="vvodite" maxlength="300" required></textarea>
		<input type="submit" value="Отправить" class="otpravit"> </input>
	</form>
</div>

<!-- Информация о пользователе -->
<div class="box_3">
	<div class='infa'>
		<img src="" class="picture">
		<ul>
			<?php
				if ($role == 1){ //админ
					echo "	<a href='' class='name'> ".$row_data[0]." ".$row_data[1]." ".$row_data[2]."</a>";
				}
				if ($role == 2){
					echo '<li class="name" style="text-decoration: none;"></li>';
				}
			?>	

			<li class="post"></li>
			<li class="department"></li>
			<li class="email"></li>
			<li class="phone"></li>
		</ul>
	</div>
</div>

	<script  type="text/javascript">

// для скрола к последнему сообщению
        var messages = document.querySelector('div.messages');
        messages.scrollTop = messages.scrollHeight;


//По клику показывать блок с инфой о юзере
		document.querySelectorAll('.msg').forEach(function(element)
		{
			element.querySelector('.nameChat').addEventListener('click', () =>
			{
				document.querySelector('.infa').style.display = 'flex';
				document.querySelector('.infa .name').textContent = element.querySelector('.nameChat').textContent;
				document.querySelector('.infa .post').textContent = 'Должность:' + element.querySelector('.post').textContent;
				document.querySelector('.infa .department').textContent = 'Отдел:' + element.querySelector('.department').textContent;
				document.querySelector('.infa .email').textContent = 'Электронная почта:' + element.querySelector('.email').textContent;
				document.querySelector('.infa .phone').textContent = 'Телефон:' + element.querySelector('.phone').textContent;
				document.querySelector('.infa img').src = element.querySelector('.img').textContent;
				document.querySelector('a.name').href = "change.php?id=" + element.querySelector('p.id').textContent.trim();//trim для того чтобы в id не было пробела
			});
		});

    </script>
</body>
</html>