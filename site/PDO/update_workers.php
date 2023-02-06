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

$sql = "UPDATE `branch` SET `quantity_workers_branch`=(SELECT COUNT(*) FROM `workers` WHERE branch_workers = 1) WHERE `id_branch` = 1;
        UPDATE `branch` SET `quantity_workers_branch`=(SELECT COUNT(*) FROM `workers` WHERE branch_workers = 2) WHERE `id_branch` = 2;
		UPDATE `branch` SET `quantity_workers_branch`=(SELECT COUNT(*) FROM `workers` WHERE branch_workers = 3) WHERE `id_branch` = 3;
		UPDATE `branch` SET `quantity_workers_branch`=(SELECT COUNT(*) FROM `workers` WHERE branch_workers = 4) WHERE `id_branch` = 4;";
$query = $pdo->prepare($sql);
$query->execute(); //Обновление данных в БД

$sql = null;
$query = null;
$pdo = null;

header('location:../index.php');      