<?php
$user = 'root';
$pass = 'root';
$dsn = 'mysql:host=localhost;dbname=azs';
try {
	$pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
}catch (PDOException $error) {
	die($error->getMessage());
}

?>
