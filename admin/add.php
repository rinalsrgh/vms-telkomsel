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

if (isset($_POST['add'])) {
    $date = $_POST['date'];
    $name = $_POST['name'];
    $building = $_POST['building'];
    $purpose = $_POST['purpose'];
    $phone = $_POST['phone'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $vehicle_number = $_POST['vehicle_number'];

    // Menentukan status otomatis sebagai 'Confirmed'
    $status = 'Confirmed';

    // Menyimpan data ke tabel visits
    $sql_visits = "INSERT INTO visits (visit_purpose, building, status, checkin_time, checkout_time, created_at) 
                   VALUES ('$purpose', '$building', '$status', '$checkin', '$checkout', NOW())";

    if ($conn->query($sql_visits) === TRUE) {
        // Ambil ID dari record yang baru saja dimasukkan ke tabel visits
        $visit_id = $conn->insert_id;
        
        // Menyimpan data ke tabel visitors1
        $sql_visitors1 = "INSERT INTO visitors1 (id_visitor, name, contact_number, vehicle_number) 
                          VALUES ('$visit_id', '$name', '$phone', '$vehicle_number')";

        if ($conn->query($sql_visitors1) === TRUE) {
            // Update tabel visits untuk menyimpan id_visitor
            $sql_update_visits = "UPDATE visits SET id_visitor = '$visit_id' WHERE id_visit = '$visit_id'";

            if ($conn->query($sql_update_visits) === TRUE) {
                header("Location: admin_dashboard.php");
                exit();
            } else {
                echo "Error updating visits with id_visitor: " . $conn->error;
            }
        } else {
            echo "Error inserting into visitors1: " . $conn->error;
        }
    } else {
        echo "Error inserting into visits: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Visitor</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <footer class="top-footer">
        <img src="../logo4.png" alt="logo" class="logo">
    </footer>
    <div class="container">
        <h2>Tambah Data Pengunjung</h2>
        <form method="post" action="add.php">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="building">Building:</label>
            <input type="text" id="building" name="building" required>
            <label for="purpose">Purpose:</label>
            <input type="text" id="purpose" name="purpose" required>
            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" required>
            <label for="checkin">Check-in Time:</label>
            <input type="time" id="checkin" name="checkin" required>
            <label for="checkout">Check-out Time:</label>
            <input type="time" id="checkout" name="checkout" required>
            <input type="submit" name="add" value="Add">
        </form>
    </div>
    <footer class="footer">
        <div class="left">
            <p>&copy; PT TELEKOMUNIKASI SELULAR, 2024.</p>
        </div>
        <div class="right">
            <a href="https://www.telkomsel.com/privacy-policy">Privacy Policy</a> 
            <a href="https://www.telkomsel.com/terms-and-conditions">Terms of Service</a>
            <a href="https://www.telkomsel.com/support/contact-us">Contact Us</a>
        </div>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
