<?php
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

$query = isset($_GET['query']) ? $_GET['query'] : '';

if ($query) {
    $sql = "SELECT name, vehicle_number, contact_number FROM visitors1 WHERE name LIKE '%$query%' LIMIT 10";
    $result = $conn->query($sql);
    $visitors = [];
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $visitors[] = $row;
        }
    }
    
    echo json_encode($visitors);
}

$conn->close();
?>
