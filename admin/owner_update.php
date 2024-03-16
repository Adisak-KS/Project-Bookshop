<?php
require_once("../db/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $empId = $_POST["emp_id"];
    $empFullname =  $_POST["emp_fullname"];
    $empTel = $_POST["emp_tel"];
    $empProfile = $_POST["emp_profile"];
    $empNewProfile = $_FILES["emp_newProfile"];
    $empStatus = $_POST["emp_status"];


    // หน้าที่จะกลับ
    $location = "Location: owner_update_form";

    // ตรวจสอบ Detail ข้อมูลของ Employee
    $errorMessage = $controllerEmployees->validateFormUpdateEmployee($empFullname, $empTel, $empId);
    if (!empty($errorMessage)) {
        $_SESSION['error'] = $errorMessage;
        header($location);
        exit();
    } else {
        $controllerEmployees->updateEmployeesDetail($empFullname, $empTel, $empId);
        $_SESSION['success'] = 'แก้ไขข้อมูลสำเร็จ';
    }


    // หากมีการอัปโหลดไฟล์รูป
    if (!empty($empNewProfile)) {
        $errorMessage = $controllerEmployees->validateUpdateEmployeesProfile($empNewProfile);

        if (!empty($errorMessage)) {
            $_SESSION['error'] = $errorMessage;
            header($location);
            exit();
        } else {
            $controllerEmployees->updateEmployeesProfile($empProfile, $empNewProfile, $empId);
            $_SESSION['success'] = 'แก้ไขข้อมูลสำเร็จ';
        }
    }


    // หากมีการแก้ไขสถานะบัญชีของ Employee
    if (!empty($empStatus)) {
        $controllerEmployees->updateEmployeesStatus($empStatus, $empId);
        $_SESSION['success'] = 'แก้ไขข้อมูลสำเร็จ';
    }


    // ส่ง SESSION และกลับ
    header($location);
} else {
    // หากไม่ได้เข้ามาผ่านวิธีการ POST หรือไม่ได้กดปุ่ม submit
    require_once("includes/alert/no_results_found.php");
}
