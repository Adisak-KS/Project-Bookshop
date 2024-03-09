<?php
require_once("../db/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    // รับค่า
    $empFullname = $_POST["emp_fullname"];
    $empUsername = $_POST["emp_username"];
    $empPassword = $_POST["emp_password"];
    $empConfirmPassword = $_POST["emp_confirmPassword"];
    $empEmail = $_POST["emp_email"];
    $empTel = $_POST["emp_tel"];

    $errorMessage = "";

    if (empty($empFullname) || empty($empUsername) || empty($empPassword) || empty($empConfirmPassword) || empty($empEmail) || empty($empTel)) {
        $errorMessage = "กรุณากรอกข้อมูลให้ครบทุกช่อง";
    } elseif (!preg_match('/^[ก-๙a-zA-Z\s\t]*$/', $empFullname)) {
        $errorMessage = "ชื่อ-นามสกุลห้ามมีตัวเลขและสัญลักษณ์พิเศษ";
    } elseif (strlen($empFullname) < 3 || strlen($empFullname) > 50) {
        $errorMessage = "ชื่อ-นามสกุลต้องมี 3-50 ตัวอักษร";
    } elseif (!preg_match('/^[a-zA-Z0-9_]*$/', $empUsername)) {
        $errorMessage = "ชื่อผู้ใช้มีได้เฉพาะภาษาอังกฤษและ _ เท่านั้น และไม่มีเว้นวรรค";
    } elseif (strlen($empUsername) < 6 || strlen($empUsername) > 30) {
        $errorMessage = "ชื่อผู้ใช้ต้องมี 3-30 ตัวอักษร";
    } elseif (!preg_match('/[A-Z]/', $empPassword) || !preg_match('/[a-z]/', $empPassword) || !preg_match('/[0-9]/', $empPassword) || !preg_match('/[!@#$%^&*()_+-|~=`{}\[\]:";\'<>?,.\/]/', $empPassword)) {
        $errorMessage = "รหัสผ่านต้องมี A-Z, a-z, 0-9, และสัญลักษณ์อย่างละ 1 ตัว ห้ามมีเว้นวรรค";
    } elseif (strlen($empPassword) < 8 || strlen($empPassword) > 16) {
        $errorMessage = "รหัสผ่านต้องมี 8-16 ตัวอักษร";
    } elseif ($empConfirmPassword !== $empPassword) {
        $errorMessage = "รหัสผ่านกับยืนยันรหัสผ่านไม่ตรงกัน";
    } elseif (!filter_var($empEmail, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "รูปแบบอีเมลไม่ถูกต้อง";
    } elseif (strlen($empEmail) < 16 || strlen($empEmail) > 65) {
        $errorMessage = "อีเมลต้องมี 16-65 ตัวอักษร";
    } elseif (!preg_match('/^0[0-9]*$/', $empTel)) {
        $errorMessage = "เบอร์โทรศัพท์ต้องเป็นตัวเลขและเริ่มด้วย 0 เท่านั้น";
    } elseif (strlen($empTel) < 9 || strlen($empTel) > 10) {
        $errorMessage = "เบอร์โทรศัพท์ต้องมี 9-10 ตัว";
    }
    // หากมี Error
    if (!empty($errorMessage)) {
        $_SESSION['error'] = $errorMessage;
        header("Location: owner_show.php");
        exit;
    }

    // ตรวจสอบ Username และ Email ใน Database
    $count = $controllerEmployees->checkEmpUsernameExist($empUsername, $empEmail);

    if ($count > 0) {
        header("Location: owner_show.php");
        $_SESSION["error"] = "ชื่อผู้ใช้หรืออีเมลไม่สามารถใช้งานได้";
        exit;
    } else {
        $result = $controllerEmployees->insertOwner($empFullname, $empUsername, $empPassword, $empEmail, $empTel);
        header("Location: owner_show.php");
        $_SESSION["success"] = "เพิ่มข้อมูลสำเร็จ";
    }
}
