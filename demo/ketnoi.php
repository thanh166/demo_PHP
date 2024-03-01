<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "demo";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Hàm thực hiện truy vấn
function query($sql) {
    global $conn;
    return mysqli_query($conn, $sql);
}

?>