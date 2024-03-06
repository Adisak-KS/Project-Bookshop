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

/*
// Validation Form With Sweetalert2
function chkForm() {
    const empFullname = document.getElementById("emp_fullname").value;
    const empUsername = document.getElementById("emp_username").value;
    const empPassword = document.getElementById("emp_password").value;
    const empConfirmPassword = document.getElementById("emp_confirmPassword").value;
    const empEmail = document.getElementById("emp_email").value;
    const empTel = document.getElementById("emp_tel").value;

    let errorMessage = "";

    if (empFullname.trim().length === 0) {
        // ไม่ต้องกระทำอะไรเพราะไม่ได้กรอกข้อมูล
    } else if (!/^[ก-๙a-zA-Z\s\t]*$/.test(empFullname)) {
        errorMessage += " ชื่อ-นามสกุลห้ามมีตัวเลขและสัญลักษณ์พิเศษ,\n";
    } else if (empFullname.length < 3 || empFullname.length > 50) {
        errorMessage += "ชื่อ-นามสกุลต้องมี 3-50 ตัวอักษร,\n";
    }

    if (empUsername.trim().length === 0) {
        // ไม่ต้องกระทำอะไรเพราะไม่ได้กรอกข้อมูล
    } else if (!/^[a-zA-Z0-9_]*$/.test(empUsername)) {
        errorMessage += "ชื่อผู้ใช้มีได้เฉพาะภาษาอังกฤษและ _ เท่านั้น,\n";
    } else if (empUsername.length < 6 || empUsername.length > 30) {
        errorMessage += "ชื่อผู้ใช้ต้องมี 6-30 ตัวอักษร,\n";
    }


    if (empPassword.trim().length === 0) {
        // ไม่ต้องกระทำอะไรเพราะไม่ได้กรอกข้อมูล
    } else if (!/[A-Z]/.test(empPassword)) {
        errorMessage += "รหัสผ่านต้องมี A-Z อย่างน้อย 1 ตัว,\n";
    } else if (!/[a-z]/.test(empPassword)) {
        errorMessage += "รหัสผ่านต้องมี a-z อย่างน้อย 1 ตัว,\n";
    } else if (!/[0-9]/.test(empPassword)) {
        errorMessage += "รหัสผ่านต้องมี 0-9 อย่างน้อย 1 ตัว,\n";
    } else if (!/[!@#$%^&*()_+|~=`{}\[\]:";'<>?,.\/]/.test(empPassword)) {
        errorMessage += "รหัสผ่านต้องมีสัญลักษณ์อย่างน้อย 1 ตัว,\n";
    } else if (empPassword.length < 8 || empUsername.length > 16) {
        errorMessage += "รหัสผ่านต้องมี 8-16 ตัวอักษร,\n";
    } else if (empConfirmPassword != empPassword) {
        errorMessage += "รหัสผ่านกับยืนยันรหัสผ่านไม่ตรงกัน,\n";
    }


    if (empEmail.trim().length === 0) {
        // ไม่ต้องกระทำอะไรเพราะไม่ได้กรอกข้อมูล
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(empEmail) || !/[a-zA-Z0-9]/.test(empEmail[0])) {
        errorMessage += "รูปแบบอีเมลไม่ถูกต้อง,\n";
    } else if (empEmail.length < 16 || empPassword.length > 65) {
        errorMessage += "อีเมลต้องมี 16-65 ตัวอักษร,\n";
    }


    if (empTel.trim().length === 0) {
        // ไม่ต้องกระทำอะไรเพราะไม่ได้กรอกข้อมูล
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
*/


// ตรวจสอบฟอร์ม Add ผู้ใช้
function getValueById(id) {
    return document.getElementById(id).value.trim();
}

function validateName(fullname) {
    if (fullname.trim().length === 0) {
        // ไม่ต้องกระทำอะไรเพราะไม่ได้กรอกข้อมูล
    }else if (!/^[ก-๙a-zA-Z\s\t]*$/.test(fullname)) {
        return "ชื่อ-นามสกุลห้ามมีตัวเลขและสัญลักษณ์พิเศษ,\n";
    } else if (fullname.length < 3 || fullname.length > 50) {
        return "ชื่อ-นามสกุลต้องมี 3-50 ตัวอักษร,\n";
    }
    return "";
}

function validateUsername(username) {
    if (username.trim().length === 0) {
        // ไม่ต้องกระทำอะไรเพราะไม่ได้กรอกข้อมูล
    }else if (!/^[a-zA-Z0-9_]*$/.test(username)) {
        return "ชื่อผู้ใช้มีได้เฉพาะภาษาอังกฤษและ _ เท่านั้น,\n";
    } else if (username.length < 6 || username.length > 30) {
        return "ชื่อผู้ใช้ต้องมี 6-30 ตัวอักษร,\n";
    }
    return "";
}

function validatePassword(password, confirmPassword) {
    if (password.trim().length === 0) {
        // ไม่ต้องกระทำอะไรเพราะไม่ได้กรอกข้อมูล
    }else if (!/[A-Z]/.test(password) || !/[a-z]/.test(password) || !/[0-9]/.test(password) || !/[!@#$%^&*()_+|~=`{}\[\]:";'<>?,.\/]/.test(password)) {
        return "รหัสผ่านต้องมี A-Z, a-z, 0-9, และสัญลักษณ์อย่างน้อย 1 ตัว,\n";
    } else if (password.length < 8 || password.length > 16) {
        return "รหัสผ่านต้องมี 8-16 ตัวอักษร,\n";
    } else if (confirmPassword !== password) {
        return "รหัสผ่านกับยืนยันรหัสผ่านไม่ตรงกัน,\n";
    }
    return "";
}

function validateEmail(email) {
    if (email.trim().length === 0) {
        // ไม่ต้องกระทำอะไรเพราะไม่ได้กรอกข้อมูล
    }else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email) || !/[a-zA-Z0-9]/.test(email[0])) {
        return "รูปแบบอีเมลไม่ถูกต้อง,\n";
    } else if (email.length < 16 || email.length > 65) {
        return "อีเมลต้องมี 16-65 ตัวอักษร,\n";
    }
    return "";
}

function validateTel(tel) {
    if (tel.trim().length === 0) {
        // ไม่ต้องกระทำอะไรเพราะไม่ได้กรอกข้อมูล
    }else if (!/^0[0-9]*$/.test(tel)) {
        return "เบอร์โทรศัพท์ต้องเป็นตัวเลขและเริ่มด้วย 0 เท่านั้น\n";
    } else if (tel.length < 9 || tel.length > 10) {
        return "เบอร์โทรศัพท์ต้องมี 9-10 ตัว\n";
    }
    return "";
}

function chkFormEmployees() {
    const fullname = getValueById("emp_fullname");
    const username = getValueById("emp_username");
    const password = getValueById("emp_password");
    const confirmPassword = getValueById("emp_confirmPassword");
    const email = getValueById("emp_email");
    const tel = getValueById("emp_tel");

    let errorMessage = "";

    errorMessage += validateName(fullname);
    errorMessage += validateUsername(username);
    errorMessage += validatePassword(password, confirmPassword);
    errorMessage += validateEmail(email);
    errorMessage += validateTel(tel);

    if (errorMessage !== "") {
        Swal.fire({
            icon: 'warning',
            title: 'คำเตือน',
            text: errorMessage,
        });
        return false;
    }
    return true;
}
//////////////////////////////////////////////////////////////////////
