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

// Proses penghapusan data jika ada id di query string
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM visits WHERE id_visit='$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: manage_visitor.php");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>
