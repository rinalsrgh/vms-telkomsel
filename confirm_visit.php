<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Mendapatkan kode unik dari request
$unique_code_qr = isset($_GET['code']) ? $_GET['code'] : null;

if ($unique_code_qr) {
    // Dapatkan tanggal hari ini
    $today = date('Y-m-d');

    // Query untuk mendapatkan visit_number terakhir di hari yang sama
    $sql = "SELECT visit_number FROM visits WHERE DATE(checkin_time) = '$today' ORDER BY visit_number DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Jika ada kunjungan sebelumnya pada hari yang sama, tambahkan 1 ke visit_number
        $row = $result->fetch_assoc();
        $new_visit_number = $row['visit_number'] + 1;
    } else {
        // Jika ini adalah kunjungan pertama pada hari itu, set visit_number ke 1
        $new_visit_number = 1;
    }

    // Update status di tabel visit dengan visit_number yang baru
    $sql = "UPDATE visits SET status='Confirmed', checkin_time=NOW(), visit_number=$new_visit_number WHERE unique_code_qr='$unique_code_qr'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Visit Confirmed', 'visit_number' => $new_visit_number]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error Confirming Visit']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid or missing QR code']);
}

$conn->close();
?>
