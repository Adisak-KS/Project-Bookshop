<?php
require_once("../db/connect.php");

$result = $controller->getOwner();
// print_r($result);
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
        <?php require_once("includes/nav_topbar.php"); ?>
        <!-- Leftbar -->
        <?php require_once("includes/nav_leftbar.php"); ?>

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
                                    <h4 class="mt-0 header-title">รายการข้อมูลเจ้าของระบบ / ผู้บริหารทั้งหมด</h4>
                                    <hr>
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#scrollable-modal">
                                        <i class="fa-regular fa-square-plus"></i>
                                        เพิ่มเจ้าของระบบ
                                    </button>

                                    <!-- มีข้อมูลให้แสดง  -->
                                    <?php if ($result && $result->rowCount() > 0) { ?>
                                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>รูป</th>
                                                    <th>ชื่อ-นามสกุล</th>
                                                    <th>ชื่อผู้ใช้</th>
                                                    <th>อีเมล</th>
                                                    <th>ระดับสิทธิ์</th>
                                                    <th>สถานะบัญชี</th>
                                                    <th>ดูรายละเอียด</th>
                                                    <th>แก้ไขข้อมูล</th>
                                                    <th>ลบข้อมูล</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                                                    <tr>
                                                        <td><?php echo $row["emp_id"]; ?></td>
                                                        <td class="profile"><img src="uploads/profile_employees/<?php echo $row["emp_profile"]; ?>" alt="รูปผู้ดูแลระบบ"></td>
                                                        <td><?php echo $row["emp_fullname"]; ?></td>
                                                        <td><?php echo $row["emp_username"]; ?></td>
                                                        <td><?php echo $row["emp_email"]; ?></td>
                                                        <td>
                                                            <?php
                                                            $authority_names = explode(",", $row["emp_authority_type_name"]);
                                                            foreach ($authority_names as $typeName) { //หากมีสิทธิ์ > 1 ให้แสดงลูปแยกบรรทัด
                                                                echo $typeName . "<br>";
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><?php echo $row["status"]; ?></td>
                                                        <td>
                                                            <a href="#" class="btn btn-info">
                                                                <i class="fa-solid fa-eye"></i>
                                                                รายละเอียด
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#" class="btn btn-warning">
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                                แก้ไข
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#" class="btn btn-danger">
                                                                <i class="fa-solid fa-trash"></i>
                                                                ลบ
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    <?php } else { ?>
                                        <?php require_once("includes/alert_notfound.php");?>
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
            <?php require_once("includes/nav_footer.php"); ?>
        </div>
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->

    <!-- Right Sidebar -->
    <?php require_once("includes/nav_rightbar.php"); ?>
    <!-- Vendor -->
    <?php require_once("includes/vendor.php"); ?>
</body>

</html>