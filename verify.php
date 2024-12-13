<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "if0_36995947_visitors";

// Mengatur timezone Jakarta
date_default_timezone_set('Asia/Jakarta');

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil ID pengunjung dari QR Code jika ada
if (isset($_GET['id'])) {
    $visitor_id = $_GET['id'];

    // Memeriksa data pengunjung di database
    $stmt = $conn->prepare("SELECT * FROM visitors WHERE id = ?");
    $stmt->bind_param("s", $visitor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Data pengunjung ditemukan
        $row = $result->fetch_assoc();

        // Cek status saat ini
        if ($row['status'] == 'Waiting') {
            // Mengupdate status menjadi "Approve" dan mencatat waktu check-in
            $checkin_time = date('Y-m-d H:i:s'); // Menyimpan waktu saat ini
            $update_stmt = $conn->prepare("UPDATE visitors SET status = 'Approve', checkin = ? WHERE id = ?");
            $update_stmt->bind_param("ss", $checkin_time, $visitor_id);
            $update_stmt->execute();

            echo '
            <!DOCTYPE html>
            <html>
            <head>
                <title>Visitor Verification</title>
                <link rel="stylesheet" type="text/css" href="styles.css">
            </head>
            <body>
                <footer class="top-footer">
                    <img src="logo/logo4.png" alt="logo" class="logo">
                </footer>

                <div class="success-container">
                    <h2>Visitor Verified!</h2>
                    <p>Visitor ID: ' . htmlspecialchars($visitor_id) . '</p>
                    <p>Name: ' . htmlspecialchars($row['name']) . '</p>
                    <p>Building: ' . htmlspecialchars($row['building']) . '</p>
                    <p>Purpose: ' . htmlspecialchars($row['purpose']) . '</p>
                    <p>Check-in Time: ' . htmlspecialchars($checkin_time) . '</p>
                    <p>Check-out Time: ' . htmlspecialchars($row['checkout']) . '</p>
                    <p>Status: Approved</p>
                </div>

                <footer class="footer">
                    <div class="footer-left">
                        <p>&copy; PT TELEKOMUNIKASI SELULAR, 2024.</p>
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
            // Status sudah tidak dalam waiting
            echo '
            <!DOCTYPE html>
            <html>
            <head>
                <title>Visitor Verification</title>
                <link rel="stylesheet" type="text/css" href="styles.css">
            </head>
            <body>
                <footer class="top-footer">
                    <img src="logo/logo4.png" alt="logo" class="logo">
                </footer>

                <div class="error-container">
                    <h2>Visitor Check-out!</h2>
                    <p>Visitor ID: ' . htmlspecialchars($visitor_id) . '</p>
                    <p>Name: ' . htmlspecialchars($row['name']) . '</p>
                    <p>Building: ' . htmlspecialchars($row['building']) . '</p>
                    <p>Purpose: ' . htmlspecialchars($row['purpose']) . '</p>
                    <p>Check-out Time: ' . htmlspecialchars($checkout_time) . '</p>
                    <p>Status: Check-out</p>
                    <p>Visitor has already been verified or rejected.</p>
                    <a href="index.php" class="button">Back to Dashboard</a>
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
        }
    } else {
        // Data pengunjung tidak ditemukan
        echo '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Visitor Verification</title>
            <link rel="stylesheet" type="text/css" href="styles.css">
        </head>
        <body>
            <footer class="top-footer">
                <img src="logo/logo4.png" alt="logo" class="logo">
            </footer>

            <div class="error-container">
                <h2>Verification Failed!</h2>
                <p>Visitor ID not found.</p>
                <a href="index.php" class="button">Try Again</a>
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
    }
    $stmt->close();
} else {
    echo '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Visitor Verification</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>
        <footer class="top-footer">
            <img src="logo/logo4.png" alt="logo" class="logo">
        </footer>

        <div class="error-container">
            <h2>Verification Failed!</h2>
            <p>Invalid or missing Visitor ID.</p>
            <a href="index.php" class="button">Try Again</a>
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
}

$conn->close();
?>