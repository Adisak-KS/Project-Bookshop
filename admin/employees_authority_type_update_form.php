<?php
require_once("../db/connect.php");

// รับค่าจาก employees_auyhority_type_show
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // หากเข้ามาผ่านการส่งข้อมูลแบบ POST
    $empAuthorityTypeId = $_POST["emp_authority_type_id"];
    $_SESSION["emp_authority_type_id"] = $empAuthorityTypeId;

    $row = $controllerEmployees->getEmployeesAuthorityTypeDetail($empAuthorityTypeId);
} elseif (isset($_SESSION['emp_authority_type_id'])) {
    // หากมีค่า emp_authority_type_id ใน session
    $empAuthorityTypeId =  $_SESSION["emp_authority_type_id"];
    $row = $controllerEmployees->getEmployeesAuthorityTypeDetail($empAuthorityTypeId);
} else {
    // หากมีการเข้าถึงหน้านี้ โดยวิธีไม่ปกติ
    require_once("includes/alert/no_results_found.php");
    exit;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once("includes/head.php"); ?>
</head>

<body class="loading" data-layout-color="light" data-layout-mode="default" data-layout-size="fluid" data-topbar-color="light" data-leftbar-position="fixed" data-leftbar-color="light" data-leftbar-size='default' data-sidebar-user='true'>
    <!-- Begin page -->
    <div id="wrapper">
        <!-- Topbar -->
        <?php require_once("includes/navbar/nav_topbar.php"); ?>
        <!-- Leftbar -->
        <?php require_once("includes/navbar/nav_leftbar.php"); ?>

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <div class="content-page">
            <div class="content">
                <!-- Start Content-->
                <div class="container-fluid">
                    <form class="needs-validation" novalidate action="employees_authority_type_update" method="post">
                        <!-- <form novalidate action="owner_update" method="post" enctype="multipart/form-data"> -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>แก้ไขข้อมูลประเภทสิทธิ์พนักงาน</span>
                                        </h4>
                                        <hr>
                                        <div class="mb-3">
                                            <label for="emp_authority_type_id" class="form-label">รหัสประเภทสิทธิ์พนักงาน : </label>
                                            <input type="text" class="form-control bg-light" name="emp_authority_type_id" value="<?php echo $row["emp_authority_type_id"]; ?>" required readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="emp_authority_type_name" class="form-label">ชื่อประเภทสิทธิ์ :</label>
                                            <input type="text" class="form-control bg-light" name="emp_authority_type_name" value="<?php echo $row["emp_authority_type_name"]; ?>" required disabled>
                                        </div>
                                        <div class="mb-3 pb-3">
                                            <label for="emp_authority_type_detail" class="form-label">รายละเอียด :</label>
                                            <input type="text" class="form-control" name="emp_authority_type_detail" value="<?php echo $row["emp_authority_type_detail"]; ?>" placeholder="ระบุข้อมูลสั้น ๆ เกี่ยวกับสิทธิ์นี้" maxlength="50" required>
                                            <div class="invalid-feedback">
                                                <small>กรุณาระบุ รายละเอียดสั้น ๆ</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card pb-2">
                                    <div class="card-body">
                                        <h4 class="header-title">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>แก้ไขสถานะสิทธิ์พนักงาน</span>
                                        </h4>
                                        <hr>
                                        <div class="form-check pb-5">
                                            <div class="form-check mb-2 form-check-success">
                                                <input class="form-check-input" type="radio" name="emp_authority_type_status" value="ACTIVATED" <?php if ($row["emp_authority_type_status"] == "ACTIVATED") echo "checked"; ?>>
                                                <label class="form-check-label" for="emp_authority_type_status">อนุญาติให้ใช้งาน (ACTIVATED)</label>
                                            </div>
                                            <div class="form-check mb-2 form-check-danger pb-5">
                                                <input class="form-check-input" type="radio" name="emp_authority_type_status" value="BLOCKED" <?php if ($row["emp_authority_type_status"] == "BLOCKED") echo "checked"; ?>>
                                                <label class="form-check-label pb-5" for="emp_authority_type_status">ระงับการใช้งาน (BLOCKED)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">ยืนยันการจัดการข้อมูล <span class="badge bg-warning rounded-pill text-black">แก้ไขเมื่อ : <?php echo $row["emp_authority_type_uptime"]; ?></span></h4>
                                    <hr>
                                    <a href="employees_authority_type_show" class="btn btn-secondary">
                                        <i class="fa-solid fa-xmark"></i>
                                        <span>ยกเลิก</span>
                                    </a>

                                    <button type="submit" name="submit" class="btn btn-warning">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span>บันทึกการแก้ไข</span>
                                    </button>
                                </div>
                            </div> <!-- end card-body -->
                        </div>
                    </form>
                </div>

            </div>
        </div>

        <!-- Footer Start -->
        <?php require_once("includes/navbar/nav_footer.php"); ?>
    </div>
    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->
    </div>
    <!-- Right Sidebar -->
    <?php require_once("includes/navbar/nav_rightbar.php"); ?>
    <!-- Vendor -->
    <?php require_once("includes/vendor.php"); ?>
</body>

</html>
<!-- แจ้ง Success, Error จากฝั่ง server  -->
<?php require_once("includes/alert/alert_server-side.php");
