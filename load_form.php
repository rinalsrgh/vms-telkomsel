<?php
include 'db.php'; // Pastikan Anda sudah membuat file ini

$name = isset($_GET['name']) ? $_GET['name'] : null;

$sql = "SELECT * FROM visitors WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(array());
}

$stmt->close();
$conn->close();
?>
