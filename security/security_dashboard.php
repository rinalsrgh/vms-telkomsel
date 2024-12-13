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

// Menyiapkan variabel pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Menyusun query SQL dengan kondisi pencarian
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
    WHERE visitors1.name LIKE ?
    ORDER BY visits.created_at DESC
";

// Menyiapkan statement
$stmt = $conn->prepare($sql);

// Mengikat parameter
$searchTerm = '%' . $search . '%';
$stmt->bind_param('s', $searchTerm);

// Menjalankan statement
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Security Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
    <!-- Tambahkan link ke font-awesome atau stylesheet lain jika diperlukan -->
</head>
<body>
    <footer class="top-footer">
        <img src="../logo4.png" alt="logo" class="logo">
    </footer>

    <!-- Left side column. contains the logo and sidebar -->
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
                    <a href="security_dashboard.php">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
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

    <!-- Tabel Data Pengunjung -->
    <div class="table-container">
        <h2>Data Pengunjung</h2>
        <!-- Formulir Pencarian -->
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Cari..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Cari</button>
        </form>

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
                // Menampilkan data pengunjung
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
