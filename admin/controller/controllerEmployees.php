<!-- จัดการเกี่ยวกับข้อมูลผู้ดูแลเว็บไซต์ทั้งหมด -->
<?php
class controllerEmployees
{
    private $db;

    // ทดสอบใช้งาน ControllerEmployees ได้
    function __construct($conn)
    {
        $this->db = $conn;
        // echo "เรียกใช้งาน Controller Employees เริ่มต้นสำเร็จ";
    }


    // แสดงข้อมูล Employees ที่มีสิทธิ์ Owner
    function getOwner()
    {
        try {
            $sql = "SELECT a.*, 
                    GROUP_CONCAT(b.emp_authority_type_id) AS emp_authority_type_id, 
                    GROUP_CONCAT(c.emp_authority_type_name) AS emp_authority_type_name
                    FROM bs_employees a
                    INNER JOIN bs_employees_authority b ON a.emp_id = b.emp_id
                    INNER JOIN bs_employees_authority_type c ON b.emp_authority_type_id = c.emp_authority_type_id
                    WHERE (b.emp_authority_type_id = 2 OR b.emp_authority_type_id = 6)
                    GROUP BY a.emp_id
                    HAVING COUNT(DISTINCT b.emp_authority_type_id) = 2;
                   ";
            $result = $this->db->query($sql);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }


    // ตรวจสอบ Usename และ Email ของ Employees
    function checkEmpUsernameExist($empUsername, $empEmail)
    {
        try {
            $sql = "SELECT COUNT(*) AS count FROM bs_employees
                    WHERE emp_username = :emp_username OR emp_email = :emp_email
                   ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':emp_username', $empUsername);
            $stmt->bindParam(':emp_email', $empEmail);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['count'];
        } catch (PDOException $e) {
            return false;
        }
    }


    // ตรวจสอบฟอร์ม Insert ข้อมูล Employee
    function validateFormInsertEmployee($empFullname, $empUsername, $empPassword, $empConfirmPassword, $empEmail, $empTel)
    {
        // เก็บข้อความ Error
        $errorMessage = "";

        // ตรวจสอบเงื่อนไขหากมี  $errorMessage จะหยุดตรวจสอบเงื่อนไขอื่น ๆ ทันที และแจ้งเตือน
        if (empty($empFullname) || empty($empUsername) || empty($empPassword) || empty($empConfirmPassword) || empty($empEmail) || empty($empTel)) {
            $errorMessage = "กรุณากรอกข้อมูลให้ครบทุกช่อง";
            return $errorMessage;
        } elseif (!preg_match('/^[ก-๙เa-zA-Z\s\t]*$/', $empFullname)) {
            $errorMessage = "ชื่อ-นามสกุลห้ามมีตัวเลขและสัญลักษณ์พิเศษ";
            return $errorMessage;
        } elseif (strlen($empFullname) < 3 || strlen($empFullname) > 50) {
            $errorMessage = "ชื่อ-นามสกุลต้องมี 3-50 ตัวอักษร";
            return $errorMessage;
        } elseif (!preg_match('/^[a-zA-Z0-9_]*$/', $empUsername)) {
            $errorMessage = "ชื่อผู้ใช้มีได้เฉพาะภาษาอังกฤษและ _ เท่านั้น และไม่มีเว้นวรรค";
            return $errorMessage;
        } elseif (strlen($empUsername) < 6 || strlen($empUsername) > 30) {
            $errorMessage = "ชื่อผู้ใช้ต้องมี 3-30 ตัวอักษร";
            return $errorMessage;
        } elseif (!preg_match('/[A-Z]/', $empPassword) || !preg_match('/[a-z]/', $empPassword) || !preg_match('/[0-9]/', $empPassword) || !preg_match('/[!@#$%^&*()_+-|~=`{}\[\]:";\'<>?,.\/]/', $empPassword)) {
            $errorMessage = "รหัสผ่านต้องมี A-Z, a-z, 0-9, และสัญลักษณ์อย่างละ 1 ตัว ห้ามมีเว้นวรรค";
            return $errorMessage;
        } elseif (strlen($empPassword) < 8 || strlen($empPassword) > 16) {
            $errorMessage = "รหัสผ่านต้องมี 8-16 ตัวอักษร";
            return $errorMessage;
        } elseif ($empConfirmPassword !== $empPassword) {
            $errorMessage = "รหัสผ่านกับยืนยันรหัสผ่านไม่ตรงกัน";
            return $errorMessage;
        } elseif (!filter_var($empEmail, FILTER_VALIDATE_EMAIL)) {
            $errorMessage = "รูปแบบอีเมลไม่ถูกต้อง";
            return $errorMessage;
        } elseif (strlen($empEmail) < 16 || strlen($empEmail) > 65) {
            $errorMessage = "อีเมลต้องมี 16-65 ตัวอักษร";
            return $errorMessage;
        } elseif (!preg_match('/^0[0-9]*$/', $empTel)) {
            $errorMessage = "เบอร์โทรศัพท์ต้องเป็นตัวเลขและเริ่มด้วย 0 เท่านั้น";
            return $errorMessage;
        } elseif (strlen($empTel) < 9 || strlen($empTel) > 10) {
            $errorMessage = "เบอร์โทรศัพท์ต้องมี 9-10 ตัว";
            return $errorMessage;
        }

        // ส่งผลการตรวจสอบ
        return $errorMessage;
    }


    // เพิ่มข้อมูล Employee ที่มีสิทธิ์ Owner
    function insertOwner($empFullname, $empUsername, $empPassword, $empEmail, $empTel)
    {
        try {

            $empAuthorityType = 2;
            $empAuthorityTyp2 = 6;
            $empProfile = "uploads/profile_employees/profile_default.png";

            // Folder เก็บไฟล์
            $uploadsDirectory = "uploads/profile_employees/";

            // ตรวจสอบหากไม่มี folder ให้สร้าง Folder เก็บไฟล์
            if (!file_exists($uploadsDirectory)) {
                mkdir($uploadsDirectory, 0777, true);
            }

            // ตรวจสอบว่าไฟล์ profile_default.png มีอยู่หรือไม่
            if (!file_exists($empProfile)) {
                echo "ไฟล์ต้นฉบับไม่พบ";
                return false;
            }

            // ไฟล์ defaluft -> สุ่มชื่อ -> นำไปเก็บที่ $pathNewProfile
            $originalFileName = "profile_default.png";
            $newProfile = uniqid('profile_', true) . '.' . pathinfo($originalFileName, PATHINFO_EXTENSION);
            $pathNewProfile = $uploadsDirectory . $newProfile;

            // คัดลอกไฟล์
            if (copy($empProfile, $pathNewProfile)) {
                // Hash รหัสผ่าน
                $hashedPassword = password_hash($empPassword, PASSWORD_DEFAULT);

                // เพิ่มข้อมูล Employee
                $sql1 = "INSERT INTO bs_employees (emp_profile, emp_fullname, emp_username, emp_password, emp_email, emp_tel)
                         VALUES (:empProfile, :empFullname, :empUsername, :hashedPassword, :empEmail, :empTel)
                        ";
                $stmt1 = $this->db->prepare($sql1);
                $stmt1->bindParam(':empProfile', $newProfile);
                $stmt1->bindParam(':empFullname', $empFullname);
                $stmt1->bindParam(':empUsername', $empUsername);
                $stmt1->bindParam(':hashedPassword', $hashedPassword);
                $stmt1->bindParam(':empEmail', $empEmail);
                $stmt1->bindParam(':empTel', $empTel);
                $stmt1->execute();

                // เอา ID ที่เพิ่มเข้าไปในฐานข้อมูลล่าสุด
                $lastInsertId = $this->db->lastInsertId();

                // เพิ่มสิทธิ์ Owner
                $sql2 = "INSERT INTO bs_employees_authority (emp_id, emp_Authority_type_id)
                         VALUES (:empId, :empAuthorityTypeId)
                        ";
                $stmt2 = $this->db->prepare($sql2);
                $stmt2->bindParam(':empId', $lastInsertId);
                $stmt2->bindParam(':empAuthorityTypeId', $empAuthorityType);
                $stmt2->execute();

                // เพิ่มสิทธิ์ Employee
                $stmt3 = $this->db->prepare($sql2);
                $stmt3->bindParam(':empId', $lastInsertId);
                $stmt3->bindParam(':empAuthorityTypeId', $empAuthorityTyp2);
                $stmt3->execute();



                header("Location:owner_show.php");
                return true;
            } else {
                echo "เกิดข้อผิดพลาดในการคัดลอกไฟล์";
                return false;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }


    // แสดง Detail ข้อมูล Employee ตาม Id
    function getEmployeesDetail($empId)
    {
        try {
            $sql = "SELECT * FROM bs_employees
                    WHERE emp_id = :emp_id
                    ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":emp_id", $empId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }


    // ตรวจสอบ Form แก้ไขข้อมูล Employees
    function validateFormUpdateEmployee($empFullname, $empTel, $empId)
    {
        // เก็บข้อความ Error
        $errorMessage = "";

        // ตรวจสอบเงื่อนไขหากมี  $errorMessage จะหยุดตรวจสอบเงื่อนไขอื่น ๆ ทันที และแจ้งเตือน
        if (empty($empId) || empty($empFullname) || empty($empTel)) {
            $errorMessage = "กรุณากรอกข้อมูลให้ครบทุกช่อง";
            return $errorMessage;
        } elseif (!preg_match('/^[ก-๙เa-zA-Z\s\t]*$/', $empFullname)) {
            $errorMessage = "ชื่อ-นามสกุลห้ามมีตัวเลขและสัญลักษณ์พิเศษ";
            return $errorMessage;
        } elseif (strlen($empFullname) < 3 || strlen($empFullname) > 50) {
            $errorMessage = "ชื่อ-นามสกุลต้องมี 3-50 ตัวอักษร";
            return $errorMessage;
        } elseif (!preg_match('/^0[0-9]*$/', $empTel)) {
            $errorMessage = "เบอร์โทรศัพท์ต้องเป็นตัวเลขและเริ่มด้วย 0 เท่านั้น";
            return $errorMessage;
        } elseif (strlen($empTel) < 9 || strlen($empTel) > 10) {
            $errorMessage = "เบอร์โทรศัพท์ต้องมี 9-10 ตัว";
            return $errorMessage;
        }
        // ส่งผลการตรวจสอบ
        return $errorMessage;
    }


    // แก้ไขรายละเอียดข้อมูล Employees
    function updateEmployeesDetail($empFullname, $empTel, $empId)
    {
        try {
            $sql = "UPDATE bs_employees
                    SET emp_fullname = :emp_Fullname,
                        emp_tel = :emp_tel,
                        emp_uptime = NOW()
                    WHERE emp_id = :emp_id
                   ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":emp_Fullname", $empFullname);
            $stmt->bindParam(":emp_tel", $empTel);
            $stmt->bindParam(":emp_id", $empId);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }


    // ตรวจสอบไฟล์ Profile ที่อัปโหลดมา
    function validateUpdateEmployeesProfile($empNewProfile)
    {
        // เก็บข้อความข้อผิดพลาด
        $errorMessage = "";

        // ตรวจสอบประเภทของไฟล์
        $allowedMimeTypes = array("image/png", "image/jpeg", "image/jpg");
        $fileMimeType = $empNewProfile["type"];

        // ตรวจสอบว่าไฟล์มีประเภทที่ถูกต้องหรือไม่
        if (!in_array($fileMimeType, $allowedMimeTypes)) {
            $errorMessage = "รูปภาพต้องเป็นประเภท .png .jpg หรือ .jpeg เท่านั้น";
            return $errorMessage;

        }

        // ตรวจสอบขนาดของไฟล์
        $maxFileSize = 1 * 1024 * 1024; // 1MB
        if ($empNewProfile["size"] > $maxFileSize) {
            $errorMessage = "ขนาดไฟล์ต้องไม่เกิน 1MB";
            return $errorMessage;
        }

        // ส่งผลการตรวจสอบ
        return $errorMessage;
    }


    // แก้ไข Profile และบันทึก Database 
    function updateEmployeesProfile($empProfile, $empNewProfile, $empId)
    {
        try {
            // Folder เก็บไฟล์
            $uploadsDirectory = "uploads/profile_employees/";

            // ลบไฟล์เดิม (ถ้ามี)
            $oldFilePath = $uploadsDirectory . $empProfile;
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }

            // สุ่มชื่อไฟล์ใหม่
            $newProfile = uniqid('profile_', true) . '.' . pathinfo($empNewProfile['name'], PATHINFO_EXTENSION);
            $pathNewProfile = $uploadsDirectory . $newProfile;

            if (move_uploaded_file($empNewProfile['tmp_name'], $pathNewProfile)) {

                // ตัวแปร เก็บชื่อไฟล์ใหม่ลง  Database
                $empNewProfileName = $newProfile;

                $sql = "UPDATE bs_employees 
                    SET emp_profile = :emp_profile, 
                        emp_uptime = NOW() 
                    WHERE emp_id = :emp_id
                   ";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":emp_profile", $empNewProfileName);
                $stmt->bindParam(":emp_id", $empId);
                $stmt->execute();
                return true;
            } else {
                $errorMessage = "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
                return false;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }


    // แก้ไขสถานะบัญชีของพนักงาน
    function updateEmployeesStatus($empStatus, $empId)
    {
        try {
            $sql = "UPDATE bs_employees 
                    SET emp_status = :emp_status, 
                        emp_uptime = NOW() 
                    WHERE emp_id = :emp_id
                   ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":emp_status", $empStatus);
            $stmt->bindParam(":emp_id", $empId);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
?>