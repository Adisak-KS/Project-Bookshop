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
                    WHERE emp_authority_type_name IN ('Owner', 'Admin', 'Accounting', 'Sale', 'Employee')";
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

    // function checkOwnerDefault()
    // {
    //     try {
    //         $sql = "SELECT * FROM bs_employees
    //                 WHERE emp_username IN ('Owner1')";
    //         $stmt = $this->db->query($sql);
    //         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     } catch (PDOException $e) {
    //         echo $e->getMessage();
    //         // หากเกิดข้อผิดพลาดส่งค่า false กลับ
    //         return false;
    //     }
    // }
    // function insertOwenrDefault()
    // {
    //     // ตรวจสอบประเภทสิทธิ์ว่ามีหรือไม่
    //     $chkOwenerDefault = $this->checkOwnerDefault();
    // }

    function showEmployees()
    {
        try {
            $sql = "SELECT a.*, b.emp_authority_type_id
                    FROM bs_employees a  
                    INNER JOIN bs_employees_authority b
                    ON a.emp_id = b.emp_id 
                    WHERE a.emp_id = b.emp_id
                    ";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
        }
    }
}
