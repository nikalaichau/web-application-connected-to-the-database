<?php
session_start();
$id = $_SESSION["id_clients"];
$name = filter_var(trim($_POST ['name']),FILTER_SANITIZE_STRING) ;              //В переменную помещаятся отфильрованное значение
$surname = filter_var(trim($_POST ['surname']),FILTER_SANITIZE_STRING) ;
$middlename = filter_var(trim($_POST ['middlename']),FILTER_SANITIZE_STRING) ;
$phone = filter_var(trim($_POST ['phone']),FILTER_SANITIZE_STRING) ;
$password = filter_var(trim($_POST ['password']),FILTER_SANITIZE_STRING) ;
$address = filter_var(trim($_POST ['address']),FILTER_SANITIZE_STRING) ;
$birthdate = filter_var(trim($_POST ['birthdate']),FILTER_SANITIZE_STRING) ;
$fuel_client = filter_var(trim($_POST ['fuel']),FILTER_SANITIZE_STRING) ;

$password = md5($password);

require "../blocks/connect.php"; //Подключение к БД

$sql = "UPDATE clients SET name_clients=?, surname_clients=?, middlename_clients=?, phone_clients=?, pass_clients=?, address_clients=?, birthdate_clients=?, fuel_client=?  WHERE `id_clients` = '$id'"; 

$query = $pdo->prepare($sql);

$query->execute(array($name, $surname, $middlename, $phone, $password, $address, $birthdate, $fuel_client)); //Внесение данных в БД

$sql = null;
$query = null;
$pdo = null;

header('location:../index.php');              
?>  