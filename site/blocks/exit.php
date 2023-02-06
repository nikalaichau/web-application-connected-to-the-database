<?php
session_start();
unset($_SESSION['branch_workers']);
unset($_SESSION['position_workers']);
setcookie('clients', $user['id_clients'], time() -3600,"/" );
setcookie('worker', $user['id_workers'], time() -3600,"/" );
session_destroy();
header('location:../index.php');
?>