<?php
require_once("../db/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    // รับค่าจากฟอร์ม
    $empFullname = $_POST["emp_fullname"];
    $empUsername = $_POST["emp_username"];
    $empPassword = $_POST["emp_password"];
    $empConfirmPassword = $_POST["emp_confirmPassword"];
    $empEmail = $_POST["emp_email"];
    $empTel = $_POST["emp_tel"];

    // เรียกใช้ฟังก์ชัน validateFormInsertEmployee เพื่อตรวจสอบข้อมูลฟอร์ม
    $errorMessage = $controllerEmployees->validateFormInsertEmployee($empFullname, $empUsername, $empPassword, $empConfirmPassword, $empEmail, $empTel);

    // หากมีข้อผิดพลาด
    if (!empty($errorMessage)) {
        $_SESSION['error'] = $errorMessage;
        header("Location: owner_show.php");
        exit;
    } else {

        // ตรวจสอบชื่อผู้ใช้และอีเมลซ้ำกันในฐานข้อมูล
        $count = $controllerEmployees->checkEmpUsernameExist($empUsername, $empEmail);
        if ($count > 0) {
            // หากมี Username Or Email ซ้ำ
            $_SESSION["error"] = "ชื่อผู้ใช้หรืออีเมลไม่สามารถใช้งานได้";

        } else {
            // เพิ่มข้อมูลพนักงงานลงใน Database
            $result = $controllerEmployees->insertOwner($empFullname, $empUsername, $empPassword, $empEmail, $empTel);
            $_SESSION["success"] = "เพิ่มข้อมูลสำเร็จ";
        }

        //ส่ง Error OR Succrss และไปหน้า owner_show.php
        header("Location: owner_show.php");
        exit;
    }
} else {
    // หากไม่ได้เข้ามาผ่านวิธีการ POST หรือไม่ได้กดปุ่ม submit
    require_once("includes/alert/no_results_found.php");
}
