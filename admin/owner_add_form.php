<!-- Long Content Scroll Modal -->
<!-- <form class="needs-validation" novalidate action="owner_add.php" onsubmit="return chkFormEmployeesInsert();" method="POST"> -->
<form novalidate action="owner_add.php" method="POST">
    <div class="modal fade" id="scrollable-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="scrollableModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollableModalTitle">ฟอร์มเพิ่มข้อมูลเจ้าของร้าน / ผู้บริหาร</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    
                    <div class="mb-3">
                        <label for="emp_fullname" class="form-label">ชื่อ - นามสกุล<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="emp_fullname" id="emp_fullname" placeholder="ระบุชื่อ-นามสกุล เช่น เอ บีบี" maxlength="50" required>
                        <div class="invalid-feedback">
                            <small>กรุณาระบุ ชื่อ - นามสกุล</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="emp_username" class="form-label">ชื่อผู้ใช้ (Username)<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="inputGroupPrepend">@</span>
                            <input type="text" class="form-control" name="emp_username" id="emp_username" placeholder="ระบุชื่อผู้ใช้ (Username) เช่น User_1" aria-describedby="inputGroupPrepend" maxlength="30" required>
                            <div class="invalid-feedback">
                                <small>กรุณาระบุ ชื่อผู้ใช้ (Username)</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="emp_password" class="form-label">รหัสผ่าน<span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge">
                            <input type="password" class="form-control" name="emp_password" id="emp_password" placeholder="ระบุรหัสผ่าน 8 ตัวขึ้นไป" maxlength="20" required>
                            <div class="input-group-text" data-password="false">
                                <span class="password-eye"></span>
                            </div>
                            <div class="invalid-feedback">
                                <small>กรุณาระบุ รหัสผ่าน</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="emp_confirmPassword" class="form-label">ยืนยันรหัสผ่าน<span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge">
                            <input type="password" class="form-control" name="emp_confirmPassword" id="emp_confirmPassword" placeholder="ยืนยันรหัสผ่านอีกครั้ง" maxlength="30" required>
                            <div class="input-group-text" data-password="false">
                                <span class="password-eye"></span>
                            </div>
                            <div class="invalid-feedback">
                                <small>กรุณาระบุ รหัสผ่านอีกครั้ง</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="emp_email" class="form-label">อีเมล<span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="emp_email" id="emp_email" placeholder="ระบุอีเมล เช่น example@gmail.com" maxlength="65" required>
                        <div class="invalid-feedback">
                            <small>กรุณาระบุ อีเมล</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="emp_tel" class="form-label">เบอร์โทรศัพท์<span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" name="emp_tel" id="emp_tel" placeholder="ระบุเบอร์โทรศัพท์ เช่น 0876543210" maxlength="10" required>
                        <div class="invalid-feedback">
                            <small>กรุณาระบุ เบอร์โทรศัพท์</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                        <span>ยกเลิก</span>
                    </button>
                    <button type="submit" name="submit" class="btn btn-success">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>บันทึก</span>
                    </button>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form>