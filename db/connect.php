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
    require_once("../admin/controller/controller.php");

    // สร้าง object ของคลาส Controller ด้วย connection object
    $controller = new Controller($conn);
    $controller->insertEmpAuthorityTypeDefault();
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
