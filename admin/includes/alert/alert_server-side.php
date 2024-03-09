<!-- Sweetalert2 แจ้งเตือนจาก php  -->

<!-- หากเกิด Error จากฝั่ง server  -->
<?php if (isset($_SESSION['error'])) { ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'ไม่สำเร็จ',
            text: '<?php echo $_SESSION['error']; ?>',
        });
    </script>    
<?php unset($_SESSION['error']); } ?>



<!-- หาก Success สามารถจัดการข้อมูลได้สำเร็จ  -->
<?php if (isset($_SESSION['success'])) { ?>
    <script>
        Swal.fire({
            position: "center",
            icon: "success",
            title: "สำเร็จ",
            text: '<?php echo $_SESSION['success']; ?>',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
<?php
    unset($_SESSION['success']);
}
?>