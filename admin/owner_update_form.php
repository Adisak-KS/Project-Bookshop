<?php
require_once("../db/connect.php");

if (isset($_GET['emp_id'])) {
    $empId = $_GET['emp_id'];

    $row = $controllerEmployees->getEmployeesDetail($empId);
    // print_r($row);
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
                    <!-- <form class="needs-validation" novalidate action="owner_update" method="post" enctype="multipart/form-data" onsubmit="return chkFormEmployeesUpdate()"> -->
                    <form novalidate action="owner_update" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>แก้ไขข้อมูลพนักงาน</span>
                                        </h4>
                                        <hr>
                                        <div class="mb-3">
                                            <label for="emp_id" class="form-label">รหัสพนักงาน : </label>
                                            <input type="text" class="form-control bg-light" name="emp_id" id="emp_id" value="<?php echo $row["emp_id"]; ?>" placeholder="รหัสของพนักงาน" required readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="emp_fullname" class="form-label">ชื่อ - นามสกุล :</label>
                                            <input type="text" class="form-control" name="emp_fullname" id="emp_fullname" value="<?php echo $row["emp_fullname"]; ?>" placeholder="ระบุชื่อ-นามสกุล เช่น เอ บีบี" maxlength="50" required>
                                            <div class="invalid-feedback">
                                                <small>กรุณาระบุ ชื่อ - นามสกุล</small>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="emp_username" class="form-label">ชื่อผู้ใช้ (Username) :</label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="inputGroupPrepend">@</span>
                                                <input type="text" class="form-control bg-light" name="emp_username" id="emp_username" value="<?php echo $row["emp_username"]; ?>" placeholder="ระบุชื่อผู้ใช้ (Username) เช่น User_1" aria-describedby="inputGroupPrepend" maxlength="30" required disabled>
                                                <div class="invalid-feedback">
                                                    <small>กรุณาระบุ ชื่อผู้ใช้ (Username)</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="emp_email" class="form-label">อีเมล :</label>
                                            <input type="email" class="form-control bg-light" name="emp_email" id="emp_email" value="<?php echo $row["emp_email"]; ?>" placeholder="ระบุอีเมล เช่น example@gmail.com" maxlength="65" required disabled>
                                            <div class="invalid-feedback">
                                                <small>กรุณาระบุ อีเมล</small>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="emp_tel" class="form-label">เบอร์โทรศัพท์ :</label>
                                            <input type="tel" class="form-control" name="emp_tel" id="emp_tel" value="<?php echo $row["emp_tel"]; ?>" placeholder="ระบุเบอร์โทรศัพท์ เช่น 0876543210" maxlength="10" required>
                                            <div class="invalid-feedback">
                                                <small>กรุณาระบุ เบอร์โทรศัพท์</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>แก้ไขรูปภาพผู้ใช้งาน</span>
                                        </h4>
                                        <hr>
                                        <div class="mb-3">
                                            <img id="previewEmpProfile" src="uploads/profile_employees/<?php echo $row["emp_profile"]; ?>">
                                            <input type="text" class="form-control" name="emp_profile" value="<?php echo $row["emp_profile"]; ?>" readonly>
                                            <br>
                                            <label for="emp_newProfile" class="form-label">รูปภาพผู้ใช้ใหม่ : </label>
                                            <input type="file" class="form-control" name="emp_newProfile" id="emp_newProfile" accept="image/png, image/jpeg" onchange="previewEmployeesProfile()">
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>แก้ไขสถานะบัญชี</span>
                                        </h4>
                                        <hr>
                                        <div class="form-check">
                                            <div class="form-check mb-2 form-check-success">
                                                <input class="form-check-input" type="radio" name="emp_status" value="ACTIVATED" <?php if ($row["emp_status"] == "ACTIVATED") echo "checked"; ?>>
                                                <label class="form-check-label" for="emp_status">อนุญาติให้ใช้งาน (ACTIVATED)</label>
                                            </div>
                                            <div class="form-check mb-2 form-check-danger">
                                                <input class="form-check-input" type="radio" name="emp_status" value="BLOCKED" <?php if ($row["emp_status"] == "BLOCKED") echo "checked"; ?> disabled>
                                                <label class="form-check-label" for="emp_status">ระงับการใช้งาน (BLOCKED)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">ยืนยันการจัดการข้อมูล  <span class="badge bg-warning rounded-pill text-black">แก้ไขเมื่อ : <?php echo $row["emp_uptime"];?></span></h4>
                                    <hr>
                                    <a href="owner_show" class="btn btn-secondary">
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