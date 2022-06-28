<?php
/**
 */
$dbuser = "root";
$dbpassword = "";
$dbserver = "localhost";
$dbname = "uas_pweb";

$dsn = "mysql:host={$dbserver};dbname={$dbname}";

$connection = null;
try{
    $connection = new PDO($dsn, $dbuser, $dbpassword);
}catch (Exception $exception){
    die($exception->getMessage());
}