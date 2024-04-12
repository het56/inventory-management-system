<?php
session_start();
require_once 'db_connect.php';
//echo $_SESSION['user_id'];
if(!$_SESSION['user_id']) { 
    header('location:http://localhost/stock_system/index.php');
}
?>
