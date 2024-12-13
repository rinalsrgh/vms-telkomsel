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

// Mengambil data dari formulir
$name = isset($_POST['name']) ? $_POST['name'] : null;
$building = isset($_POST['building']) ? $_POST['building'] : null;
$purpose = isset($_POST['purpose']) ? $_POST['purpose'] : null;
$vehicle_number = isset($_POST['vehicle_number']) ? $_POST['vehicle_number'] : null;
$contact_number = isset($_POST['phone']) ? $_POST['phone'] : null;

// Cek apakah visitor dengan nama yang sama sudah ada
$sql_check = "SELECT id_visitor FROM visitors1 WHERE name = '$name' AND vehicle_number = '$vehicle_number' AND contact_number = '$contact_number' LIMIT 1";
$result_check = $conn->query($sql_check);

if ($result_check->num_rows > 0) {
    // Jika visitor sudah ada, gunakan id visitor yang ada
    $row = $result_check->fetch_assoc();
    $id_visitor = $row['id_visitor'];
} else {
    // Jika visitor belum ada, simpan data ke tabel visitors1
    $sql_visitor = "INSERT INTO visitors1 (name, vehicle_number, contact_number) 
                    VALUES ('$name', '$vehicle_number', '$contact_number')";

    if ($conn->query($sql_visitor) === TRUE) {
        $id_visitor = $conn->insert_id;
    } else {
        die("Error: " . $sql_visitor . "<br>" . $conn->error);
    }
}

// Generate unique code QR
$unique_code_qr = bin2hex(random_bytes(4)); // Menghasilkan random string 8 karakter

// Menyimpan data ke tabel visits
$sql_visit = "INSERT INTO visits (id_visitor, visit_purpose, checkin_time, checkout_time, visit_number, unique_code_qr, building, created_at) 
              VALUES ('$id_visitor', '$purpose', NULL, NULL, NULL, '$unique_code_qr', '$building', NOW())";

if ($conn->query($sql_visit) === TRUE) {
    $visit_id = $conn->insert_id;


    // Menyertakan pustaka QR Code
    include('phpqrcode/qrlib.php');

    // Menentukan path untuk menyimpan QR Code
    $qr_path = 'qrcodes/' . $unique_code_qr . '.png';

    // Data yang akan di-encode ke dalam QR Code
    $qr_data = 'http://visitormanagement.great-site.net/confirm_visit.php?code=' . $unique_code_qr;

    // Menghasilkan QR Code
    QRcode::png($qr_data, $qr_path);

    // Menampilkan halaman sukses
    echo '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Registration Success</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>
        <footer class="top-footer">
            <img src="logo4.png" alt="logo" class="logo">
        </footer>

        <div class="success-container">
            <h2>Registration Successful!</h2>
            <p>Thank you for registering, ' . htmlspecialchars($name ?? '') . '.</p>
            <p>Your visit to ' . htmlspecialchars($building ?? '') . ' has been recorded.</p>
            <p>Scan the QR code below to verify your visit:</p>
            <div class="qr-code">
                <img src="' . $qr_path . '" alt="QR Code">
            </div>
            <a href="index.php" class="button">Register Another Visitor</a>
            <a href="dashboard_user.php" class="button">View Your Visit Status</a>
        </div>

        <footer class="footer">
            <div class="footer-left">
                <p>&copy; PT TELEKOMUNIKASI SELULAR, 2024. </p>
            </div>
            <div class="footer-right">
                <a href="https://www.telkomsel.com/privacy-policy">Privacy Policy</a> 
                <a href="https://www.telkomsel.com/terms-and-conditions">Terms of Service</a>
                <a href="https://www.telkomsel.com/support/contact-us">Contact Us</a>
            </div>
        </footer>
    </body>
    </html>
    ';
} else {
    die("Error: " . $sql_visit . "<br>" . $conn->error);
}

$conn->close();
?>
