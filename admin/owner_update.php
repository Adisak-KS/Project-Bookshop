<?php
require_once("../db/connect.php");


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $empId = $_POST["emp_id"];
    $empFullname =  $_POST["emp_fullname"];
    $empTel = $_POST["emp_tel"];
    $empProfile = $_POST["emp_profile"];
    $empNewProfile = $_FILES["emp_newProfile"];
    $empStatus = $_POST["emp_status"];

    $errorMessage = "";

    if (empty($empId) || empty($empFullname) || empty($empTel)) {
        $errorMessage = "กรุณากรอกข้อมูลให้ครบทุกช่อง";
    } elseif (!preg_match('/^[ก-๙เa-zA-Z\s\t]*$/', $empFullname)) {
        $errorMessage = "ชื่อ-นามสกุลห้ามมีตัวเลขและสัญลักษณ์พิเศษ";    
    } elseif (strlen($empFullname) < 3 || strlen($empFullname) > 50) {
        $errorMessage = "ชื่อ-นามสกุลต้องมี 3-50 ตัวอักษร";
    } elseif (!preg_match('/^0[0-9]*$/', $empTel)) {
        $errorMessage = "เบอร์โทรศัพท์ต้องเป็นตัวเลขและเริ่มด้วย 0 เท่านั้น";
    } elseif (strlen($empTel) < 9 || strlen($empTel) > 10) {
        $errorMessage = "เบอร์โทรศัพท์ต้องมี 9-10 ตัว";
    }

    // ตรวจสอบว่ามีการอัปโหลดไฟล์รูปโปรไฟล์ใหม่หรือไม่
    if (!empty($empNewProfile['name'])) {
        // ตรวจสอบขนาดไฟล์
        $maxFileSize = 1024 * 1024; // 1 MB in bytes
        if ($empNewProfile['size'] > $maxFileSize) {
            $errorMessage .= "ขนาดของไฟล์รูปต้องไม่เกิน 1 MB";
        }

        // ตรวจสอบไฟล์รูปโปรไฟล์ใหม่ที่อัปโหลด
        $allowedExtensions = array("png", "jpg", "jpeg");
        $empNewProfileName = $empNewProfile["name"];
        $empNewProfileExt = strtolower(pathinfo($empNewProfileName, PATHINFO_EXTENSION));
        if (!in_array($empNewProfileExt, $allowedExtensions)) {
            $errorMessage .= "ไฟล์รูปโปรไฟล์ต้องเป็นประเภท .png หรือ .png เท่านั้น";
        }
    }

    // หากมี Error
    if (!empty($errorMessage)) {
        $_SESSION['error'] = $errorMessage;
        header("Location: owner_update_form?emp_id=$empId");
        exit;
    } 
    // หากไม่มี Error ให้ดำเนินการเพิ่มข้อมูลต่อไป
    if(!empty($empStatus)){
        $controllerEmployees->updateEmployeesStatus($empStatus, $empId);
        $_SESSION['success'] = 'แก้ไขข้อมูลสำเร็จ';
        header("Location: owner_update_form?emp_id=$empId");
    }

} else {
    require_once("includes/alert/no_results_found.php");
    exit;
}
