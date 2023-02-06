<?php
session_start();
$branch = $_SESSION["branch_workers"];
$fuel = filter_var(trim($_POST ['fuel']),FILTER_SANITIZE_STRING) ;
$volume = filter_var(trim($_POST ['volume']),FILTER_SANITIZE_STRING) ;              //В переменную помещаятся отфильрованное значение

if(mb_strlen($volume) < 1 ) { 
    echo "Введите объем";
    exit();
}

require "../blocks/connect.php"; //Подключение к БД

$sql = "INSERT INTO sales (fuel_sales, branch_sales, volume_sales) 
VALUES( ?,?,?)"; 

$query = $pdo->prepare($sql);

$query->execute(array($fuel, $branch, $volume)); //Внесение данных в БД

$sql = null;
$query = null;


$sql = "UPDATE `leftover` SET `quantity_leftover` = `quantity_leftover` - ? 
WHERE `fuel_leftover` = ? AND `branch_leftover` = ?"; 

$query = $pdo->prepare($sql);

$query->execute(array($volume, $fuel, $branch)); //Внесение данных в БД

$pdo = null;
header('location:../index.php');

?>
