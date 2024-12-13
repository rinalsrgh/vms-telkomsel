<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "if0_36995947_visitors";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil id dari query string
$id_visit = isset($_GET['id']) ? $_GET['id'] : '';

// Mengambil data pengunjung berdasarkan id
$sql = "SELECT id_visit, checkout_time FROM visits WHERE id_visit = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_visit);
$stmt->execute();
$result = $stmt->get_result();
$visit = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $checkout_time = $_POST['checkout_time'];

    // Update waktu checkout di database
    $update_sql = "UPDATE visits SET checkout_time = ? WHERE id_visit = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('si', $checkout_time, $id_visit);
    
    if ($update_stmt->execute()) {
        echo "Checkout time updated successfully.";
        header("Location: manage_visitor.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Set Checkout Time</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <div class="form-container">
        <h2>Set Checkout Time</h2>
        <form method="POST" action="">
            <label for="checkout_time">Checkout Time:</label>
            <!-- Mengganti datetime-local dengan time -->
            <input type="time" id="checkout_time" name="checkout_time" value="<?php echo !empty($visit['checkout_time']) ? date('H:i', strtotime($visit['checkout_time'])) : ''; ?>" placeholder="--:--" required>
            <button type="submit">Update Checkout Time</button>
        </form>
    </div>
</body>
</html>

