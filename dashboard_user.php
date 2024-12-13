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

// Mengambil data dari tabel visits dan visitors1
$sql = "
    SELECT 
        visits.id_visit, 
        visits.visit_purpose, 
        visits.visit_number, 
        visits.building, 
        visits.status, 
        visitors1.name, 
        visitors1.contact_number, 
        visits.checkin_time as checkin, 
        visits.checkout_time as checkout, 
        visits.created_at, 
        DATE(visits.created_at) as date
    FROM visits 
    JOIN visitors1 ON visits.id_visitor = visitors1.id_visitor
    ORDER BY visits.created_at DESC
";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <footer class="top-footer">
        <img src="../logo4.png" alt="logo" class="logo">
    </footer>

    <div class="user-dashboard">
        <h2>Dashboard Pengunjung</h2>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Bangunan</th>
                    <th>Tujuan</th>
                    <th>Telepon</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Dibuat Pada</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Menentukan kelas berdasarkan status
                            $statusClass = '';
                            if ($row["status"] == 'Waiting') {
                                $statusClass = 'status-waiting';
                            } elseif ($row["status"] == 'Confirmed') {
                                $statusClass = 'status-confirmed';
                            }
                            
                            echo "<tr>
                                    <td>" . $row["id_visit"] . "</td>
                                    <td>" . $row["date"] . "</td>
                                    <td>" . $row["name"] . "</td>
                                    <td>" . $row["building"] . "</td>
                                    <td>" . $row["visit_purpose"] . "</td>
                                    <td>" . $row["contact_number"] . "</td>
                                    <td>" . $row["checkin"] . "</td>
                                    <td>" . $row["checkout"] . "</td>
                                    <td>" . $row["created_at"] . "</td>
                                    <td class='" . $statusClass . "'>" . $row["status"] . "</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='10'>Tidak ada data pengunjung</td></tr>";
                    }
                    ?>
            </tbody>
        </table>
        <a href="index.php" class="button-user">Register Another Visitor</a>
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

<?php
$conn->close();
?>
