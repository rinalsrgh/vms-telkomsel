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

// Menyiapkan variabel pencarian dan sorting
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'created_at';

// Menentukan pengurutan data berdasarkan parameter
switch ($sort) {
    case 'today':
        $filter = "DATE(visits.created_at) = CURDATE()";
        break;
    case 'yesterday':
        $filter = "DATE(visits.created_at) = CURDATE() - INTERVAL 1 DAY";
        break;
    case 'this_week':
        $filter = "YEARWEEK(visits.created_at, 1) = YEARWEEK(CURDATE(), 1)";
        break;
    default:
        $filter = "1 = 1"; // Tidak ada filter khusus
        break;
}

// Menyusun query SQL dengan kondisi pencarian dan pengurutan
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
    WHERE visitors1.name LIKE ? AND $filter
    ORDER BY visits.created_at DESC
";

// Menyiapkan statement
$stmt = $conn->prepare($sql);
$searchTerm = '%' . $search . '%';
$stmt->bind_param('s', $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// Mengambil data pengunjung untuk hari ini
$sql_today = "SELECT COUNT(*) as total FROM visits WHERE DATE(created_at) = CURDATE()";
$result_today = $conn->query($sql_today);
$today_visitor = $result_today->fetch_assoc()['total'];

// Mengambil data pengunjung untuk kemarin
$sql_yesterday = "SELECT COUNT(*) as total FROM visits WHERE DATE(created_at) = CURDATE() - INTERVAL 1 DAY";
$result_yesterday = $conn->query($sql_yesterday);
$yesterday_visitor = $result_yesterday->fetch_assoc()['total'];

// Mengambil data pengunjung untuk minggu ini (dari hari Senin hingga hari ini)
$sql_this_week = "SELECT COUNT(*) as total FROM visits WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)";
$result_this_week = $conn->query($sql_this_week);
$this_week_visitor = $result_this_week->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <footer class="top-footer">
        <img src="../logo4.png" alt="logo" class="logo">
    </footer>

    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="../logo4.png" alt="logo" class="logo">
                </div>
            </div>
           
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
    </aside>

    <div class="content-wrapper">
        <div class="visitor-stats">
            <div class="stat-box">
                <h3><a href="?search=<?php echo urlencode($search); ?>&sort=today">Today's Visitors</a></h3>
                <p><?php echo $today_visitor; ?></p>
            </div>
            <div class="stat-box">
                <h3><a href="?search=<?php echo urlencode($search); ?>&sort=yesterday">Yesterday's Visitors</a></h3>
                <p><?php echo $yesterday_visitor; ?></p>
            </div>
            <div class="stat-box">
                <h3><a href="?search=<?php echo urlencode($search); ?>&sort=this_week">Visitors This Week</a></h3>
                <p><?php echo $this_week_visitor; ?></p>
            </div>
        </div>

        <div class="table-container">
            <h2>Data Pengunjung</h2>
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
    </div>
</body>
</html>

<?php
$conn->close();
?>
