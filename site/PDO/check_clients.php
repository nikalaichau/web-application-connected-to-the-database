<?php
$name = filter_var(trim($_POST ['name']),FILTER_SANITIZE_STRING) ;              //В переменную помещаятся отфильрованное значение
$surname = filter_var(trim($_POST ['surname']),FILTER_SANITIZE_STRING) ;
$middlename = filter_var(trim($_POST ['middlename']),FILTER_SANITIZE_STRING) ;
$phone = filter_var(trim($_POST ['phone']),FILTER_SANITIZE_STRING) ;
$password = filter_var(trim($_POST ['password']),FILTER_SANITIZE_STRING) ;
$address = filter_var(trim($_POST ['address']),FILTER_SANITIZE_STRING) ;
$birthdate = filter_var(trim($_POST ['birthdate']),FILTER_SANITIZE_STRING) ;
$fuel_client = filter_var(trim($_POST ['fuel']),FILTER_SANITIZE_STRING) ;


if(mb_strlen($name) < 2 ) { 
    $error = '<h1>Введите имя</h1>';
    header('location:../index.php');
    exit(); 
    
}else if(mb_strlen($surname) < 2 ) { 
    echo "Введите фамилию";
    exit();
}else if(mb_strlen($middlename) < 2 ) { 
    echo "Введите Отчество";
    exit();
}else if(mb_strlen($phone) < 7 ) { 
    echo "Введите телефон";
    exit();
}else if(mb_strlen($password) < 4 || mb_strlen($password) > 20 ) { 
    echo "Недопустимая длина пароля (от 4 до 20 символов)";
    exit();
}else if(mb_strlen($address) < 1 ) { 
    echo "Введите адресс";
    exit();
}

$password = md5($password); //Хэширование пароля

require "../blocks/connect.php"; //Подключение к БД

$sql = "INSERT INTO clients (name_clients, surname_clients, middlename_clients, phone_clients, pass_clients, address_clients, birthdate_clients, fuel_client) 
VALUES( ?,?,?,?,?,?,?,? )"; 

$query = $pdo->prepare($sql);

$query->execute(array($name, $surname, $middlename, $phone, $password, $address, $birthdate, $fuel_client)); //Внесение данных в БД

$sql = null;
$query = null;
$pdo = null;

header('location:../index.php');

?>



