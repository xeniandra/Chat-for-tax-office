<?php

session_start();
require_once "connection.php"; //подключение

if (empty($_SESSION['username'])) {
    header('Location: auth.php');
    exit();
}
$SESSIONname = $_SESSION['username'];

//переменные с формы
$surname = $_POST['surname'];
$name = $_POST['name'];
$fathername = $_POST['fathername'];
$phone = $_POST['phone'];
$post = $_POST['post'];
$department = $_POST['department'];
$email = $_POST['email'];
$login = $_POST['login'];
$password = $_POST['password'];

if ($resultUser) {
    header("Location: change.php"); //чтобы после обновления страницы введенные данные не вводились снова и снова
}
?>

<!DOCTYPE html>
<html>
<head>
	<title> Изменение пользователя </title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="styles/add.css">
</head>
<body>
<!-- кнопка "обратно к чату" -->
<div class="backb">
    <a href="chat.php" class="back_to_button" style="margin-bottom: 10px;"> Назад к чату </a>
    <a href="list.php" class="back_to_button"> К списку пользователей </a>
</div>

<!-- Аватарка -->
<div class="blok_s_avoy">
    <img src="" class="picture">
</div>

<!--  Форма для заполнения -->
<form action="" class="registracia" method="POST">

    <?php
    $getId = $_GET['id'];

    $select = "SELECT
                    `id_user`, `surname`, `name`, `fathername`, `picture`, 
                    `login`, `password`, `post`, `department`, `email`, `phone`
                FROM
                    user
                WHERE `id_user` = '$getId';";

    $resultSelect = mysqli_query($link, $select);
    $SelectRow = mysqli_fetch_assoc($resultSelect);
    $SelectRow_surname = $SelectRow['surname'];
    $SelectRow_name = $SelectRow['name'];
    $SelectRow_fathername = $SelectRow['fathername'];
    $SelectRow_picture = $SelectRow['picture'];
    $SelectRow_login = $SelectRow['login'];
    $SelectRow_password = $SelectRow['password'];
    $SelectRow_post = $SelectRow['post'];
    $SelectRow_department = $SelectRow['department'];
    $SelectRow_email = $SelectRow['email'];
    $SelectRow_phone = $SelectRow['phone'];
    ?>
    
    <h1>Изменить пользователя</h1>
    <input type="text" value="<?php echo $SelectRow_surname; ?>" name="surname" placeholder="Фамилия" pattern="[А-Яа-яЁё \-]{2,30}" required>
    <input type="text" value="<?php echo $SelectRow_name; ?>" name="name" placeholder="Имя" pattern="[А-Яа-яЁё \-]{2,30}" required>
    <input type="text" value="<?php echo $SelectRow_fathername; ?>" name="fathername" placeholder="Отчество" pattern="[А-Яа-яЁё \-]{2,30}">
    <input type="text" value="<?php echo $SelectRow_picture; ?>" name="picture" placeholder="Ссылка на изображение" class="pic">
    <input type="text" value="<?php echo $SelectRow_post; ?>" name="post" placeholder="Должность" required>
    <input type="text" value="<?php echo $SelectRow_department; ?>" name="department" placeholder="Отдел" required>
    <input type="email" value="<?php echo $SelectRow_email; ?>" name="email" placeholder="E-mail" required>
    <input type="tel" value="<?php echo $SelectRow_phone; ?>" name="phone" placeholder="Номер телефона (должен начинаться с 8, не должен содержать пробелы и другие знаки)" pattern="[8]{1}[0-9]{10}"  required>
    <input type="text" value="<?php echo $SelectRow_login; ?>" name="login" placeholder="Логин" required>

    <?php
    // ИЗМЕНИТЬ ПОЛЬЗОВАТЕЛЯ
    if (isset($_POST['change'])) {

        //переменные с формы
        $surname = $_POST['surname'];
        $name = $_POST['name'];
        $fathername = $_POST['fathername'];
        $picture = $_POST['picture'];
        $phone = $_POST['phone'];
        $post = $_POST['post'];
        $department = $_POST['department'];
        $email = $_POST['email'];
        $login = $_POST['login'];
        $password = $_POST['password'];

        $resultLogin = mysqli_num_rows(mysqli_query($link, "SELECT * FROM user WHERE login = '$login' AND NOT id_user = '$getId'"));
        if ($resultLogin) {
            echo "<p style='color: #f00; font-size: medium; background-color: #00000091; padding: 5px; border-radius: 10px;'> Такой логин уже используется! </p>";
        }
        else{
            //запрос на изменение в базу
            $insert = "UPDATE `user` SET `surname` = '$surname', `name` = '$name', `fathername` = '$fathername', 
                        `picture` = '$picture', `login` = '$login', `password` = '$password', `post` = '$post', 
                        `department` = '$department', `email` = '$email', `phone` = '$phone'
                        WHERE `id_user` = '$getId';";
            $resultUser = mysqli_query($link, $insert) or die("Ошибка " . mysqli_error($link));

            if ($SelectRow_login == $SESSIONname) //если человек изменяет сам себя, то он перебрасывается на вход
            {
                echo "<script> location.href = 'logout.php'</script>";
            } else {
                echo "<script> location.href = 'change.php?id=" . $getId . "'</script>";
            }
        }
    }
    //закрываем подключение
    mysqli_close($link);
    ?>
        
    <div class="inputpass">
        <input type="password" value="<?php echo $SelectRow_password; ?>" class="password" name="password" placeholder="Пароль" required>
        <a href="#" class="passwordOnOff" onclick="return show_hide_password(this);"></a>
    </div>
    <input type="submit" value="Применить" name="change" class="submit">
</form>

<script type='text/javascript'>

// Получение аватарки в реальном времени
		document.querySelector('.picture').src = document.querySelector('input.pic').value;
        document.querySelector('input.pic').addEventListener('input', function()
        {
            document.querySelector('.picture').src = document.querySelector('input.pic').value;
        });

// Показать - скрыть пароль
        function show_hide_password(target){
            var input = document.querySelector('.password');
            if (input.getAttribute('type') == 'password') {
                target.classList.add('view');
                input.setAttribute('type', 'text');
            } else {
                target.classList.remove('view');
                input.setAttribute('type', 'password');
            }
            return false;
        }

</script>

</body>
</html>