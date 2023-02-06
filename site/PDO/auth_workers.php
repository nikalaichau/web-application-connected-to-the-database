<?php
session_start();
$id = filter_var(trim($_POST ['id']),FILTER_SANITIZE_STRING) ;
$password = filter_var(trim($_POST ['password']),FILTER_SANITIZE_STRING) ;



require "../blocks/connect.php"; //Подключение к БД

$query = $pdo->query("SELECT * FROM `workers` WHERE `id_workers` = '$id' AND `pass_workers` = '$password'");
$user = $query->fetch(PDO::FETCH_ASSOC); //Преобразование объекта в массив 


if(mb_strlen($id) < 1 ) { 
    echo "Введите id";
    exit();
}
$_SESSION['position_workers']=$user['position_workers'];
$_SESSION['branch_workers']=$user['branch_workers'];

$pdo = null;
$user = null;
$query = null;

header('location:../index.php');


?>
