"use strict";

// การเริ่มต้น DataTable
$(document).ready(function () {
    $("#datatable").DataTable();

    const exportDatatable = $("#datatable-buttons").DataTable({
        layout: {
            topStart: {
                buttons: ['copy', 'excel', 'print']
            }
        }
    });

    exportDatatable.buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)");
    $("#datatable_length select[name*='datatable_length']").addClass("form-select form-select-sm").removeClass("custom-select custom-select-sm");

    $(".dataTables_length label").addClass("form-label");
});


// Validation Form With Sweetalert2
function chkFormEmployeesInsert() {
    const empFullname = document.getElementById("emp_fullname").value;
    const empUsername = document.getElementById("emp_username").value;
    const empPassword = document.getElementById("emp_password").value;
    const empConfirmPassword = document.getElementById("emp_confirmPassword").value;
    const empEmail = document.getElementById("emp_email").value;
    const empTel = document.getElementById("emp_tel").value;

    let errorMessage = "";

    if (empFullname.trim().length === 0) {
        // ให้ bootstrap5 แจ้งเตือน
    } else if (!/^[ก-๙a-zA-Z\s\t]*$/.test(empFullname)) {
        errorMessage += " ชื่อ-นามสกุลห้ามมีตัวเลขและสัญลักษณ์พิเศษ,\n";
    } else if (empFullname.length < 3 || empFullname.length > 50) {
        errorMessage += "ชื่อ-นามสกุลต้องมี 3-50 ตัวอักษร,\n";
    }

    if (empUsername.trim().length === 0) {
        // ให้ bootstrap5 แจ้งเตือน
    } else if (!/^[a-zA-Z0-9_]*$/.test(empUsername)) {
        errorMessage += "ชื่อผู้ใช้มีได้เฉพาะภาษาอังกฤษและ _ เท่านั้น,\n";
    } else if (empUsername.length < 6 || empUsername.length > 30) {
        errorMessage += "ชื่อผู้ใช้ต้องมี 6-30 ตัวอักษร,\n";
    }


    if (empPassword.trim().length === 0) {
        // ให้ bootstrap5 แจ้งเตือน
    } else if (!/[A-Z]/.test(empPassword)) {
        errorMessage += "รหัสผ่านต้องมี A-Z อย่างน้อย 1 ตัว,\n";
    } else if (!/[a-z]/.test(empPassword)) {
        errorMessage += "รหัสผ่านต้องมี a-z อย่างน้อย 1 ตัว,\n";
    } else if (!/[0-9]/.test(empPassword)) {
        errorMessage += "รหัสผ่านต้องมี 0-9 อย่างน้อย 1 ตัว,\n";
    } else if (!/[!@#$%^&*()_+-|~=`{}\[\]:";'<>?,.\/]/.test(empPassword)) {
        errorMessage += "รหัสผ่านต้องมีสัญลักษณ์อย่างน้อย 1 ตัว,\n";
    } else if (empPassword.length < 8 || empUsername.length > 16) {
        errorMessage += "รหัสผ่านต้องมี 8-16 ตัวอักษร,\n";
    } else if (empConfirmPassword != empPassword) {
        errorMessage += "รหัสผ่านกับยืนยันรหัสผ่านไม่ตรงกัน,\n";
    }


    if (empEmail.trim().length === 0) {
        // ให้ bootstrap5 แจ้งเตือน
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(empEmail) || !/[a-zA-Z0-9]/.test(empEmail[0])) {
        errorMessage += "รูปแบบอีเมลไม่ถูกต้อง,\n";
    } else if (empEmail.length < 16 || empPassword.length > 65) {
        errorMessage += "อีเมลต้องมี 16-65 ตัวอักษร,\n";
    }


    if (empTel.trim().length === 0) {
        // ให้ bootstrap5 แจ้งเตือน
    } else if (!/^0[0-9]*$/.test(empTel)) {
        errorMessage += "เบอร์โทรศัพท์ต้องเป็นตัวเลขและเริ่มด้วย 0 เท่านั้น\n";
    } else if (empTel.length < 9 || empTel.length > 10) {
        errorMessage += "เบอร์โทรศัพท์ต้องมี 9-10 ตัว\n";
    }


    if (errorMessage !== "") {
        // หากมี errorMessage > 1 ให้แสดง sweetalert
        Swal.fire({
            icon: 'warning',
            title: 'คำเตือน',
            text: errorMessage,
        });
        return false; // ยกเลิก submit
    }
    return true; // submit ได้
}

function chkFormEmployeesUpdate() {
    const empFullname = document.getElementById("emp_fullname").value;
    const empTel = document.getElementById("emp_tel").value;
    const empNewProfile = document.getElementById("emp_newProfile").files[0];

    let errorMessage = "";

    if (empFullname.trim().length === 0) {
        // ให้ bootstrap5 แจ้งเตือน
    } else if (!/^[ก-๙a-zA-Z\s\t]*$/.test(empFullname)) {
        errorMessage += " ชื่อ-นามสกุลห้ามมีตัวเลขและสัญลักษณ์พิเศษ,\n";
    } else if (empFullname.length < 3 || empFullname.length > 50) {
        errorMessage += "ชื่อ-นามสกุลต้องมี 3-50 ตัวอักษร,\n";
    }

    if (empTel.trim().length === 0) {
        // ให้ bootstrap5 แจ้งเตือน
    } else if (!/^0[0-9]*$/.test(empTel)) {
        errorMessage += "เบอร์โทรศัพท์ต้องเป็นตัวเลขและเริ่มด้วย 0 เท่านั้น,\n";
    } else if (empTel.length < 9 || empTel.length > 10) {
        errorMessage += "เบอร์โทรศัพท์ต้องมี 9-10 ตัว,\n";
    }

    // เช็คไฟล์รูป
    if (empNewProfile) {
        const allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
        if (!allowedExtensions.test(empNewProfile.name)) {
            errorMessage += "รูปภาพต้องเป็นไฟล์ประเภท .jpg หรือ .png เท่านั้น\n";
        } else {
            const fileSizeInBytes = empNewProfile.size;
            const fileSizeInMB = fileSizeInBytes / (1024 * 1024);
            if (fileSizeInMB > 1) {
                errorMessage += "ขนาดของไฟล์ภาพต้องไม่เกิน 1MB\n";
            }
        }
    }

    if (errorMessage !== "") {
        // หากมี errorMessage > 1 ให้แสดง sweetalert
        Swal.fire({
            icon: 'warning',
            title: 'คำเตือน',
            text: errorMessage,
        });
        return false; // ยกเลิก submit
    }
    return true; // submit ได้
}

function previewEmployeesProfile() {
    const preview = document.getElementById('previewEmpProfile');
    const file = document.getElementById('emp_newProfile').files[0];
    const reader = new FileReader();

    reader.onloadend = function () {
        preview.src = reader.result;
    }

    if (file) {
        const fileName = file.name.toLowerCase();
        if (fileName.endsWith('.png') || fileName.endsWith('.jpg') || fileName.endsWith('.jpeg')) {
            reader.readAsDataURL(file);
        } else {
            // ใช้ URL ของรูปภาพของพนักงานที่ถูกเก็บไว้บนเซิร์ฟเวอร์
            Swal.fire({
                icon: 'warning',
                title: 'คำเตือน',
                text: 'รูปภาพต้องเป็นไฟล์ประเภท .jpg หรือ .png เท่านั้น',
            });
            return false;
        }
    } else {
        // ใช้ URL ของรูปภาพของพนักงานที่ถูกเก็บไว้บนเซิร์ฟเวอร์
        preview.src = "uploads/profile_employees/<?php echo $row['emp_profile']; ?>";
    }
}











//////////////////////////////////////////////////////////////////////

