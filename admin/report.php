
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

// Mendapatkan rentang tanggal dari form
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : "";
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : "";

// Mengambil data dari tabel visits dan visitors1 berdasarkan rentang tanggal
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
    WHERE DATE(visits.created_at) BETWEEN '$start_date' AND '$end_date'
    ORDER BY visits.created_at DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
    <!-- Tambahkan link ke font-awesome atau stylesheet lain jika diperlukan -->
</head>
<body>
    <footer class="top-footer">
        <img src="../logo4.png" alt="logo" class="logo">
    </footer>

    <!-- Sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="../logo4.png" alt="logo" class="logo">
                </div>
            </div>
           
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MAIN NAVIGATION</li>
                <li class="<?php if($page=='dashboard') { echo 'active'; }?>">
                    <a href="admin_dashboard.php">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="<?php if($page=='newvisitor') { echo 'active'; }?>">
                    <a href="new_visitor.php">
                        <i class="fa fa-user-plus"></i> <span>New Visitor</span>
                    </a>
                </li>
                <li class="<?php if($page=='manage_visitor') { echo 'active'; }?>">
                    <a href="manage_visitor.php">
                        <i class="fa fa-users"></i> <span>Manage Visitor</span>
                    </a>
                </li>
                <li class="<?php if($page=='report') { echo 'active'; }?>">
                    <a href="report.php">
                        <i class="fa fa-file-text"></i> <span>Report</span>
                    </a>
                </li>
                <li>
                    <a href="logout.php" class="security-login-button">
                        <i class="fa fa-sign-out"></i> <span>Logout</span>
                    </a>
                </li>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Report Section -->
    <div class="table-container">
        <h2>Visitor Report</h2>

        <!-- Formulir Pencarian Rentang Tanggal -->
        <form method="GET" action="">
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" id="start_date" value="<?php echo $start_date; ?>">

            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" id="end_date" value="<?php echo $end_date; ?>">

            <button type="submit">Filter</button>
        </form>

        <!-- Tabel Data Pengunjung -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
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

        <!-- Tombol Unduh PDF -->
        <a href="download_pdf.php?start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" class="download-pdf">Download Reports</a>
    </div>

    <footer class="footer">
        <div class="right">
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