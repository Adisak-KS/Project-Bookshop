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
            $sql = "SELECT a.*, GROUP_CONCAT(b.emp_authority_type_id) 
                    AS emp_authority_type_id, GROUP_CONCAT(c.emp_authority_type_name) 
                    AS emp_authority_type_name
                    FROM bs_employees a
                    INNER JOIN bs_employees_authority b ON a.emp_id = b.emp_id
                    INNER JOIN bs_employees_authority_type c ON b.emp_authority_type_id = c.emp_authority_type_id
                    GROUP BY a.emp_id
                   ";
            $result = $this->db->query($sql);
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>