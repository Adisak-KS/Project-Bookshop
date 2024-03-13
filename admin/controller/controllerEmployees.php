<!-- จัดการเกี่ยวกับข้อมูลผู้ดูแลเว็บไซต์ทั้งหมด -->
<?php
class controllerEmployees
{
    private $db;

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

    // แก้ไขข้อมูลพนักงาน
    function updateEmployees($empFullname, $empTel, $empId){
        try{
            $sql = "UPDATE bs_employees
                    SET emp_fullname = :emp_fullname,
                        emp_tel = :emp_tel,
                        emp_uptime = NOW()
                    WHERE emp_id = :emp_id
                   ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":emp_fullname", $empFullname);
            $stmt->bindParam(":emp_tel", $empTel);
            $stmt->bindParam(":emp_id", $empId);
            $stmt->execute();
            return true;
        }catch(PDOException $e){
            echo $e->getMessage();
            return false;
        }
    }
}
?>