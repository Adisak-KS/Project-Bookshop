<?php

$servername = "localhost";
$username = "bookshop";
$password = "123";
$dbname = "bookshop";
$dsn = "mysql:host=$servername;dbname=$dbname";

try {
    $conn = new PDO($dsn, $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";
    
    // เรียกใช่ Controller
    require_once("../admin/controller/controller.php");

    session_start();

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
