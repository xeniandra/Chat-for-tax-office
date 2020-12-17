<?php

session_start();
require_once "connection.php"; //подключение

if (empty($_SESSION['username'])) {
    header('Location: auth.php');
    exit();
}
$SESSIONname = $_SESSION['username'];  

if($resultUser){
header("Location: add.php"); //чтобы после обновления страницы введенные данные не вводились снова и снова
}
?>

<!DOCTYPE html>
<html>
<head>
	<title> Добавление пользователя </title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="styles/add.css">
</head>
<body>
<!-- кнопка "обратно к чату" -->
<div class="backb">
    <a href="chat.php" class="back_to_button"> Назад к чату </a>
</div>

<!-- Аватарка -->
<div class="blok_s_avoy">
    <img src="" class="picture">
</div>

<!--  Форма для заполнения -->
<form action="" class="registracia" method="POST">

    <?php
        //выполняется запись в таблицу бд
        if (isset($_POST['add'])) {

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
        
        //ПРОВЕРКА НА СУЩЕСТВОВАНИЕ ЛОГИНА
        $resultLogin = mysqli_num_rows(mysqli_query($link, "SELECT * FROM `user` WHERE `login` = '$login'"));

        if($resultLogin <= 0) {
        
            //запрос на добавление в базу
            $insert = "INSERT INTO `user` (`id_user`, `surname`, `name`, `fathername`, `picture`, `login`, `password`, `post`, `department`, `email`, `phone`, `id_role`) 
            VALUES (NULL, '$surname', '$name', '$fathername', '$picture', '$login', '$password', '$post', '$department', '$email', '$phone', 2);";
            $resultUser = mysqli_query($link, $insert) or die("Ошибка " . mysqli_error($link));

        }
    }
    ?>

    <h1>Добавить пользователя</h1>

        <?php 
        if ($resultUser) {
            echo "<br><p style='color: green; font-size: larger; background-color: #00000091; padding: 5px; border-radius: 10px;'> Пользователь добавлен. </p>";
        }
        ?> 

    <input type="text" name="surname" placeholder="Фамилия" pattern="[А-Яа-яЁё \-]{2,30}" required>
    <input type="text" name="name" placeholder="Имя" pattern="[А-Яа-яЁё \-]{2,30}" required>
    <input type="text" name="fathername" placeholder="Отчество" pattern="[А-Яа-яЁё \-]{2,30}">
    <input type="text" name="picture" placeholder="Ссылка на изображение" class="pic">
    <input type="text" name="post" placeholder="Должность" required> 
    <input type="text" name="department" placeholder="Отдел" required> 
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="tel" name="phone" placeholder="Номер телефона (должен начинаться с 8, не должен содержать пробелы и другие знаки)" pattern="[8]{1}[0-9]{10}"  required>
    <input type="text" name="login" placeholder="Логин" required>

        <?php
        if($resultLogin > 0) {
        echo "<p style='color: #f00; font-size: medium; background-color: #00000091; padding: 5px; border-radius: 10px;'> Такой логин уже существует! </p>";
        }
        ?>

    <div class="inputpass">
        <input type="password" name="password" class="password" placeholder="Пароль" required>   
        <a href="#" class="passwordOnOff" onclick="return show_hide_password(this);"></a>  
    </div>  

    <input type="submit" value="Добавить" name="add" class="submit">  

        <?php 
            //закрываем подключение
            mysqli_close($link);
        ?> 

</form>

<script type='text/javascript'> 

// Получение аватарки в реальном времени
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