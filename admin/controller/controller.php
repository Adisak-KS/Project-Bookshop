<?php
class Controller
{
    private $db;

    function __construct($con)
    {
        $this->db = $con;
        // echo "เรียกใช้งาน Controller";
    }

    function checkEmpAuthorityTypeDefault()
    {
        try {
            $sql = "SELECT * FROM bs_employees_authority_type
                    WHERE emp_authority_type_name IN ('Owner', 'Admin', 'Accounting', 'Sale', 'Employee')
                   ";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            // หากเกิดข้อผิดพลาดส่งค่า false กลับ
            return false;
        }
    }

    function insertEmpAuthorityTypeDefault()
    {
        // ตรวจสอบประเภทสิทธิ์ว่ามีหรือไม่
        $chkAuthorityTypesDefault = $this->checkEmpAuthorityTypeDefault();

        // สร้างรายการของสิทธิ์ที่ต้องการในตาราง
        $requiredAuthorityTypes = [
            ['id' => 1, 'name' => 'Owner', 'detail' => 'เจ้าของ / ผู้บริหาร'],
            ['id' => 2, 'name' => 'Admin', 'detail' => 'ผู้ดูแลระบบ'],
            ['id' => 3, 'name' => 'Accounting', 'detail' => 'พนักงานบัญชี'],
            ['id' => 4, 'name' => 'Sale', 'detail' => 'พนักงานขาย'],
            ['id' => 5, 'name' => 'Employee', 'detail' => 'พนักงานทั่วไป']
        ];

        // หาประเภทสิทธิ์ที่ขาดหายไปในฐานข้อมูล
        $missingAuthorityTypes = array_diff(array_column($requiredAuthorityTypes, 'name'), array_column($chkAuthorityTypesDefault, 'emp_authority_type_name'));

        // หากมีสิทธิ์ที่หายไป ให้ทำการเพิ่มเข้าไปในฐานข้อมูล
        if (!empty($missingAuthorityTypes)) {
            foreach ($missingAuthorityTypes as $authorityType) {
                // หาข้อมูลของสิทธิ์ที่ต้องการเพิ่ม
                $type = array_filter($requiredAuthorityTypes, fn ($item) => $item['name'] === $authorityType);

                // ถ้าพบข้อมูลสิทธิ์ที่ต้องการเพิ่ม
                if (!empty($type)) {
                    $type = current($type);
                    // เพิ่มสิทธิ์ที่ไม่มีลงในฐานข้อมูล
                    $sql = "INSERT INTO bs_employees_authority_type (emp_authority_type_id, emp_authority_type_name, emp_authority_type_detail) VALUES (:emp_authority_type_id, :emp_authority_type_name, :emp_authority_type_detail)";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindParam(':emp_authority_type_id', $type['id']);
                    $stmt->bindParam(':emp_authority_type_name', $type['name']);
                    $stmt->bindParam(':emp_authority_type_detail', $type['detail']);
                    $stmt->execute();
                }
            }
        }
    }

    function getOwner()
    {
        try {
            // $sql = "SELECT a.*, GROUP_CONCAT(b.emp_authority_type_id) AS emp_authority_type_id
            //     FROM bs_employees a
            //     INNER JOIN bs_employees_authority b ON a.emp_id = b.emp_id
            //     GROUP BY a.emp_id
            //    ";
            $sql = "SELECT a.*, GROUP_CONCAT(b.emp_authority_type_id) AS emp_authority_type_id, GROUP_CONCAT(c.emp_authority_type_name) AS emp_authority_type_name
                    FROM bs_employees a
                    INNER JOIN bs_employees_authority b ON a.emp_id = b.emp_id
                    INNER JOIN bs_employees_authority_type c ON b.emp_authority_type_id = c.emp_authority_type_id
                    GROUP BY a.emp_id";
            $stmt = $this->db->query($sql);
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }

    function checkOwnerDefault()
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM bs_employees_authority
                    WHERE emp_authority_type_id = 1
                    ";
            $stmt = $this->db->query($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }

    function insertOwnerDefault()
    {
        try {
            $check = $this->checkOwnerDefault();

            if ($check["count"] > 0) {
                return false;
            } else {
                // ข้อมูลเริ่มต้น
                $empId = 1;
                $empProfile = "uploads/profile_employees/profile_default.png";
                $empFullname = "เจ้าของระบบ";
                $empUsername = "Owner1";
                $empPassword = "Owner_1"; // รหัสผ่านไม่ได้ถูกเข้ารหัสด้วย password_hash() ตรงนี้
                $empEmail = "adisak.general@gmail.com";
                $empTel = "0935797491";
                $empAuthorityType = 1;
                $empAuthorityTyp2 = 5;

                // Folder เก็บไฟล์
                $uploadsDirectory = "uploads/profile_employees/";

                // ตรวจสอบว่ามี Folder เก็บไฟล์หรือไม่ ถ้าไม่มีให้สร้าง
                if (!file_exists($uploadsDirectory)) {
                    mkdir($uploadsDirectory, 0777, true);
                }

                // ตรวจสอบว่าไฟล์ profile_default.png มีอยู่หรือไม่
                if (!file_exists($empProfile)) {
                    echo "ไฟล์ต้นฉบับไม่พบ";
                    return false;
                }

                // ชื่อไฟล์ต้นฉบับ
                $originalFileName = "profile_default.png";

                // สุ่มชื่อใหม่
                $newProfile = uniqid('profile_', true) . '.' . pathinfo($originalFileName, PATHINFO_EXTENSION);

                // ที่เก็บไฟล์ $newProfile
                $pathNewProfile = $uploadsDirectory . $newProfile;

                // คัดลอกไฟล์
                if (copy($empProfile, $pathNewProfile)) {
                    // Hash รหัสผ่าน
                    $hashedPassword = password_hash($empPassword, PASSWORD_DEFAULT);

                    // เพิ่มข้อมูล Employee
                    $sql1 = "INSERT INTO bs_employees (emp_id, emp_profile, emp_fullname, emp_username, emp_password, emp_email, emp_tel)
                         VALUES (:empId, :empProfile, :empFullname, :empUsername, :hashedPassword, :empEmail, :empTel)";
                    $stmt1 = $this->db->prepare($sql1);
                    $stmt1->bindParam(':empId', $empId);
                    $stmt1->bindParam(':empProfile', $newProfile);
                    $stmt1->bindParam(':empFullname', $empFullname);
                    $stmt1->bindParam(':empUsername', $empUsername);
                    $stmt1->bindParam(':hashedPassword', $hashedPassword);
                    $stmt1->bindParam(':empEmail', $empEmail);
                    $stmt1->bindParam(':empTel', $empTel);
                    $stmt1->execute();

                    // เพิ่มสิทธิ์ Owner
                    $sql2 = "INSERT INTO bs_employees_authority (emp_id, emp_Authority_type_id)
                         VALUES (:empId, :empAuthorityTypeId)";
                    $stmt2 = $this->db->prepare($sql2);
                    $stmt2->bindParam(':empId', $empId);
                    $stmt2->bindParam(':empAuthorityTypeId', $empAuthorityType);
                    $stmt2->execute();

                    // เพิ่มสิทธิ์ Employee
                    $stmt2->bindParam(':empId', $empId);
                    $stmt2->bindParam(':empAuthorityTypeId', $empAuthorityTyp2);
                    $stmt2->execute();

                    return true; // หลังจากการเพิ่มข้อมูลสำเร็จ
                } else {
                    echo "เกิดข้อผิดพลาดในการคัดลอกไฟล์";
                    return false;
                }
            }
        } catch (PDOException $e) {
            return false;
        }
    }
}
