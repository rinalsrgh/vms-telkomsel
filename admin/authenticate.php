<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "if0_36995947_visitors";

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil data dari form login
$username = $_POST['username'];
$password = $_POST['password'];

// Memeriksa data admin di database
$sql = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Login berhasil
    $_SESSION['admin'] = $username;
    header("Location: admin_dashboard.php");
} else {
    // Login gagal
    echo "<script>alert('Invalid username or password.'); window.location.href = 'login.php';</script>";
}

$conn->close();
?>
