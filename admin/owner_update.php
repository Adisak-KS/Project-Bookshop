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
    } else {
        // แก้ไขข้อมูลลง database
        $controllerEmployees->updateEmployeesDetail($empFullname, $empTel, $empId);
        $_SESSION['success'] = 'แก้ไขข้อมูลสำเร็จ';
        header("Location: owner_update_form?emp_id=$empId");
    }

    // หากมีการอัปโหลดรูปภาพมา
    if(!empty($empNewProfile)){
        // ตรวจสอบว่ามีข้อผิดพลาดในการอัปโหลดหรือไม่
        if($empNewProfile['error'] === UPLOAD_ERR_OK){
            // ตำแหน่งที่จะเก็บ
            $uploadDir = "uploads/profile_employees/";
            $uploadFile = $uploadDir . basename($empNewProfile['name']);
            if (move_uploaded_file($empNewProfile['tmp_name'], $uploadFile)) {

                $empNewProfile = basename($empNewProfile['name']);
                $controllerEmployees->updateEmployeesProfile($empNewProfile, $empId);

            } else {
                $errorMessage = "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
            }
        }else{
            $errorMessage = "มีข้อผิดพลาดในการอัปโหลด";
        }
    }


    // หากมีการแก้ไขสถานะบัญชี
    if (!empty($empStatus)) {
        $controllerEmployees->updateEmployeesStatus($empStatus, $empId);
        $_SESSION['success'] = 'แก้ไขข้อมูลสำเร็จ';
        header("Location: owner_update_form?emp_id=$empId");
    }


    // หากมี Error
    if (!empty($errorMessage)) {
        $_SESSION['error'] = $errorMessage;
        header("Location: owner_update_form?emp_id=$empId");

    }
    // หากมีการเลือก ACTIVATED ให้แก้ไขใน Database
    if(!empty($empStatus)){
        $controllerEmployees->updateEmployeesStatus($empStatus, $empId);
        $_SESSION['success'] = 'แก้ไขข้อมูลสำเร็จ';
        header("Location: owner_update_form?emp_id=$empId");
    }

} else {
    header("Location: includes/alert/no_results_found.php");
    
}
