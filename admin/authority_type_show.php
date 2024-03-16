<?php
require_once("../db/connect.php");

// แสดงพนักงานที่มีสิทธิ์ Owner
$result = $controllerEmployees->getAuthorityType();
// print_r($result); // ทดสอบ getOwner()
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
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mt-0 header-title">รายการข้อมูลประเภทสิทธิ์ในระบบทั้งหมด</h4>
                                    <hr>
                                    <!-- มีข้อมูลให้แสดง  -->
                                    <?php if ($result > 0) { ?>
                                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>ชื่อประเภทสิทธิ์</th>
                                                    <th>รายละเอียด</th>
                                                    <th>สถานะสิทธิ์</th>
                                                    <th>แก้ไขข้อมูล</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($result as $row) { ?>
                                                    <tr>
                                                        <td><?php echo $row["emp_authority_type_id"]; ?></td>
                                                        <td><?php echo $row["emp_authority_type_name"]; ?></td>
                                                        <td><?php echo $row["emp_authority_type_detail"]; ?></td>
                                                        <td class="status">
                                                            <?php
                                                            if ($row["emp_authority_type_status"] == "ACTIVATED") {
                                                                echo '<span class="badge bg-success">ACTIVATED</span>';
                                                            } else if ($row["emp_authority_type_status"] == "BLOCKED") {
                                                                echo '<span class="badge bg-danger">BLOCKED</span>';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <form action="authority_type_update_form" method="POST">
                                                                <input type="hidden" name="emp_authority_type_id" value="<?php echo $row["emp_authority_type_id"]; ?>">
                                                                <button type="submit" class="btn btn-warning">
                                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                                    <span>แก้ไข</span>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    <?php } else { ?>
                                        <?php require_once("includes/alert/no_data_available.php") ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal ของฟอร์ม Add Admin  -->
                    <?php require_once("owner_add_form.php"); ?>
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

    <!-- Right Sidebar -->
    <?php require_once("includes/navbar/nav_rightbar.php"); ?>
    <!-- Vendor -->
    <?php require_once("includes/vendor.php"); ?>
</body>

</html>

<!-- แจ้ง Success, Error จากฝั่ง server  -->
<?php require_once("includes/alert/alert_server-side.php");
