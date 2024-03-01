<?php

// Nếu không phải là sự kiện đăng ký thì không xử lý
if (!isset($_POST['txtUsername'])) {
    die('');
}

//Nhúng file kết nối với database
include('ketnoi.php');

// Khai báo utf-8 để hiển thị được tiếng việt
header('Content-Type: text/html; charset=UTF-8');

// Lấy dữ liệu từ file dangky.php
$username = trim($_POST['txtUsername']);
$password = trim($_POST['txtPassword']);
$email = trim($_POST['txtEmail']);
$fullname = trim($_POST['txtFullname']);
$birthday = trim($_POST['txtBirthday']);
$sex = trim($_POST['txtSex']);

// Kiểm tra người dùng đã nhập liệu đầy đủ chưa
if (!$username || !$password || !$email || !$fullname || !$birthday || !$sex) {
    echo "Vui lòng nhập đầy đủ thông tin. <a href='javascript: history.go(-1)'>Trở lại</a>";
    exit;
}

// Mã hóa mật khẩu
$password = md5($password);

// Kết nối đến cơ sở dữ liệu
$conn = new PDO('mysql:host=localhost;dbname=demo', 'root', '');

// Chuẩn bị câu lệnh SQL
$stmt = $conn->prepare('SELECT COUNT(*) FROM member WHERE username = ?');
$stmt->execute([$username]);

// Kiểm tra tên đăng nhập đã có người dùng chưa
if ($stmt->fetchColumn() > 0) {
    echo "Tên đăng nhập này đã có người dùng. Vui lòng chọn tên đăng nhập khác. <a href='javascript: history.go(-1)'>Trở lại</a>";
    exit;
}

// Chuẩn bị câu lệnh SQL
$stmt = $conn->prepare('SELECT COUNT(*) FROM member WHERE email = ?');
$stmt->execute([$email]);

// Kiểm tra email đã có người dùng chưa
if ($stmt->fetchColumn() > 0) {
    echo "Email này đã có người dùng. Vui lòng chọn Email khác. <a href='javascript: history.go(-1)'>Trở lại</a>";
    exit;
}

// Kiểm tra định dạng ngày sinh
if (!preg_match('/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/', $birthday)) {
    echo "Ngày tháng năm sinh không hợp lệ. Vui long nhập lại. <a href='javascript: history.go(-1)'>Trở lại</a>";
    exit;
}

// Chuẩn bị câu lệnh SQL
$stmt = $conn->prepare('
    INSERT INTO member (username, password, email, fullname, birthday, sex)
    VALUES (?, ?, ?, ?, ?, ?)
');

// Thực thi câu lệnh SQL
$stmt->execute([$username, $password, $email, $fullname, $birthday, $sex]);

// Thông báo quá trình lưu
if ($stmt->rowCount() > 0) {
    echo "Quá trình đăng ký thành công. <a href='/'>Về trang chủ</a>";
} else {
    echo "Có lỗi xảy ra trong quá trình đăng ký. <a href='dangky.php'>Thử lại</a>";
}

$conn = null; // Đóng kết nối cơ sở dữ liệu

?>
