<?php
require_once("../db/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $empAuthorityTypeId = $_POST["emp_authority_type_id"];
    $empAuthorityTypeDetail = $_POST["emp_authority_type_detail"];
    $empAuthorityTypeStatus = $_POST["emp_authority_type_status"];

    // หน้าที่จะกลับ
    $location = "Location: employees_authority_type_update_form";
    

    // ทำการอัปเดตรายละเอียด
    $result = $controllerEmployees->updateEmployeesAuthorityTypeDetail($empAuthorityTypeDetail, $empAuthorityTypeId);
    if ($result) {
        $_SESSION['success'] = 'แก้ไขข้อมูลสำเร็จ';
    }

    // ตรวจสอบว่ามีการส่งค่า empAuthorityTypeStatus มาหรือไม่
    if (isset($empAuthorityTypeStatus)) {
        // ทำการอัปเดตสถานะ
        $result_status = $controllerEmployees->updateEmployeesAuthorityTypeStatus($empAuthorityTypeStatus, $empAuthorityTypeId);
        if ($result_status) {
            $_SESSION['success'] = 'แก้ไขข้อมูลสำเร็จ';
        }
    }

    header($location);
} else {
    // หากไม่ได้เข้ามาผ่านวิธีการ POST หรือไม่ได้กดปุ่ม submit
    require_once("includes/alert/no_results_found.php");
}
 
