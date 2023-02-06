<?php session_start(); ?>
<?php
$phone = filter_var(trim($_POST ['phone']),FILTER_SANITIZE_STRING) ;
$password = filter_var(trim($_POST ['password']),FILTER_SANITIZE_STRING) ;

$password = md5($password); //Хэширование пароля

require "../blocks/connect.php"; //Подключение к БД

$query = $pdo->query("SELECT * FROM `clients` WHERE `phone_clients` = '$phone' AND `pass_clients` = '$password'");
$user = $query->fetch(PDO::FETCH_ASSOC); //Преобразование объекта в массив 

if (!$user) {
echo "Пользователь не найден, зарегестрируйтесь";
exit();
}
if(mb_strlen($phone) < 1 ) { 
    echo "Введите телефон";
    exit();
}
$_SESSION["id_clients"]=$user['id_clients'];

$pdo = null;
$user = null;
$query = null;

header('location:../index.php');
?>