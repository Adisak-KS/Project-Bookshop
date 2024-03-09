<!-- กำหนดข้อมูลเริ่มต้นของเว็บไซต์  -->

<?php
class ControllerDefalut
{
    private $db;

    function __construct($conn)
    {
        $this->db = $conn;
        // echo "เรียกใช้งาน Controller Default เริ่มต้นสำเร็จ";
    }

    // ตรวจสอบประเภทสิทธิ์เริ่มต้น
    function checkEmpAuthorityTypeDefault()
    {
        try {
            $sql = "SELECT * FROM bs_employees_authority_type
                    WHERE emp_authority_type_name 
                    IN ('Super', 'Owner', 'Admin', 'Accounting', 'Sale', 'Employee')
                   ";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // เพิ่มประเภทสิทธิ์เริ่มต้น
    function insertEmpAuthorityTypeDefault()
    {
        // ตรวจสอบประเภทสิทธิ์ว่ามีหรือไม่
        $chkAuthorityTypesDefault = $this->checkEmpAuthorityTypeDefault();

        // สร้างรายการของสิทธิ์ที่ต้องการในตาราง
        $requiredAuthorityTypes = [
            ['id' => 1, 'name' => 'Super', 'detail' => 'เจ้าของระบบ'],
            ['id' => 2, 'name' => 'Owner', 'detail' => 'เจ้าของร้าน / ผู้บริหาร'],
            ['id' => 3, 'name' => 'Admin', 'detail' => 'ผู้ดูแลระบบ'],
            ['id' => 4, 'name' => 'Accounting', 'detail' => 'พนักงานบัญชี'],
            ['id' => 5, 'name' => 'Sale', 'detail' => 'พนักงานขาย'],
            ['id' => 6, 'name' => 'Employee', 'detail' => 'พนักงานทั่วไป']
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

    // ตรวจสอบพนักงานที่มีสิทธิ์ Super
    function checkSuperDefault()
    {
        try {
            $sql = "SELECT COUNT(*) AS count FROM bs_employees_authority
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

    // หากไม่พบพนักงานที่มีสิทธิ์ Super ให้เพิ่มข้อมูลพนักงานที่มีสิทธิ์ Super เริ่มต้น
    function insertSuperDefault()
    {
        try {
            $check = $this->checkSuperDefault();

            if ($check["count"] > 0) {
                return false;
            } else {
                // ข้อมูลเริ่มต้น
                $empId = 1;
                $empProfile = "uploads/profile_employees/profile_default.png";
                $empFullname = "เจ้าของระบบ";
                $empUsername = "Super";
                $empPassword = "Super_1"; // รหัสผ่านไม่ได้ถูกเข้ารหัสด้วย password_hash() ตรงนี้
                $empEmail = "adisak.khongsuk@gmail.com";
                $empTel = "0935797491";
                $empAuthorityType = 1;
                $empAuthorityTyp2 = 6;

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
                    $sql1 = "INSERT INTO bs_employees (emp_id, emp_profile, emp_fullname, emp_username, emp_password, emp_email, emp_tel)
                             VALUES (:empId, :empProfile, :empFullname, :empUsername, :hashedPassword, :empEmail, :empTel)
                            ";
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
                             VALUES (:empId, :empAuthorityTypeId)
                            ";
                    $stmt2 = $this->db->prepare($sql2);
                    $stmt2->bindParam(':empId', $empId);
                    $stmt2->bindParam(':empAuthorityTypeId', $empAuthorityType);
                    $stmt2->execute();

                    // เพิ่มสิทธิ์ Employee
                    $stmt3 = $this->db->prepare($sql2);
                    $stmt3->bindParam(':empId', $empId);
                    $stmt3->bindParam(':empAuthorityTypeId', $empAuthorityTyp2);
                    $stmt3->execute();

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


    // ตรวจสอบพนักงานที่มีสิทธิ์ Owner
    function checkOwnerDefault()
    {
        try {
            $sql = "SELECT COUNT(*) AS count FROM bs_employees_authority
                    WHERE emp_authority_type_id = 2
                   ";
            $stmt = $this->db->query($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }

    // หากไม่พบพนักงานที่มีสิทธิ์ Owner ให้เพิ่มข้อมูลพนักงานที่มีสิทธิ์ Owner เริ่มต้น
    function insertOwnerDefault()
    {
        try {
            $check = $this->checkOwnerDefault();

            if ($check["count"] > 0) {
                return false;
            } else {
                // ข้อมูลเริ่มต้น
                $empId = 2;
                $empProfile = "uploads/profile_employees/profile_default.png";
                $empFullname = "เจ้าของร้าน";
                $empUsername = "Owner1";
                $empPassword = "Owner_1"; // รหัสผ่านไม่ได้ถูกเข้ารหัสด้วย password_hash() ตรงนี้
                $empEmail = "adisak.general@gmail.com";
                $empTel = "0935797491";
                $empAuthorityType = 2;
                $empAuthorityTyp2 = 6;

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
                    $sql1 = "INSERT INTO bs_employees (emp_id, emp_profile, emp_fullname, emp_username, emp_password, emp_email, emp_tel)
                             VALUES (:empId, :empProfile, :empFullname, :empUsername, :hashedPassword, :empEmail, :empTel)
                            ";
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
                             VALUES (:empId, :empAuthorityTypeId)
                            ";
                    $stmt2 = $this->db->prepare($sql2);
                    $stmt2->bindParam(':empId', $empId);
                    $stmt2->bindParam(':empAuthorityTypeId', $empAuthorityType);
                    $stmt2->execute();

                    // เพิ่มสิทธิ์ Employee
                    $stmt3 = $this->db->prepare($sql2);
                    $stmt3->bindParam(':empId', $empId);
                    $stmt3->bindParam(':empAuthorityTypeId', $empAuthorityTyp2);
                    $stmt3->execute();

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
