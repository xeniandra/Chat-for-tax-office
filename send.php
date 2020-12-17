<?
// Фильтр мата
// https://github.com/vearutop/php-obscene-censor-rus
namespace Wkhooy;
include 'lib/ObsceneCensorRus.php';
// https://github.com/vearutop/php-obscene-censor-rus

require_once "connection.php";


session_start();
$message = $_GET['chatMsg'];

if (isset($message))
{
    $name = $_SESSION['username'];
    $message = htmlentities(mysqli_real_escape_string($link, $message));
    $new_message = ObsceneCensorRus::getFiltered($message);

    $query_id = "SELECT id_user FROM user WHERE login = '$name'";
    $row_id = mysqli_query($link, $query_id);
    $row_data_id = mysqli_fetch_row($row_id);
    $result_name_id = $row_data_id[0];

    $query = "INSERT INTO `message` (`id_message`, `text`, `date`, `id_user`) 
              VALUES (NULL, '$new_message', NOW(), '$result_name_id');";
    $result = mysqli_query($link, $query);

    mysqli_close($link);
    header('Location: auth.php');
    exit();
}
