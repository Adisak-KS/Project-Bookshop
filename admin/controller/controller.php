<!-- เรียกใช้งาน Controller ทั้งหมดที่นี้ -->

<?php
require_once("controllerDefault.php");

// สร้าง object ของคลาส Controller ด้วย connection object
$controllerDefault = new ControllerDefalut($conn);

// ตรวจสอบและเพิ่มข้อมูลเบื้องต้น
$controllerDefault->insertEmpAuthorityTypeDefault();
$controllerDefault->insertOwnerDefault();


require_once("controllerEmployees.php");
$controllerEmployees = new controllerEmployees($conn);
?>